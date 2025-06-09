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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('company')->nullable();
            $table->decimal('tjm', 8, 2)->nullable()->change();
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->string('provider_phone')->nullable();
            $table->string('provider_mail')->nullable();
            $table->string('provider_website')->nullable();
            $table->string('provider_logo')->nullable();
            $table->string('customer_company')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('company');
            $table->string('tjm')->nullable()->change();
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('provider_phone');
            $table->dropColumn('provider_mail');
            $table->dropColumn('provider_website');
            $table->dropColumn('provider_logo');
            $table->dropColumn('customer_company');
        });
    }
};
