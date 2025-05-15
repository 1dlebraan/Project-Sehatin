<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoliModel extends Model
{
    // Laravel secara default akan mengasumsikan nama tabel adalah jamak ("polis"),
    // karena kamu menamainya "poli" (bentuk tidak jamak), kita perlu mendefinisikannya:
    protected $table = 'poli';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'kode_poli',
        'nama_poli',
    ];
}
