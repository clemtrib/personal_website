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
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->string('location', 255);
            $table->string('company', 255);
            $table->string('job', 255);
            $table->longText('description')->nullable();
            $table->integer('begin_at')->nullable();
            $table->integer('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('job', 255);
            $table->longText('description')->nullable();
            $table->integer('begin_at')->nullable();
            $table->integer('end_at')->nullable();
            $table->foreignId('work_experience_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('work_experiences');
    }
};
