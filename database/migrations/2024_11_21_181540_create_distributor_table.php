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
        Schema::create('distributor', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('distributor_group_id')->constrained('distributor_group')->onDelete('restrict');
            $table->foreignId('zone_id')->constrained('zone')->onDelete('restrict');
            $table->foreignId('city_id')->constrained('cities')->onDelete('restrict');
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('restrict');
            $table->string('code');
            $table->string('logo')->nullable();
            $table->string('manager_name');
            $table->string('owner_name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('location')->nullable()->comment('comma seperated lat&long');
            $table->string('commercial_register');
            $table->string('tax_card');
            $table->integer('points')->default(0);
            $table->decimal('balance')->default(0);
            $table->decimal('commission_balance')->default(0);
            $table->integer('pos_terminals_count')->default(0);
            $table->boolean('is_active')->nullable()->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributor');
    }
};
