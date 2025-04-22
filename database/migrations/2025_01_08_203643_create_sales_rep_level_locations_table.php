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
        Schema::create('sales_rep_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_rep_id')
                ->nullable()
                ->constrained("sales_reps")
                ->cascadeOnDelete()
                ->onUpdate('cascade');
            $table->foreignId('city_id')->nullable()->constrained("cities")->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained("regions")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_rep_locations');
    }
};
