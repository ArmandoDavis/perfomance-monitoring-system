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
        Schema::create('task_expenses', function (Blueprint $table) {
            $table->id();
             $table->foreignId('task_id')
              ->constrained()
              ->cascadeOnDelete();
            $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

            $table->decimal('amount', 15, 2);

            $table->text('description');

            $table->string('receipt_path')->nullable();
            $table->date('spent_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_expenses');
    }
};
