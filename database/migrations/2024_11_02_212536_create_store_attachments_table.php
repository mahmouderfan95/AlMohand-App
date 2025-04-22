<?php

use App\Enums\Distributor\DistributorAttachmentTypeEnum;
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
        Schema::create('store_attachment', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->constrained("store")->cascadeOnDelete();
            $table->text('file_url');
            $table->enum('type', DistributorAttachmentTypeEnum::getList());
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
        Schema::dropIfExists('store_attachment');
    }
};
