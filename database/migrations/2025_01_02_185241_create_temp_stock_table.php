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
        Schema::create('temp_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()
                ->constrained('orders')->cascadeOnDelete();

            $table->foreignId('product_id')->nullable()
                ->constrained('products')->cascadeOnDelete();

            $table->foreignId('product_serial_id')->nullable()
                ->constrained('product_serials')->cascadeOnDelete();

            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_stock');
    }
};
