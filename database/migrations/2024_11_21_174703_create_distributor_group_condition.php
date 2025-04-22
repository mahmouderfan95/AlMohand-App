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
        Schema::create('distributor_group_condition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distributor_group_id');
            $table->foreign('distributor_group_id')->references('id')->on('distributor_group')->onDelete('cascade');
            $table->enum('condition_type', ['orders_count', 'orders_value', 'zone', 'account_created_from']);
            $table->enum('prefix', ['greater_than', 'lower_than', 'between']);
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributor_group_condition');
    }
};
