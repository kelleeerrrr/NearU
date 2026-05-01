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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // 🔗 who receives the notification
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // 📌 notification content
            $table->string('title');
            $table->text('message')->nullable();

            // 🏷️ type: message, visit, listing, system, etc.
            $table->string('type')->nullable();

            // 👁️ read/unread status
            $table->boolean('is_read')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};