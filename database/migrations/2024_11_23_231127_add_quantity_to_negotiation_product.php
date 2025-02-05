<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('negotiation_products', function (Blueprint $table) {
            $table->string('quantity')->nullable()->after('product');
            $table->string('total')->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('negotiation_products', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('total');
        });
    }
};
