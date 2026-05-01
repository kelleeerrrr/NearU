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
        Schema::create('dorm_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('street');
            $table->enum('type', ['Room', 'Bedspace', 'Unit']);
            $table->decimal('price', 8, 2);
            $table->enum('gender_policy', ['Any', 'Female', 'Male'])->default('Any');
            $table->integer('walk_minutes');
            $table->enum('bathroom', ['Private', 'Shared']);
            $table->text('furnishings')->nullable();
            $table->text('appliances')->nullable();
            $table->text('bills_included')->nullable();
            $table->enum('curfew', ['No curfew', '10 PM', '11 PM', '12 AM'])->default('No curfew');
            $table->boolean('wifi_included')->default(false);
            $table->boolean('pets_allowed')->default(false);
            $table->text('nearby_landmarks')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->json('photos')->nullable(); // Array of photo URLs
            $table->enum('status', ['Available', 'Taken'])->default('Available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dorm_listings');
    }
};
