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
        Schema::create('objek_wisatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kecamatan')->constrained('kecamatans')->onDelete('cascade');
            $table->string('nama_objek', 100);
            $table->string('slug', 120)->unique();
            $table->string('alamat');
            $table->string('no_telepon', 15)->nullable();
            $table->text('keterangan');
            $table->string('jam_operasional', 100)->nullable();
            $table->string('harga_tiket', 100)->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('id_kecamatan');
            $table->index('slug');
            $table->index('rating_avg');
            
            // Fulltext index for search
            $table->fullText(['nama_objek', 'alamat', 'keterangan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objek_wisatas');
    }
};
