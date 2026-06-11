<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // master
        Schema::create('basic_colors', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn');
        });

        Schema::create('basic_laminations', function ($table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('sizes', function ($table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('papers', function ($table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('work_types', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn');
        });

        // pivot
        Schema::create('work_type_color', function ($table) {
            $table->foreignId('work_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('color_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('work_type_size', function ($table) {
            $table->foreignId('work_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('size_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('work_type_paper', function ($table) {
            $table->foreignId('work_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('paper_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printing_tables');
    }
};
