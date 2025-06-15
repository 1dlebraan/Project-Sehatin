<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika sesuai konvensi)
    protected $table = 'poli';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'kode_poli',
        'nama_poli',
    ];

    // Relasi ke tabel jadwal_poli
    public function jadwal()
    {
        return $this->hasMany(JadwalPoli::class);
    }
}
