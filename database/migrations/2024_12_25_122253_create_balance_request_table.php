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
        Schema::create('balance_request', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on('pos_terminal_transactions')->restrictOnDelete();
            $table->uuid('distributor_id');
            $table->foreign('distributor_id')->references('id')->on('distributor')->restrictOnDelete();
            $table->uuid('pos_terminal_id')->nullable();
            $table->foreign('pos_terminal_id')->references('id')->on('pos_terminal')->restrictOnDelete();
            $table->string('pos_name')->nullable();
            $table->decimal('amount');
            $table->string('status')->nullable()->default('pending');
            $table->string('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_request');
    }
};
