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
        if (!Schema::hasTable('challans')) {
            Schema::create('challans', function (Blueprint $table) {
                $table->id();
                $table->string('challan_no', 50)->unique();
                $table->date('challan_date')->index();
                $table->integer('sales_id')->nullable()->index();
                $table->integer('ledger_id')->index();
                $table->string('delivery_address', 500)->nullable();
                $table->string('contact_person', 150)->nullable();
                $table->string('contact_phone', 50)->nullable();
                $table->string('driver_name', 150)->nullable();
                $table->string('vehicle_no', 100)->nullable();
                $table->string('transport_name', 150)->nullable();
                $table->string('reference_no', 100)->nullable();
                $table->string('status', 30)->default('Pending')->index();
                $table->tinyInteger('deduct_stock')->default(1);
                $table->text('narration')->nullable();
                $table->integer('user_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('challan_items')) {
            Schema::create('challan_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('challan_id')->index();
                $table->integer('item_id')->nullable()->index();
                $table->string('item_name', 255);
                $table->string('unit_name', 50)->nullable();
                $table->decimal('quantity', 12, 2)->default(0);
                $table->string('note', 255)->nullable();
                $table->timestamps();

                $table->foreign('challan_id')
                    ->references('id')
                    ->on('challans')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challan_items');
        Schema::dropIfExists('challans');
    }
};
