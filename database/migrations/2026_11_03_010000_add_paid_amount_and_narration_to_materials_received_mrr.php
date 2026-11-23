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
        Schema::table('materials_received_mrr', function (Blueprint $table) {
            if (!Schema::hasColumn('materials_received_mrr', 'paid_amount')) {
                $table->decimal('paid_amount', 15, 2)->default(0)->after('date');
            }
            if (!Schema::hasColumn('materials_received_mrr', 'narration')) {
                $table->text('narration')->nullable()->after('paid_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials_received_mrr', function (Blueprint $table) {
            if (Schema::hasColumn('materials_received_mrr', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
            if (Schema::hasColumn('materials_received_mrr', 'narration')) {
                $table->dropColumn('narration');
            }
        });
    }
};
