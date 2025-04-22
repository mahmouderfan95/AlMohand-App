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
        Schema::create('pos_terminal_logins', function (Blueprint $table) {
            $table->id();
            $table->uuid('distributor_pos_terminal_id');
            $table->foreign('distributor_pos_terminal_id')->references('id')->on('distributor_pos_terminals')->onDelete('cascade');
            $table->string("app_version")->nullable();
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
        Schema::dropIfExists('pos_terminal_logins');
    }
};
