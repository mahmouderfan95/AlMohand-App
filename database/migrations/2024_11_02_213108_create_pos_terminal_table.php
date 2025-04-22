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
        Schema::create('pos_terminal', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('brand');
            $table->string('serial_number');
            $table->string('terminal_id');
            $table->decimal('balance')->default(0.0);
            $table->string('software_version')->nullable();
            $table->string('status')->default('running');
            $table->string('device_model')->nullable();
            $table->text('secret_key')->nullable();
            $table->boolean('is_active')->nullable()->default(false);
            $table->timestamp('expire_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_terminal');
    }
};
