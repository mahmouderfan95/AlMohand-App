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
        Schema::table('failed_order_reasons', function (Blueprint $table) {
            $table->foreignId('order_product_id')->nullable()
                ->after('order_id')
                ->constrained('order_products')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('failed_order_reasons', function (Blueprint $table) {
            //
        });
    }
};
