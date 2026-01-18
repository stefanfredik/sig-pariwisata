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
        Schema::create('fasilitas_wisatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_objek')->constrained('objek_wisatas')->onDelete('cascade');
            $table->string('nama_fasilitas', 50);
            $table->string('deskripsi', 500)->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('icon', 50)->nullable();
            $table->timestamps();
            
            // Index
            $table->index('id_objek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_wisatas');
    }
};
