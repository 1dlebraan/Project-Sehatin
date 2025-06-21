<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Komar',
                'email' => 'petugas1@gmail,com',
                'email_verified_at' => now(),
                'password' => Hash::make('petugas1'),
                'role' => 'petugas',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SULISTIA YULIANTI',
                'email' => 'ulsi@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('sy123456'),
                'role' => 'pasien',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CHAIRIL GIBRAN',
                'email' => 'gibran@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('cg123456'),
                'role' => 'pasien',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MUHAMAD ABUG',
                'email' => 'abug@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'pasien',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
