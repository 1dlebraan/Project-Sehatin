<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('poli')->insert([
            'kode_poli'
        ]);
    }
}
