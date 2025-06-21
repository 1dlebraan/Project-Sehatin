<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;


    protected $table = 'poli';


    protected $fillable = [
        'kode_poli',
        'nama_poli',
    ];


    public function jadwal()
    {
        return $this->hasMany(JadwalPoli::class);
    }

    //    Relasi table User
    public function users() // Nama relasi biasanya plural (users)
    {
        return $this->belongsToMany(UsersModel::class, 'poli_user', 'user_id', 'poli_id');
    }
}
