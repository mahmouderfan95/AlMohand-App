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
        Schema::create('pos_terminal_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_id')->unique();
            $table->char('transaction_code')->unique()->nullable();
            $table->char('track_id')->unique();
            $table->uuid('distributor_id')->index();
            $table->foreign('distributor_id')->references('id')->on('distributor')->restrictOnDelete();
            $table->uuid('pos_terminal_id')->index()->nullable();
            $table->foreign('pos_terminal_id')->references('id')->on('pos_terminal')->restrictOnDelete();
            $table->bigInteger('order_id')->nullable();
            $table->decimal('amount');
            $table->decimal('balance_before');
            $table->decimal('balance_after');
            $table->string('currency_code', 3)->default('SAR');
            $table->decimal('exchange_rate', 20, 8)->nullable();
            $table->enum('type', ['credit', 'debit'])->index();
            $table->enum('status', ['draft', 'pending', 'success', 'failed', 'reversed', 'cancelled'])->index()->default('draft');
            $table->enum('payment_method', ['mada', 'balance'])->nullable();
            $table->string('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->bigInteger('auth_id')->nullable();
            $table->json('transaction_object')->nullable();
            $table->string('created_by')->nullable();
            $table->enum('created_by_type', ['admin', 'sales_rep', 'user', 'merchant', 'pos'])->nullable();
            $table->string('updated_by')->nullable();
            $table->enum('updated_by_type', ['admin', 'sales_rep', 'user', 'merchant', 'pos'])->nullable();
            $table->timestamp('transaction_date')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_terminal_transactions');
    }
};
