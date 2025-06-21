<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Metode 'up' akan dijalankan saat Anda menjalankan 'php artisan migrate'.
     */
    public function up(): void
    {
        // Pastikan tabel 'antrian_poli' tidak lagi mereferensikan 'pendaftaran_pasien'
        // Jika 'iduser' di 'antrian_poli' adalah foreign key ke 'pendaftaran_pasien',
        // Anda perlu memodifikasi migrasi 'antrian_poli' terlebih dahulu,
        // atau menambahkan migrasi lain untuk menghapus FK sebelum drop tabel ini.
        // Asumsi: 'iduser' di 'antrian_poli' sekarang akan mereferensikan 'users'.
        // Jika sebelumnya 'iduser' mereferensikan 'pendaftaran_pasien', Anda perlu:
        // 1. Membuat migrasi baru untuk mengubah foreign key di antrian_poli dari pendaftaran_pasien ke users.
        // 2. Kemudian baru drop tabel pendaftaran_pasien.

        // Untuk saat ini, kita langsung drop. Jika ada masalah FK, Anda akan melihat error.
        Schema::dropIfExists('pendaftaran_pasien');
    }

    /**
     * Reverse the migrations.
     * Metode 'down' akan dijalankan saat Anda menjalankan 'php artisan migrate:rollback'.
     * Ini digunakan untuk mengembalikan tabel yang dihapus (dengan struktur lama jika memungkinkan).
     */
    public function down(): void
    {
        // Karena kita tidak memiliki definisi asli tabel pendaftaran_pasien,
        // jika rollback dilakukan, tabel ini tidak akan dibuat ulang dengan benar.
        // Ini hanyalah placeholder. Anda harus memiliki migrasi asli untuk recreate tabel.
        Schema::create('pendaftaran_pasien', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iduser'); // Contoh foreign key, sesuaikan jika berbeda
            // Tambahkan kembali kolom-kolom lama pendaftaran_pasien di sini jika Anda benar-benar perlu rollback
            // $table->string('nama_lengkap');
            // $table->string('alamat');
            // ...
            $table->timestamps();
        });
    }
};
