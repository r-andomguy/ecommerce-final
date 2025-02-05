<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('parties', function (Blueprint $table) {
            $table->integer('id', true)->unique()->primary()->unsigned();
            $table->timestamps();
            $table->integer('user')->unsigned();
            $table->integer('origin');
            $table->integer('role')->comment('1 - Customer / 2 - Supplier');

            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('parties');
    }
};
