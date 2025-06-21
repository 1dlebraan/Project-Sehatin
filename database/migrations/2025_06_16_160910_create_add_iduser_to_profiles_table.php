<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pendaftaran_pasien', function (Blueprint $table) {
            $table->unsignedBigInteger('iduser')->after('id'); // letakkan setelah kolom 'id'
            $table->foreign('iduser')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_pasien', function (Blueprint $table) {
            $table->dropForeign(['iduser']);
            $table->dropColumn('iduser');
        });
    }
};