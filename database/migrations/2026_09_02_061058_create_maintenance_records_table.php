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
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();

            // Component that received maintenance
            $table->foreignId('component_id')
                ->constrained('components')
                ->cascadeOnDelete();

            // Technician who performed the maintenance
            $table->foreignId('technician_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Preventive or corrective maintenance
            $table->enum('maintenance_type', [
                'Preventive',
                'Corrective'
            ]);

            $table->date('maintenance_date');

            // Description of the problem/work
            $table->text('description')->nullable();

            // Condition before maintenance
            $table->enum('condition_before', [
                'Excellent',
                'Good',
                'Fair',
                'Poor',
                'Critical'
            ])->nullable();

            // Work performed
            $table->text('action_taken');

            // Condition after maintenance
            $table->enum('condition_after', [
                'Excellent',
                'Good',
                'Fair',
                'Poor',
                'Critical'
            ])->nullable();

            // Next maintenance date
            $table->date('next_due_date')->nullable();

            // Maintenance status
            $table->enum('status', [
                'Completed',
                'Pending',
                'Cancelled'
            ])->default('Completed');

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};