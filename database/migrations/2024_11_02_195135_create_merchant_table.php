<?php

use Database\Seeders\MerchantSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('merchant', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->foreignUuid('store_id')->references('id')->on('store')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->timestamp('verified_at')->nullable();
            $table->decimal('balance')->nullable();
            $table->integer('points')->nullable();
            $table->boolean('is_blocked')->nullable();
            $table->boolean('is_owner')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        /*Artisan::call('db:seed' , [
            '--class' => MerchantSeeder::class
        ]);*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant');
    }
};
