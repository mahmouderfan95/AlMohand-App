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
        Schema::create('vendor_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained("vendors")->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('product_id')->constrained("products")->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('brand_id')->constrained("brands")->restrictOnDelete()->restrictOnUpdate();
            $table->bigInteger('vendor_product_id');
            $table->timestamps();

            $table->unique(['vendor_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_products');
    }
};
