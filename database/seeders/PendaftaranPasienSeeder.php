<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UsersModel; // Pastikan ini mengarah ke model User Anda
use App\Models\PendaftaranPasienModel; // Pastikan ini mengarah ke model PendaftaranPasien Anda
use Carbon\Carbon; // Untuk tanggal lahir

class PendaftaranPasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil user yang memiliki role 'pasien'
        $pasienUsers = UsersModel::where('role', 'pasien')->get();

        if ($pasienUsers->isEmpty()) {
            $this->command->info('Tidak ada user dengan role "pasien" ditemukan. Silakan buat beberapa user pasien terlebih dahulu.');
            return;
        }

        foreach ($pasienUsers as $user) {
            // Cek apakah user ini sudah memiliki entri pendaftaran_pasien
            if (!PendaftaranPasienModel::where('iduser', $user->id)->exists()) {
                PendaftaranPasienModel::create([
                    'iduser' => $user->id, // Hubungkan dengan ID user yang bersangkutan
                    'nik' => '12345678901234' . $user->id, // NIK dummy unik
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => Carbon::parse('1990-01-01')->addDays($user->id)->format('Y-m-d'), // Tanggal lahir dummy
                    'email' => $user->email, // Email dari UsersModel
                    'no_hp' => '0812345678' . $user->id, // No HP dummy
                    'jenis_kelamin' => ($user->id % 2 == 0) ? 'Laki - Laki' : 'Perempuan', // Jenis kelamin dummy
                    'alamat' => 'Jl. Contoh Alamat No. ' . $user->id . ', Kota Dummy',
                ]);
                $this->command->info("Pendaftaran pasien untuk user '{$user->name}' berhasil dibuat.");
            } else {
                $this->command->info("User '{$user->name}' sudah memiliki entri pendaftaran pasien, dilewati.");
            }
        }
    }
}
