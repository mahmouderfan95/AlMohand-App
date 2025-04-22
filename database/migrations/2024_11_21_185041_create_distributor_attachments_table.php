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
        Schema::create('distributor_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('distributor_id');
            $table->foreign('distributor_id')->references('id')->on('distributor')->onDelete('cascade');
            $table->text('file_url');
            $table->enum('type', ['identity_files','commercial_register_files','tax_card_files','more_files']);
            $table->string('extension')->nullable();
            $table->string('size')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributor_attachments');
    }
};
