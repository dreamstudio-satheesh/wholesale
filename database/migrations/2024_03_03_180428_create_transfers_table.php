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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('user_id_transfers');
            $table->dateTime('date');

            // Define the columns before setting them as foreign keys
            $table->unsignedBigInteger('from_warehouse_id'); // Define this column before its foreign key constraint
            $table->unsignedBigInteger('to_warehouse_id'); // Define this column before its foreign key constraint

            // Now set the foreign keys
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');

            $table->float('tax_rate', 10, 0)->nullable()->default(0);
            $table->float('tax_amount', 10, 0)->nullable()->default(0);
            $table->float('discount', 10, 0)->nullable()->default(0);
            $table->string('discount_type', 192);
            $table->float('grand_total', 10, 0)->default(0);
            $table->float('paid_amount', 10, 0)->default(0);
            $table->float('shipping_amount', 10, 0)->nullable()->default(0);
            $table->string('payment_status', 192);
            $table->string('status', 191);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
