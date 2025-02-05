<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('negotiations', function (Blueprint $table) {
            $table->integer('id', true)->unique()->primary()->unsigned();
            $table->timestamps();
            $table->integer('status')->default(1)->comment('1 - PENDING / 2 - CANCEL / 3 - SOLD');
            $table->integer('customer')->unsigned();
            $table->integer('supplier')->unsigned();
            $table->decimal('discount')->unsigned()->nullable();
            $table->integer('payment_method')->unsigned()->comment('1 - PIX / 2 - CASH / 3 - CREDIT CARD / 4 - DEBIT');
            $table->text('shipping_address');
            $table->decimal('total')->unsigned();

            $table->foreign('customer')->references('id')->on('parties')->onDelete('cascade');
            $table->foreign('supplier')->references('id')->on('parties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('negotiations');
    }
};
