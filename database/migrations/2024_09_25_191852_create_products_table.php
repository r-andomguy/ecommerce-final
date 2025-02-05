<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id', true)->unique()->primary()->unsigned();
            $table->timestamps();
            $table->string('name');
            $table->integer('supplier')->unsigned();
            $table->integer('stock');
            $table->decimal('price');
            $table->integer('category')->unsigned();


            $table->foreign('supplier')->references('id')->on('parties')->onDelete('cascade');
            $table->foreign('category')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
