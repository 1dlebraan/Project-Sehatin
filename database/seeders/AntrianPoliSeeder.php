<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AntrianPoliModel; // Pastikan ini mengarah ke model AntrianPoli Anda
use App\Models\UsersModel; // Untuk mencari user pasien
use App\Models\Poli; // Untuk mencari poli
use Carbon\Carbon; // Untuk bekerja dengan tanggal dan waktu

class AntrianPoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil semua ID poli yang ada di database
        $poliIds = Poli::pluck('id')->toArray();
        // Ambil semua ID user dengan role 'pasien'
        $pasienUserIds = UsersModel::where('role', 'pasien')->pluck('id')->toArray();

        // Cek apakah ada poli atau user pasien yang terdaftar
        if (empty($poliIds)) {
            $this->command->info('Tidak ada poli ditemukan. Silakan tambahkan poli terlebih dahulu.');
            return;
        }

        if (empty($pasienUserIds)) {
            $this->command->info('Tidak ada user dengan role "pasien" ditemukan. Silakan buat beberapa user pasien terlebih dahulu.');
            return;
        }

        // Dapatkan tanggal hari ini dalam format YYYY-MM-DD
        $tanggalHariIni = Carbon::today()->format('Y-m-d');

        // Hapus antrean yang sudah ada untuk hari ini saja
        // Ini untuk menghindari duplikasi data antrean setiap kali seeder dijalankan
        AntrianPoliModel::where('tanggal_antrian', $tanggalHariIni)->delete();

        $nomorAntrian = 1; // Mulai nomor antrean dari 1

        // Buat 10 antrean dummy untuk hari ini
        for ($i = 0; $i < 10; $i++) {
            // Pilih poli secara acak dari daftar poli yang tersedia
            $randomPoliId = $poliIds[array_rand($poliIds)];
            // Pilih ID pasien secara acak dari daftar pasien yang tersedia
            $randomPasienId = $pasienUserIds[array_rand($pasienUserIds)];

            AntrianPoliModel::create([
                'iduser' => $randomPasienId,
                'poli_id' => $randomPoliId,
                'nomor_antrian' => $nomorAntrian++, // Increment nomor antrean
                'tanggal_antrian' => $tanggalHariIni,
                // Waktu datang acak antara 10 hingga 60 menit yang lalu dari sekarang
                'waktu_datang' => Carbon::now()->subMinutes(rand(10, 60))->format('H:i:s'),
                'waktu_dipanggil' => null, // Awalnya waktu dipanggil kosong
                'waktu_tunggu' => null, // Awalnya waktu tunggu kosong
                'status_antrian' => 'menunggu', // Status default adalah 'menunggu'
            ]);
        }
        $this->command->info("Berhasil membuat " . ($nomorAntrian - 1) . " antrean dummy untuk hari ini.");
    }
}
