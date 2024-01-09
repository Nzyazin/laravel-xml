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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('product_id');
            $table->string('url');
            $table->string('price');
            $table->string('old_price');
            $table->string('currencyId');            
            $table->string('picture');            
            $table->string('vendor');

            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('sub_sub_category')->nullable();            
            
            $table->boolean('available');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
