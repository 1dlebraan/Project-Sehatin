<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPoli extends Model
{
    // Laravel akan menganggap tabelnya "jadwal_polis", jadi kita definisikan manual
    protected $table = 'jadwal_poli';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'poli_id',
        'hari',
        'jam_buka',
        'jam_tutup',
        'kuota',
        'status',
    ];

    /**
     * Relasi ke model Poli (jadwal_poli belongsTo poli)
     */
    public function poli()
    {
        return $this->belongsTo(PoliModel::class);
    }
}
