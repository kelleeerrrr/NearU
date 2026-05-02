<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dorm_listings', function (Blueprint $table) {

            $table->integer('walk_minutes')->nullable()->change();
            $table->string('bathroom')->nullable()->change();
            $table->string('furnishings')->nullable()->change();
            $table->string('appliances')->nullable()->change();
            $table->string('bills_included')->nullable()->change();
            $table->string('curfew')->nullable()->change();
            $table->string('gender_policy')->nullable()->change();

        });
    }

    public function down(): void
    {
        Schema::table('dorm_listings', function (Blueprint $table) {

            $table->integer('walk_minutes')->nullable(false)->change();
            $table->string('bathroom')->nullable(false)->change();
            $table->string('furnishings')->nullable(false)->change();
            $table->string('appliances')->nullable(false)->change();
            $table->string('bills_included')->nullable(false)->change();
            $table->string('curfew')->nullable(false)->change();
            $table->string('gender_policy')->nullable(false)->change();

        });
    }
};