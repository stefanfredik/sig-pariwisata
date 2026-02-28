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
        Schema::table('objek_wisatas', function (Blueprint $table) {
            $table->json('akses_transportasi')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objek_wisatas', function (Blueprint $table) {
            $table->dropColumn('akses_transportasi');
        });
    }
};
