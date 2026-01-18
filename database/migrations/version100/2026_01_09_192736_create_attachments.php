<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            // Polymorphic relation (attach to ANY model)
            $table->morphs('attachable');

            $table->string('name');
            $table->string('original_name')->nullable();
            $table->string('path');
            $table->string('thumbnail_path')->nullable();

            $table->string('mime_type', 100);
            $table->string('extension', 20);
            $table->unsignedBigInteger('size');

            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);

            $table->string('uuid');
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['attachable_id', 'attachable_type']);
            $table->index('mime_type');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
