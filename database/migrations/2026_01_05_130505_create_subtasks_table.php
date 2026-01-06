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
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('title');
        $table->text('description')->nullable();

        $table->decimal('budget', 15, 2)->default(0);

        $table->enum('status', ['not_started', 'in_progress', 'completed'])
            ->default('not_started');

        $table->foreignId('created_by')->constrained('users')
            ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};
