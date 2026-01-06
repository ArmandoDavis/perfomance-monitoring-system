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
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_assignment_id')
              ->constrained()
              ->cascadeOnDelete();

        // Staff being evaluated
            $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

        // HoD / Manager who evaluated
            $table->foreignId('evaluated_by')
              ->constrained('users')
              ->restrictOnDelete();

            $table->integer('score'); // e.g. 0 – 100
            $table->text('remarks')->nullable();

            $table->date('evaluation_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
