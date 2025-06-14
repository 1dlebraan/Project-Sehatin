<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('poli')->insert([
            [
                'kode_poli' => 'GIG002',
                'nama_poli' => 'Poli Gigi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_poli' => 'ANA003',
                'nama_poli' => 'Poli Anak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
