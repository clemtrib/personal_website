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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug', 255);
            $table->string('page_name', 255);
            $table->string('hero_subtitle', 255);
            $table->string('hero_title', 255);
            $table->longText('hero_description');
            $table->string('hero_image', 255)->nullable();
            $table->longText('content_text')->nullable();
            $table->string('content_image', 255)->nullable();
            $table->longText('page_seo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
