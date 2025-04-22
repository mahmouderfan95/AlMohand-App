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
        Schema::table('distributor', function (Blueprint $table) {
            //
            $table->foreignId('sales_rep_id')
                ->nullable()
                ->constrained("sales_reps")
                ->cascadeOnDelete()
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributor', function (Blueprint $table) {
            //
            $table->dropForeign('distributor_sales_rep_id_foreign');
            $table->dropColumn('sales_rep_id');
        });
    }
};
