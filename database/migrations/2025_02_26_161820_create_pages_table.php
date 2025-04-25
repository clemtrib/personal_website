<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages_hero', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255);
            $table->string('subtitle', 255);
            $table->string('title', 255);
            $table->longText('description');
            $table->string('image', 255);
            $table->longText('seo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages_hero');
    }
};
