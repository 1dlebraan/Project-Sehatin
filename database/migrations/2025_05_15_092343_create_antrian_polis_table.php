<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('antrian_poli', function (Blueprint $table) {
            $table->id(); // ID antrian poli
            $table->foreignId('pendaftaran_pasien_id')->constrained('pendaftaran_pasien')->onDelete('cascade');
            $table->foreignId('poli_id')->constrained('poli')->onDelete('cascade');
            $table->integer('nomor_antrian');
            $table->date('tanggal_antrian');
            $table->time('waktu_datang')->nullable();
            $table->time('waktu_dipanggil')->nullable();
            $table->integer('waktu_tunggu')->nullable(); // dalam menit atau detik
            $table->enum('status_antrian', ['menunggu', 'dipanggil', 'selesai', 'batal'])->default('menunggu');
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian_poli');
    }
};
