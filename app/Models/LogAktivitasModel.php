<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitasModel extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas'; // Pastikan nama tabel sesuai dengan migrasi Anda

    // Sesuaikan fillable dengan kolom yang sebenarnya ada di tabel log_aktivitas Anda
    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'peran',
        'aktivitas',
        'waktu',
        'timestamp',
    ];

    /**
     * Relasi: Sebuah log aktivitas dimiliki oleh seorang user.
     */
    public function user()
    {
        // Asumsi user_id adalah foreign key ke tabel users
        return $this->belongsTo(UsersModel::class, 'user_id'); // Mengacu ke UsersModel Anda
    }
}
