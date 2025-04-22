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
        Schema::create('product_price_distributor_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained("products")->cascadeOnDelete();
            $table->foreignId('distributor_group_id')->nullable()->constrained("distributor_group")->cascadeOnDelete();
            $table->decimal('price',15,4)->default('0.0000');
            $table->decimal('amount_percentage',15,4)->default('0.0000');
            $table->integer('minimum_quantity')->default('0');
            $table->integer('max_quantity')->default('0');
            $table->integer('points_buy')->default('0');
            $table->integer('points_sell')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_distributor_groups');
    }
};
