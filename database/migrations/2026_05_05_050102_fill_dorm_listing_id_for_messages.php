<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('messages')
            ->whereNull('dorm_listing_id')
            ->whereNotNull('listing_id')
            ->update(['dorm_listing_id' => DB::raw('listing_id')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('messages')
            ->whereColumn('dorm_listing_id', 'listing_id')
            ->update(['dorm_listing_id' => null]);
    }
};
