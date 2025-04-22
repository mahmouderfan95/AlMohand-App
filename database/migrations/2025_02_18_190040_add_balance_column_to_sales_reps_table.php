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
        Schema::table('sales_reps', function (Blueprint $table) {
            //
            $table->decimal('balance', 15, 4)->default(00.0000);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_reps', function (Blueprint $table) {
            //
            $table->dropColumn('balance');
        });
    }
};
