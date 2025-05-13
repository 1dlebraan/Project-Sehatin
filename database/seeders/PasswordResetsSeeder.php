<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('password_resets')->insert([
            [
                'email' => 'admin@example.com',
                'token' => hash('sha256', Str::random(60)),
                'created_at' => Carbon::now(),
            ],
            [
                'email' => 'petugas@example.com',
                'token' => hash('sha256', Str::random(60)),
                'created_at' => Carbon::now(),
            ],
            [
                'email' => 'pasien@example.com',
                'token' => hash('sha256', Str::random(60)),
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
