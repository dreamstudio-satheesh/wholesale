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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
			$table->integer('account_id')->index('expenses_account_id');
			$table->integer('expense_category_id')->index('expenses_category_id');
			$table->decimal('amount', 10);
			$table->integer('payment_method_id')->index('expenses_payment_method_id');
			$table->dateTime('date');
			$table->string('expense_ref', 192);
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
        Schema::dropIfExists('expenses');
    }
};
