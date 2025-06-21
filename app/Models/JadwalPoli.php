<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPoli extends Model
{
    use HasFactory;

    protected $table = 'jadwal_poli'; // Pastikan nama tabel benar

    // Pastikan SEMUA kolom yang ingin Anda perbarui ada dalam array $fillable ini.
    protected $fillable = [
        'poli_id',
        'hari', // Ini harus ada
        'jam_buka', // Ini harus ada
        'jam_tutup', // Ini harus ada
        'kuota', // Ini harus ada
        'status', // Ini juga harus ada jika status bisa diupdate via form/mass assignment
    ];

    /**
     * Relasi dengan model Poli
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }
}
