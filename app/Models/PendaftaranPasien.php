<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPasien extends Model
{
    // Laravel akan menganggap nama tabelnya "pendaftaran_pasiens", jadi kita override
    protected $table = 'pendaftaran_pasien';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'nik',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'email',
        'no_hp',
        'jenis_kelamin',
        'alamat',
    ];
}
