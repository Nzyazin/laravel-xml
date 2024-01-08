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
        Schema::create('subsubcategories', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_sub_category_id')->primary();
            $table->string('name');
            $table->integer('parent_id');
            $table->timestamps();

            $table->foreign('sub_sub_category_id')->references('sub_category_id')->on('subcategories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsubcategories');
    }
};
