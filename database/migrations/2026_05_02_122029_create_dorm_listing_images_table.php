<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dorm_listing_images', function (Blueprint $table) {
            $table->id();

            // 🔗 link to listing
            $table->foreignId('dorm_listing_id')
                ->constrained('dorm_listings')
                ->onDelete('cascade');

            // 📸 FIXED: unified column name
            $table->string('path');

            // ⭐ cover photo
            $table->boolean('is_cover')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dorm_listing_images');
    }
};