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
        Schema::create('petugas_jadwal_poli', function (Blueprint $table) {
            $table->id(); // ID petugas jadwal poli
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK ke users
            $table->foreignId('jadwal_poli_id')->constrained('jadwal_poli')->onDelete('cascade'); // FK ke jadwal_poli
            $table->string('nama_lengkap'); // Tambahkan kolom nama lengkap
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif'); // status petugas jadwal
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas_jadwal_poli');
    }
};