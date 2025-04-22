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
        Schema::table('code_verifications', function (Blueprint $table) {
            $table->uuid('verifiable_uuid')->after('verifiable_id')->nullable();
            $table->unsignedBigInteger('verifiable_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('code_verifications', function (Blueprint $table) {
            $table->unsignedBigInteger('verifiable_id')->change();
        });
    }
};
