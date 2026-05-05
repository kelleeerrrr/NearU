<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {

            // prevent duplicate column error
            if (!Schema::hasColumn('messages', 'conversation_id')) {

                $table->foreignId('conversation_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('conversations')
                    ->onDelete('cascade');
            }

        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {

            if (Schema::hasColumn('messages', 'conversation_id')) {
                $table->dropForeign(['conversation_id']);
                $table->dropColumn('conversation_id');
            }

        });
    }
};