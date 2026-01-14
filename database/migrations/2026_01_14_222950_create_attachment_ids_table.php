<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachment_ids', function (Blueprint $table) {
            $table->id();
            $table->string('base_10')->unique();
            $table->string('base_34')->unique();
            $table->unsignedInteger('k');
            $table->unsignedInteger('n');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachment_ids');
    }
};
