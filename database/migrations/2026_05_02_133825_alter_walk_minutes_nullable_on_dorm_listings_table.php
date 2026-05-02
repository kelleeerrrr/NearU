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
        Schema::table('dorm_listings', function (Blueprint $table) {

            // ✅ FIX: allow NULL to prevent insert crashes
            $table->integer('walk_minutes')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dorm_listings', function (Blueprint $table) {

            // revert back to NOT NULL (optional rollback)
            $table->integer('walk_minutes')->nullable(false)->change();
        });
    }
};