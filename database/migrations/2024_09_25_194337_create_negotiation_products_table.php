<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('negotiation_products', function (Blueprint $table) {
            $table->integer('id', true)->unique()->primary()->unsigned();
            $table->timestamps();
            $table->integer('negotiation')->unsigned();
            $table->integer('product')->unsigned();


            $table->foreign('negotiation')->references('id')->on('negotiations')->onDelete('cascade');
            $table->foreign('product')->references('id')->on('products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('negotiation_products');
    }
};
