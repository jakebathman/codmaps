<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('filters', function (Blueprint $table) {
            $table->unsignedInteger('game_id')->after('id');
            $table->string('name')->after('game_id');
            $table->boolean('is_active')->default(true)->after('name');

            $table->unique(['game_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('filters', function (Blueprint $table) {
            $table->dropUnique(['game_id', 'name']);
            $table->dropColumn('game_id');
            $table->dropColumn('name');
            $table->dropColumn('is_active');
        });
    }
};
