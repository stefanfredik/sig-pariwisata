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
        Schema::create('fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fotoable_id');
            $table->string('fotoable_type', 50);
            $table->string('path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            // Index for polymorphic relationship
            $table->index(['fotoable_id', 'fotoable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotos');
    }
};
