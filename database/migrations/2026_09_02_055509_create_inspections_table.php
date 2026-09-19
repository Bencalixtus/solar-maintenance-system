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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();

            // Installation being inspected
            $table->foreignId('installation_id')
                ->constrained('installations')
                ->cascadeOnDelete();

            // User who performed the inspection
            $table->foreignId('inspector_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('inspection_date');

            $table->enum('overall_condition', [
                'Excellent',
                'Good',
                'Fair',
                'Poor',
                'Critical'
            ])->default('Good');

            $table->text('remarks')->nullable();

            $table->date('next_inspection_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};