<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attachment_ids', function (Blueprint $table) {
            $table->mediumText('binary')->after('base_34')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('attachment_ids', function (Blueprint $table) {
            $table->dropColumn('binary');
        });
    }
};
