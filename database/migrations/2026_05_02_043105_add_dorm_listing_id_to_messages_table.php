<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDormListingIdToMessagesTable extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('dorm_listing_id')
                ->nullable()
                ->after('receiver_id')
                ->constrained('dorm_listings')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['dorm_listing_id']);
            $table->dropColumn('dorm_listing_id');
        });
    }
}