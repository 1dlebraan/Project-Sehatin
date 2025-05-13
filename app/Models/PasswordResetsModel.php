<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetsModel extends Model
{
    // Laravel tidak mengharapkan primary key gabungan secara default,
    // jadi kita harus menonaktifkan auto-incrementing ID dan timestamps

    public $incrementing = false;
    public $timestamps = false;

    protected $primaryKey = null;

    // Nama tabel
    protected $table = 'password_resets';

    // Kolom yang bisa diisi
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    // Karena tidak ada ID otomatis, kita bisa menggunakan 'email' dan 'token' sebagai kunci manual jika dibutuhkan
    protected function setKeysForSaveQuery($query)
    {
        return $query->where('email', $this->getAttribute('email'))
                     ->where('token', $this->getAttribute('token'));
    }
}
