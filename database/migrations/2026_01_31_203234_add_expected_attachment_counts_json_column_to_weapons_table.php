<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->json('expected_attachment_counts')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->dropColumn('expected_attachment_counts');
        });
    }
};
