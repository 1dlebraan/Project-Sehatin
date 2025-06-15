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
}
