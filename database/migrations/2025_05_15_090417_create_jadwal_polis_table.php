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
         Schema::create('jadwal_poli', function (Blueprint $table) {
            $table->id();  // Kolom ID Jadwal sebagai primary key
            $table->foreignId('poli_id')->constrained('poli')->onDelete('cascade');  // Foreign key ke tabel poli
            $table->string('hari');  // Kolom hari
            $table->time('jam_buka');  // Kolom jam buka
            $table->time('jam_tutup');  // Kolom jam tutup
            $table->integer('kuota');  // Kolom kuota
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');  // Kolom status
            $table->timestamps();  // Kolom created_at dan updated_at
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
