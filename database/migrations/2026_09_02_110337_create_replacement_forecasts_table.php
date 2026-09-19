<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('replacement_forecasts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('component_id')
                ->constrained('components')
                ->cascadeOnDelete();

            $table->date('forecast_date');

            $table->decimal('current_age', 6, 2)->nullable();

            $table->enum('current_condition', [
                'Excellent',
                'Good',
                'Fair',
                'Poor',
                'Critical'
            ])->nullable();

            $table->decimal('degradation_rate', 8, 2)->nullable();

            $table->decimal('estimated_remaining_life', 8, 2)->nullable();

            $table->decimal('estimated_replacement_cost', 12, 2)->nullable();

            $table->enum('risk_level', [
                'Low',
                'Medium',
                'High'
            ])->default('Low');

            $table->string('recommended_action');

            $table->text('forecast_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('replacement_forecasts');
    }
};