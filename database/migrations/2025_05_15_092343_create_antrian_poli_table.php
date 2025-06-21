<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi
     */
    public function up(): void
    {
        Schema::create('antrian_poli', function (Blueprint $table) {
            $table->id(); // ID utama

            // GANTI: gunakan user_id sebagai foreign key
            // Pastikan Anda menggunakan 'iduser' jika itu nama kolom FK Anda yang sebenarnya.
            // Konvensi Laravel adalah foreignId('user_id'), tapi jika Anda sudah pakai 'iduser' dan sudah ran,
            // pertahankan 'iduser'.
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');

            $table->foreignId('poli_id')->constrained('poli')->onDelete('cascade');
            $table->integer('nomor_antrian');
            $table->date('tanggal_antrian');

            // --- PASTIKAN KOLOM-KOLOM INI ADA DI SINI ---
            $table->time('waktu_datang')->nullable();
            $table->time('waktu_dipanggil')->nullable();
            $table->integer('waktu_tunggu')->nullable(); // dalam menit
            // --- AKHIR PASTIKAN KOLOM-KOLOM INI ADA DI SINI ---

            $table->enum('status_antrian', ['menunggu', 'dipanggil', 'selesai', 'batal'])->default('menunggu');

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Rollback (jika dibatalkan)
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian_poli');
    }
};
