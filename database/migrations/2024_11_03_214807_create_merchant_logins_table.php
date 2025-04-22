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
        Schema::create('merchant_logins', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('merchant_id')->references('id')->on('merchant');
            $table->string("platform")->default('android');
            $table->string("os_version")->nullable();
            $table->string("app_version")->nullable();
            $table->string("mobile_brand")->nullable();
            $table->ipAddress("ip");
            $table->string("location")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_logins');
    }
};
