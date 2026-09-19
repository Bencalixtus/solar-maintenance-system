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
        Schema::create('inspection_items', function (Blueprint $table) {
            $table->id();

            // The inspection this checklist item belongs to
            $table->foreignId('inspection_id')
                ->constrained('inspections')
                ->cascadeOnDelete();

            // The component being checked
            $table->foreignId('component_id')
                ->constrained('components')
                ->cascadeOnDelete();

            // Example: Surface condition, Cable condition, Leakage
            $table->string('check_item');

            // Example: Pass, Attention, Fail
            $table->string('result')->nullable();

            // Numerical measurement where applicable
            $table->decimal('measurement', 12, 2)->nullable();

            // Example: V, A, °C, Ah, W
            $table->string('unit')->nullable();

            // Condition observed during inspection
            $table->enum('condition', [
                'Excellent',
                'Good',
                'Fair',
                'Poor',
                'Critical'
            ])->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_items');
    }
};