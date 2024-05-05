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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('sku')->unique(); 
        $table->text('description')->nullable(); 
        $table->decimal('price', 8, 2)->nullable(); 
        $table->decimal('cost', 8, 2)->nullable(); 
        $table->string('product_type', 50);
        $table->string('tax_method', 10)->nullable()->default('exclusive');
        $table->unsignedBigInteger('unit_id')->nullable();
        $table->unsignedBigInteger('unit_sale_id')->nullable();
        $table->unsignedBigInteger('unit_purchase_id')->nullable();
        $table->decimal('tax', 5, 2)->nullable()->default(0.00);
        $table->unsignedBigInteger('category_id')->nullable();
        $table->unsignedBigInteger('supplier_id')->nullable();            
        $table->unsignedBigInteger('brand_id')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();
        $table->integer('minimum_sale_quantity')->default(1);
        $table->integer('stock_alert')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes();

        /* $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('set null');
        $table->foreign('supplier_id')
            ->references('id')->on('suppliers')
            ->onDelete('set null');
        $table->foreign('brand_id')
            ->references('id')->on('brands')
            ->onDelete('set null');
        $table->foreign('unit_id')
            ->references('id')->on('units')
            ->onDelete('set null'); */
    });
}


    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
