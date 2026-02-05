<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->boolean('prestige')->default(false)->after('weapon_unlock');
            $table->text('notes')->nullable()->after('prestige');
        });
    }

    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropColumn('prestige');
        });
    }
};
