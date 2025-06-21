<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntrianPoliModel extends Model
{
    use HasFactory;
    protected $table = 'antrian_poli'; // Pastikan nama tabel sesuai

    protected $fillable = [
        'iduser', // Sesuaikan dengan nama kolom foreign key di migrasi Anda
        'poli_id',
        'nomor_antrian',
        'tanggal_antrian',
        'waktu_datang',
        'waktu_dipanggil',
        'waktu_selesai',
        'lama_layanan',
        'waktu_tunggu',
        'status_antrian',
    ];

    /**
     * Relasi: Sebuah antrian dimiliki oleh seorang user (pasien).
     * Kolom foreign key di tabel 'antrian_poli' adalah 'iduser'.
     */
    public function pasien()
    {
        // ✅ UBAH INI: Pastikan menunjuk ke UsersModel Anda
        return $this->belongsTo(UsersModel::class, 'iduser');
    }
    /**
     * Relasi: Sebuah antrian dimiliki oleh satu Poli.
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'poli_id');
    }
}
