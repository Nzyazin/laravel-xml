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
            $table->increments('id'); 
            $table->string('url');
            $table->integer('price');
            $table->integer('old_price');
            $table->string('currencyId');            
            $table->string('picture');
            $table->string('name');
            $table->string('vendor');
            $table->string('category');                        
            $table->string('sub_category');
            $table->string('sub_sub_category');

            $table->integer('product_id');
            $table->foreign('category')->references('category_id')->on('categories')->onDelete('cascade')->onUpdate('cascade');                        
            $table->foreign('sub_category')->references('sub_category_id')->on('subcategories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sub_sub_category')->references('sub_sub_category_id')->on('subsubcategories')->onDelete('cascade')->onUpdate('cascade');
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
