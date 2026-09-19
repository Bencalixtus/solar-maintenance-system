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
        Schema::create('components', function (Blueprint $table) {
            $table->id();

            // Relationship with installations table
            $table->foreignId('installation_id')
                ->constrained('installations')
                ->cascadeOnDelete();

            // Relationship with component_types table
            $table->foreignId('component_type_id')
                ->constrained('component_types')
                ->cascadeOnDelete();

            $table->string('name');

            $table->string('manufacturer')->nullable();

            $table->string('model')->nullable();

            $table->string('serial_number')->nullable()->unique();

            $table->date('installation_date')->nullable();

            $table->decimal('rated_capacity', 10, 2)->nullable();

            $table->decimal('rated_voltage', 10, 2)->nullable();

            $table->decimal('expected_lifespan', 5, 2)->nullable();

            $table->enum('current_condition', [
                'Excellent',
                'Good',
                'Fair',
                'Poor',
                'Critical'
            ])->default('Good');

            $table->enum('status', [
                'Operational',
                'Under Maintenance',
                'Faulty',
                'Replaced',
                'Inactive'
            ])->default('Operational');

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};