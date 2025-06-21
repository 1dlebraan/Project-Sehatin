<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Jika Anda tidak menggunakan Laravel Sanctum lagi dan hanya memakai JWT,
// baris ini bisa tetap dikomentari atau dihapus.
// use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UsersModel extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    // Jika Anda tidak menggunakan Laravel Sanctum lagi, hapus HasApiTokens dari sini.
    // use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nik',
        'name',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date', // ✅ TAMBAH BARIS INI
    ];

    /**
     * Relasi: Seorang user (petugas) bisa bertugas di banyak Poli.
     */
    public function polis() // Nama relasi biasanya plural (polis)
    {
        return $this->belongsToMany(Poli::class, 'poli_user', 'user_id', 'poli_id');
    }

    // Relasi pendaftaranPasien sudah benar dikomentari karena kolomnya sudah digabung
    // public function pendaftaranPasien()
    // {
    //     return $this->hasOne(PendaftaranPasienModel::class, 'iduser', 'id'); // foreign_key, local_key
    // }

    // --- METODE JWTSubject DIBUTUHKAN OLEH tymon/jwt-auth ---

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * Mengembalikan identifikasi pengguna yang akan disimpan dalam klaim subjek JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Mengembalikan primary key dari model (ID pengguna)
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * Mengembalikan array key-value yang berisi klaim kustom yang akan ditambahkan ke JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // Anda bisa menambahkan klaim kustom seperti role di sini
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }
}
