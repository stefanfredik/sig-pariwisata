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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_objek')->constrained('objek_wisatas')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned();
            $table->string('judul', 100)->nullable();
            $table->text('komentar');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->integer('helpful_count')->default(0);
            $table->timestamps();
            
            // Unique constraint: one review per user per objek
            $table->unique(['id_user', 'id_objek']);
            
            // Indexes
            $table->index('status');
            $table->index('id_objek');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
