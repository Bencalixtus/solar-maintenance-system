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
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();

            // Component that requires maintenance
            $table->foreignId('component_id')
                ->constrained('components')
                ->cascadeOnDelete();

            // Example: Battery inspection, Panel cleaning
            $table->string('maintenance_task');

            // Example: Weekly, Monthly, Quarterly, Yearly
            $table->string('frequency');

            // Date maintenance was last performed
            $table->date('last_maintenance_date')->nullable();

            // Date the next maintenance is due
            $table->date('next_due_date');

            // Importance of the maintenance task
            $table->enum('priority', [
                'Low',
                'Medium',
                'High',
                'Critical'
            ])->default('Medium');

            // Current schedule status
            $table->enum('status', [
                'Scheduled',
                'Due Soon',
                'Overdue',
                'Completed'
            ])->default('Scheduled');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};