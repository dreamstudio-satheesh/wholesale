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
        Schema::create('payment_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id')->index(); 
            $table->unsignedBigInteger('user_id')->index(); 
            $table->float('amount', 10, 0);
            $table->unsignedBigInteger('payment_method_id');
            $table->integer('account_id')->nullable()->index('account_id_payment_purchases');
            $table->dateTime('date')->nullable();
            $table->float('change', 10, 0)->default(0);  
            $table->string('payment_notes', 192)->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_purchases');
    }
};
