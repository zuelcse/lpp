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
        if (!Schema::hasTable('item_sub_categories')) {
            Schema::create('item_sub_categories', function (Blueprint $table) {
                $table->id();
                $table->integer('category_id')->index();
                $table->string('name');
                $table->tinyInteger('is_active')->default(1);
                $table->timestamps();

                $table->unique(['category_id', 'name'], 'item_sub_categories_category_name_unique');
            });
        }

        if (Schema::hasTable('stock_items') && !Schema::hasColumn('stock_items', 'sub_category')) {
            Schema::table('stock_items', function (Blueprint $table) {
                $table->integer('sub_category')->nullable()->after('category')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('stock_items') && Schema::hasColumn('stock_items', 'sub_category')) {
            Schema::table('stock_items', function (Blueprint $table) {
                $table->dropIndex(['sub_category']);
                $table->dropColumn('sub_category');
            });
        }

        Schema::dropIfExists('item_sub_categories');
    }
};
