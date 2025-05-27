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
        Schema::create('gusers', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable()->unique();
            $table->string('google_name')->nullable();
            $table->string('google_email')->nullable();
            $table->text('google_picture')->nullable();
            $table->text('google_access_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->timestamp('google_token_expires_at')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gusers');
    }
};
