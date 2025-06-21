<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('antrian_poli', function (Blueprint $table) {
            // Kolom waktu_selesai untuk mencatat kapan layanan selesai
            $table->time('waktu_selesai')->nullable()->after('waktu_dipanggil');
            // Kolom lama_layanan (dalam menit)
            $table->integer('lama_layanan')->nullable()->after('waktu_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrian_poli', function (Blueprint $table) {
            $table->dropColumn('lama_layanan');
            $table->dropColumn('waktu_selesai');
        });
    }
};
