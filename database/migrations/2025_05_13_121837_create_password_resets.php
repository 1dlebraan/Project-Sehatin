<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();  // Kolom email untuk menghubungkan dengan user
            $table->string('token');  // Kolom token untuk reset password
            $table->timestamp('created_at')->nullable();  // Waktu pembuatan token
            $table->primary(['email', 'token']);  // Kombinasi email dan token sebagai primary key
            });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::dropIfExists('password_resets'); 
}

};
