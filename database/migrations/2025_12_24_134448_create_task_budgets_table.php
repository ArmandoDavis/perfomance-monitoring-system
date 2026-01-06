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
        Schema::create('task_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')
              ->constrained()
              ->cascadeOnDelete();

        // Null = task-level budget
            $table->foreignId('user_id')
              ->nullable()
              ->constrained()
              ->nullOnDelete();

            $table->decimal('allocated_amount', 15, 2);
            

             // HoD who created the budget
            $table->foreignId('created_by')
              ->constrained('users')
              ->restrictOnDelete();

            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_budgets');
    }
};
