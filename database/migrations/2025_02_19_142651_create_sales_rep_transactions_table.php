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
        Schema::create('sales_rep_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('balance_type');
            $table->enum('transaction_type', ['credit', 'debit']);
            $table->decimal('balance_before');
            $table->decimal('balance_after');
            $table->foreignId('sales_rep_id')
                ->nullable()
                ->constrained("sales_reps")
                ->cascadeOnDelete()
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_rep_transactions');
    }
};
