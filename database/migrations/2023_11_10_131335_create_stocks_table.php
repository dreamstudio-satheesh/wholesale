<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->default(1)->nullable();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->dateTime('date');
            $table->integer('quantity');
            $table->enum('type', ['Addition', 'Subtraction']);
            $table->enum('movement_reason', ['Sale', 'Purchase', 'Sales Return', 'Purchase Return', 'Stock Adjustment_Addition', 'Stock Adjustment_Subtraction', 'Transfer Out', 'Transfer In', 'Write-Off', 'Reconciliation']);
            $table->unsignedBigInteger('related_order_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
