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
        // Modifikasi tabel 'users' yang sudah ada
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom 'nik' (Nomor Induk Kependudukan)
            // nullable(): kolom ini bisa kosong
            // unique(): memastikan setiap NIK unik
            // after('id'): menempatkan kolom ini setelah kolom 'id'
            $table->string('nik', 16)->unique()->nullable()->after('id'); // NIK biasanya 16 digit

            // Menambahkan kolom 'tempat_lahir'
            // nullable(): kolom ini bisa kosong
            // after('name'): menempatkan kolom ini setelah kolom 'name'
            $table->string('tempat_lahir')->nullable()->after('name');

            // Menambahkan kolom 'tanggal_lahir'
            // nullable(): kolom ini bisa kosong
            // after('tempat_lahir'): menempatkan kolom ini setelah kolom 'tempat_lahir'
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');

            // Menambahkan kolom 'jenis_kelamin' dengan tipe ENUM
            // nullable(): kolom ini bisa kosong
            // after('tanggal_lahir'): menempatkan kolom ini setelah kolom 'tanggal_lahir'
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->nullable()->after('tanggal_lahir');
        });
    }

    /**
     * Reverse the migrations.
     * Metode 'down' akan dijalankan saat Anda menjalankan 'php artisan migrate:rollback'.
     * Ini digunakan untuk mengembalikan perubahan yang dilakukan di metode 'up'.
     */
    public function down(): void
    {
        // Mengembalikan perubahan pada tabel 'users'
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom-kolom dalam urutan terbalik dari penambahannya
            $table->dropColumn('jenis_kelamin');
            $table->dropColumn('tanggal_lahir');
            $table->dropColumn('tempat_lahir');
            $table->dropColumn('nik');
        });
    }
};
