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
        Schema::create('sales_reps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained("sales_reps")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained("cities")->nullOnDelete();
            $table->foreignId('sales_rep_level_id')->nullable()->constrained("sales_rep_levels")->nullOnDelete();
            $table->string('name');
            $table->string('username');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('code');
            $table->enum('status', ['active','inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_reps');
    }
};
