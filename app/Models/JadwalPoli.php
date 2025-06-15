<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPoli extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika sesuai konvensi)
    protected $table = 'jadwal_poli';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'poli_id',
        'hari',
        'jam_buka',
        'jam_tutup',
        'kuota',
        'status',
    ];

    // Relasi ke tabel poli
    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }
}
