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
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            // Assigned task
            $table->foreignId('task_id')
              ->constrained()
              ->cascadeOnDelete();

        // Assigned staff
            $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

            // HoD or Manager who assigned the task
            $table->foreignId('assigned_by')
              ->constrained('users')
              ->restrictOnDelete();

            $table->date('assigned_at');

            $table->enum('status', [
            'assigned',
            'in_progress',
            'submitted',
            'completed'
            ])->default('assigned'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};
