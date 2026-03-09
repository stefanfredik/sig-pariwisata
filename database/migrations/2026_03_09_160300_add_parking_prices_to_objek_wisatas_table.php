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
        Schema::table('objek_wisatas', function (Blueprint $col) {
            $col->bigInteger('harga_parkir_motor')->nullable()->after('harga_tiket_asing');
            $col->bigInteger('harga_parkir_mobil')->nullable()->after('harga_parkir_motor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objek_wisatas', function (Blueprint $col) {
            $col->dropColumn(['harga_parkir_motor', 'harga_parkir_mobil']);
        });
    }
};
