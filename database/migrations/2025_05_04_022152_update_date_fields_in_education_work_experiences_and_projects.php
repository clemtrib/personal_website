<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Education table
        DB::table('education')
            ->whereRaw("LENGTH(date) != 10 OR date NOT LIKE '____-__-__'")
            ->update(['date' => null]);
        Schema::table('education', function (Blueprint $table) {
            // Modifier la colonne 'date' en type date nullable
            $table->date('date')->nullable()->change();
        });

        // Work experiences table
        DB::table('work_experiences')
            ->whereRaw("LENGTH(begin_at) != 10 OR begin_at NOT LIKE '____-__-__'")
            ->update(['begin_at' => null]);
        DB::table('work_experiences')
            ->whereRaw("LENGTH(end_at) != 10 OR end_at NOT LIKE '____-__-__'")
            ->update(['end_at' => null]);
        Schema::table('work_experiences', function (Blueprint $table) {
            // Modifier 'begin_at' et 'end_at' en date nullable
            $table->date('begin_at')->nullable()->change();
            $table->date('end_at')->nullable()->change();
        });

        // Projects table
        DB::table('projects')
            ->whereRaw("LENGTH(begin_at) != 10 OR begin_at NOT LIKE '____-__-__'")
            ->update(['begin_at' => null]);
        DB::table('projects')
            ->whereRaw("LENGTH(end_at) != 10 OR end_at NOT LIKE '____-__-__'")
            ->update(['end_at' => null]);
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
