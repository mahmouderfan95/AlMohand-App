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
        Schema::create('distributor_pos_terminals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('distributor_id');
            $table->foreign('distributor_id')->references('id')->on('distributor')->onDelete('cascade');
            $table->uuid('pos_terminal_id');
            $table->foreign('pos_terminal_id')->references('id')->on('pos_terminal')->onDelete('cascade');
            $table->string('serial_number');
            $table->string('otp');
            $table->decimal('balance')->default(0.0);
            $table->integer('points')->nullable()->default(0);
            $table->string('branch_name')->nullable();
            $table->string('address')->nullable();
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('password')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('reset_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_blocked')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributor_pos_terminals');
    }
};
