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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_objek')->nullable()->constrained('objek_wisatas')->onDelete('set null');
            $table->string('nama_event', 100);
            $table->string('slug', 120)->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('alamat');
            $table->text('keterangan');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['tanggal_mulai', 'tanggal_selesai']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
