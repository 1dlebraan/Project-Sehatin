<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    // Karena nama tabel tidak sesuai konvensi jamak Laravel
    protected $table = 'log_aktivitas';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'peran',
        'aktivitas',
        'waktu',
        'timestamp',
    ];

    /**
     * Relasi ke user (log_aktivitas belongsTo user)
     */
    public function user()
    {
        return $this->belongsTo(UsersModel::class);
    }
}
