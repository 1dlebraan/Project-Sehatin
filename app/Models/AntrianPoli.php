<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntrianPoli extends Model
{
    // Karena nama tabel bukan bentuk jamak dari nama model, perlu ditentukan
    protected $table = 'antrian_poli';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'pendaftaran_pasien_id',
        'poli_id',
        'nomor_antrian',
        'tanggal_antrian',
        'waktu_datang',
        'waktu_dipanggil',
        'waktu_tunggu',
        'status_antrian',
    ];

    /**
     * Relasi ke pendaftaran pasien (antrian belongsTo pendaftaran)
     */
    public function pendaftaranPasien()
    {
        return $this->belongsTo(PendaftaranPasien::class);
    }

    /**
     * Relasi ke poli (antrian belongsTo poli)
     */
    public function poli()
    {
        return $this->belongsTo(PoliModel::class);
    }
}
