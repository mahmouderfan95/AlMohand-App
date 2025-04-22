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
        Schema::create('sales_rep_levels', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['active','inactive'])->default('active');
            $table->string('code');
            $table->foreignId('parent_id')->nullable()->constrained("sales_rep_levels")->cascadeOnUpdate()->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_rep_levels');
    }
};
