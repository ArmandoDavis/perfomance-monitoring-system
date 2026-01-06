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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();

            $table->date('start_date');
            $table->date('due_date');

            $table->enum('category', ['major', 'minor']);
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->enum('status', [
            'not_started',
            'in_progress',
            'submitted',
            'completed',
            'overdue'
            ])->default('not_started');

            $table->foreignId('created_by')
              ->constrained('users')
              ->restrictOnDelete();

            $table->foreignId('department_id')
              ->constrained()
              ->restrictOnDelete();
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
