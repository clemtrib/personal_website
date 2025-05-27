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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->integer('tjm')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->string('hash');
            $table->string('provider_name');
            $table->string('provider_address_line_1');
            $table->string('provider_address_line_2');
            $table->string('provider_zip_code');
            $table->string('provider_city');
            $table->string('provider_province');
            $table->string('provider_country');
            $table->string('customer_name');
            $table->string('customer_address_line_1');
            $table->string('customer_address_line_2');
            $table->string('customer_zip_code');
            $table->string('customer_city');
            $table->string('customer_province');
            $table->string('customer_country');
            $table->decimal('subtotal');
            $table->decimal('tps');
            $table->decimal('tvq');
            $table->decimal('total');
            $table->string('id_tps');
            $table->string('id_tvq');
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });

        Schema::create('bill_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id');
            $table->string('product');
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('total');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('bill_details');
    }
};
