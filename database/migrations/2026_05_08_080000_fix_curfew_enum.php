<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dorm_listings', function (Blueprint $table) {
            $table->enum('curfew', ['No curfew', '10 PM', '11 PM', '12 AM', '12 PM'])
                ->default('No curfew')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('dorm_listings', function (Blueprint $table) {
            $table->enum('curfew', ['No curfew', '10 PM', '11 PM', '12 AM'])
                ->default('No curfew')
                ->change();
        });
    }
};
