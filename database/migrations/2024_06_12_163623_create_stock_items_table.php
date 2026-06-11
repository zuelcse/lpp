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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('parent')->nullable();
            $table->string('category')->nullable();
            $table->string('taxClassificationName')->nullable();
            $table->string('costingMethod');
            $table->string('valuationMethod');
            $table->string('baseUnits');
            $table->string('additionalUnits'); 
            $table->string('conversion');
            $table->string('openingBalance');
            $table->string('openingValue');
            $table->string('openingRate');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
