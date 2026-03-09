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
        Schema::table('objek_wisatas', function (Blueprint $table) {
            $table->bigInteger('harga_tiket_lokal')->nullable()->after('harga_tiket');
            $table->bigInteger('harga_tiket_domestik')->nullable()->after('harga_tiket_lokal');
            $table->bigInteger('harga_tiket_asing')->nullable()->after('harga_tiket_domestik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objek_wisatas', function (Blueprint $table) {
            $table->dropColumn(['harga_tiket_lokal', 'harga_tiket_domestik', 'harga_tiket_asing']);
        });
    }
};
