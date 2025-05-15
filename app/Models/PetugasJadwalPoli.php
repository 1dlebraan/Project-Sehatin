<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetugasJadwalPoli extends Model
{
    // Karena nama tabel tidak sesuai konvensi plural Laravel, kita definisikan manual
    protected $table = 'petugas_jadwal_poli';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'user_id',
        'jadwal_poli_id',
        'nama_lengkap',
        'status',
    ];

    /**
     * Relasi ke User (petugas_jadwal_poli belongsTo user)
     */
    public function user()
    {
        return $this->belongsTo(UsersModel::class);
    }

    /**
     * Relasi ke JadwalPoli (petugas_jadwal_poli belongsTo jadwal poli)
     */
    public function jadwalPoli()
    {
        return $this->belongsTo(JadwalPoli::class);
    }
}
