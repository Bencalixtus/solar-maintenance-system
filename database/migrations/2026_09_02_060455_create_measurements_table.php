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
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();

            // Component being measured
            $table->foreignId('component_id')
                ->constrained('components')
                ->cascadeOnDelete();

            // User who recorded the measurement
            $table->foreignId('recorded_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Date the measurement was taken
            $table->date('measurement_date');

            // Example: Voltage, Current, Temperature, Capacity
            $table->string('parameter');

            // Measured value
            $table->decimal('value', 12, 3);

            // Example: V, A, °C, Ah, W
            $table->string('unit');

            // Initial/reference value for comparison
            $table->decimal('reference_value', 12, 3)->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};