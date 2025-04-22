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
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn('unit_price');
            $table->double('cost_price')->default(0.00000)->after('quantity');
            $table->double('wholesale_price')->default(0.00000)->after('cost_price');
            $table->double('price')->default(0.00000)->after('wholesale_price');
            $table->double('profit')->default(0.00000)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'wholesale_price', 'price', 'profit']);
        });
    }
};
