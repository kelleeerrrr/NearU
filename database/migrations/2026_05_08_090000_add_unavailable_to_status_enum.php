<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dorm_listings', function (Blueprint $table) {
            $table->enum('status', ['Available', 'Taken', 'Unavailable'])
                ->default('Available')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('dorm_listings', function (Blueprint $table) {
            $table->enum('status', ['Available', 'Taken'])
                ->default('Available')
                ->change();
        });
    }
};
