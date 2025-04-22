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
        Schema::create('distributor_group', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('is_auto_assign')->nullable()->default(true);
            $table->boolean('is_require_all_conditions')->nullable()->default(true)->comment('All condition should be existed in the distributor to be applied');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributor_group');
    }
};
