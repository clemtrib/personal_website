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
        DB::table('messages')
            ->whereRaw("LENGTH(created_at) != 10 OR created_at NOT LIKE '____-__-__'")
            ->update(['created_at' => null]);
        DB::table('messages')
            ->whereRaw("LENGTH(updated_at) != 10 OR updated_at NOT LIKE '____-__-__'")
            ->update(['updated_at' => null]);
        Schema::table('messages', function (Blueprint $table) {
            // Modifier la colonne 'date' en type date nullable
            $table->date('created_at')->nullable()->change();
            $table->date('updated_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->integer('created_at')->nullable()->change();
            $table->integer('updated_at')->nullable()->change();
        });
    }
};
