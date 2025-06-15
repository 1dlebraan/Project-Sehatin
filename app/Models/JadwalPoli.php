<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPoli extends Model
{
    use HasFactory;

    protected $table = 'jadwal_poli'; // Pastikan nama tabel benar jika tidak plural 'jadwal_polis'

    // Kolom-kolom yang boleh diisi secara massal
    protected $fillable = [
        'poli_id',
        'hari',
        'jam_buka',
        'jam_tutup',
        'kuota',
        'status', // Tambahkan 'status' jika Anda juga ingin bisa mengupdate status via update method
    ];

    // Jika Anda menggunakan $guarded, pastikan kolom yang ingin diupdate TIDAK ada di dalamnya
    // protected $guarded = []; // Jika kosong, semua kolom boleh diisi massal

    /**
     * Get the poli that owns the JadwalPoli.
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }
}
