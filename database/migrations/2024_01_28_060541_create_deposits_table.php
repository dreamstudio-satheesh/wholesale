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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
			$table->integer('account_id')->index('deposit_account_id');
			$table->integer('deposit_category_id')->index('deposit_category_id');
			$table->decimal('amount', 10);
			$table->integer('payment_method_id')->index('deposit_payment_method_id');
			$table->dateTime('date');
			$table->string('deposit_ref', 192);
			$table->text('description')->nullable();
			$table->string('attachment', 192)->nullable();
			$table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
