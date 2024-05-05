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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('supplier_id')->index('purchases_return_supplier');
            $table->integer('warehouse_id')->nullable()->default(0);
            $table->dateTime('date');
            $table->string('return_invoice_number')->unique();
            $table->float('tax_rate', 10, 0)->nullable()->default(0);
            $table->float('tax_amount', 10, 0)->nullable()->default(0);
            $table->float('discount', 10, 0)->nullable()->default(0);
            $table->string('discount_type', 192);
            $table->float('grand_total', 10, 0)->default(0);
			$table->float('paid_amount', 10, 0)->default(0);
            $table->string('payment_status', 192);
            $table->string('status', 191);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
