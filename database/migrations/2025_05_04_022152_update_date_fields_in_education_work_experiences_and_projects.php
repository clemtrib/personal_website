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
        // Education table
        Schema::table('education', function (Blueprint $table) {
            // Modifier la colonne 'date' en type date nullable
            $table->date('date')->nullable()->change();
        });

        // Work experiences table
        Schema::table('work_experiences', function (Blueprint $table) {
            // Modifier 'begin_at' et 'end_at' en date nullable
            $table->date('begin_at')->nullable()->change();
            $table->date('end_at')->nullable()->change();
        });

        // Projects table
        Schema::table('projects', function (Blueprint $table) {
            // Modifier 'begin_at' et 'end_at' en date nullable
            $table->date('begin_at')->nullable()->change();
            $table->date('end_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir aux champs integer si besoin

        Schema::table('education', function (Blueprint $table) {
            $table->integer('date')->nullable()->change();
        });

        Schema::table('work_experiences', function (Blueprint $table) {
            $table->integer('begin_at')->nullable()->change();
            $table->integer('end_at')->nullable()->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->integer('begin_at')->nullable()->change();
            $table->integer('end_at')->nullable()->change();
        });
    }
};
