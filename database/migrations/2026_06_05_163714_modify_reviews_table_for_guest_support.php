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
        Schema::table('reviews', function (Blueprint $table) {
            // Drop foreign key first so we can modify the column and unique constraint
            $table->dropForeign(['id_user']);
            $table->dropUnique(['id_user', 'id_objek']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            // Make id_user nullable
            $table->foreignId('id_user')->nullable()->change();
            
            // Add guest name and email fields
            $table->string('nama')->nullable()->after('id_user');
            $table->string('email')->nullable()->after('nama');
        });

        Schema::table('reviews', function (Blueprint $table) {
            // Re-add foreign key with cascade delete
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn(['nama', 'email']);
        });

        // Delete any guest reviews to avoid constraint failure when making id_user NOT NULL
        \DB::table('reviews')->whereNull('id_user')->delete();

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('id_user')->nullable(false)->change();
            $table->unique(['id_user', 'id_objek']);
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
