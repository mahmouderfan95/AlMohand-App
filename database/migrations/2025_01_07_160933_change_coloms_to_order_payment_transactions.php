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
        Schema::table('order_payment_transactions', function (Blueprint $table) {
            $table->string('payment_id')->nullable()->change();
            $table->string('reference_number')->nullable()->change();
            $table->string('payment_type')->nullable()->change();
            $table->decimal('amount', 10, 6)->nullable()->change();
            $table->longText('full_response')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_payment_transactions', function (Blueprint $table) {
            $table->dropColumn('full_response');
        });
    }
};
