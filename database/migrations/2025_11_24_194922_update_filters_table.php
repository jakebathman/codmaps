<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('filters', function (Blueprint $table) {
            $table->string('game')->after('id');
            $table->string('name')->after('game');
            $table->boolean('is_active')->default(true)->after('name');

            $table->unique(['game', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('filters', function (Blueprint $table) {
            $table->dropUnique(['game', 'name']);
            $table->dropColumn('game');
            $table->dropColumn('name');
            $table->dropColumn('is_active');
        });
    }
};
