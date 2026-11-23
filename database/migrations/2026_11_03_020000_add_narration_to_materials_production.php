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
        Schema::table('materials_production', function (Blueprint $table) {
            if (!Schema::hasColumn('materials_production', 'narration')) {
                $table->text('narration')->nullable()->after('date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials_production', function (Blueprint $table) {
            if (Schema::hasColumn('materials_production', 'narration')) {
                $table->dropColumn('narration');
            }
        });
    }
};
