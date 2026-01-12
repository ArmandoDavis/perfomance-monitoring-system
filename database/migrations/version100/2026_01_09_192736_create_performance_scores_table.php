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
        Schema::create('performance_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('timeliness_score');
            $table->unsignedTinyInteger('quality_score');
            $table->unsignedTinyInteger('budget_score');
            $table->unsignedTinyInteger('kpi_score');

            $table->decimal('total_score', 5, 2);
            $table->text('remarks')->nullable();

            $table->foreignId('evaluated_by')->constrained('users')->cascadeOnDelete();
            $table->archivedAt();
            $table->string('uuid');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_scores');
    }
};
