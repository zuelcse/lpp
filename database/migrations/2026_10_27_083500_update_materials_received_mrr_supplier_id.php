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
        if (Schema::hasTable('materials_received_mrr')) {
            Schema::table('materials_received_mrr', function (Blueprint $table) {
                if (!Schema::hasColumn('materials_received_mrr', 'supplier_id')) {
                    $table->integer('supplier_id')->nullable()->after('voucher_no');
                    $table->foreign('supplier_id', 'fk_mrr_supplier')->references('id')->on('ledgers')->onDelete('restrict');
                }
                if (Schema::hasColumn('materials_received_mrr', 'supplier_name')) {
                    $table->dropColumn('supplier_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('materials_received_mrr')) {
            Schema::table('materials_received_mrr', function (Blueprint $table) {
                if (Schema::hasColumn('materials_received_mrr', 'supplier_id')) {
                    $table->dropForeign('fk_mrr_supplier');
                    $table->dropColumn('supplier_id');
                }
                if (!Schema::hasColumn('materials_received_mrr', 'supplier_name')) {
                    $table->string('supplier_name', 200)->nullable()->after('voucher_no');
                }
            });
        }
    }
};
