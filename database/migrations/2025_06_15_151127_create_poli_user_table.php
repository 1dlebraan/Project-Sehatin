<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('poli_user', function (Blueprint $table) {
            // Kolom foreign key untuk tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Kolom foreign key untuk tabel poli
            $table->foreignId('poli_id')->constrained('poli')->onDelete('cascade');

            // Menjadikan kombinasi user_id dan poli_id sebagai primary key
            // Ini juga mencegah duplikasi asosiasi
            $table->primary(['user_id', 'poli_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poli_user');
    }
};
