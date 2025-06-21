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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK ke users
            $table->string('nama_pengguna');
            $table->enum('peran', ['admin', 'petugas', 'pasien']);
            $table->text('aktivitas'); // Deskripsi aktivitas
            $table->time('waktu'); // Hanya waktu
            $table->timestamp('timestamp'); // Waktu lengkap (tanggal dan waktu)
            $table->timestamps(); // created_at dan updated_at (opsional tambahan)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
