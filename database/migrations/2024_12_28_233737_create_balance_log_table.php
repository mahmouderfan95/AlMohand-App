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
        Schema::create('balance_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on('pos_terminal_transactions')->restrictOnDelete();
            $table->uuid('distributor_id')->nullable();
            $table->foreign('distributor_id')->references('id')->on('distributor')->restrictOnDelete();
            $table->uuid('pos_terminal_id')->nullable();
            $table->foreign('pos_terminal_id')->references('id')->on('pos_terminal')->restrictOnDelete();
            $table->enum('balance_type', ['commission', 'points']);
            $table->enum('transaction_type', ['credit', 'debit']);
            $table->decimal('balance_before');
            $table->decimal('balance_after');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_log');
    }
};
