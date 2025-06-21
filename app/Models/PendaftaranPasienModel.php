<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPasienModel extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_pasien';

    // Sesuaikan fillable dengan semua kolom yang ada di migrasi pendaftaran_pasien Anda
    protected $fillable = [
        'iduser', // ✅ Pastikan ini ada di sini dan cocok dengan kolom di database
        'nik',
        'nama', // Ini nama lengkap pasien
        'tempat_lahir',
        'tanggal_lahir',
        'email', // Email ini bisa berbeda dari email login di UsersModel
        'no_hp',
        'jenis_kelamin',
        'alamat',
    ];

    /**
     * Relasi: Sebuah detail pendaftaran pasien dimiliki oleh satu user.
     * Menggunakan 'iduser' sebagai foreign key.
     */
    public function user()
    {
        return $this->belongsTo(UsersModel::class, 'iduser', 'id'); // foreign_key, owner_key
    }


}
