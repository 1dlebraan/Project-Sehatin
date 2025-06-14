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
        Schema::create('jadwal_poli', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poli_id')->constrained('poli')->onDelete('cascade');  // Foreign key ke tabel poli
            $table->string('hari');
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->integer('kuota');
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_poli');
    }
};
