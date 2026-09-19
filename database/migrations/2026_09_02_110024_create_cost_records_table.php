<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cost_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('component_id')
                ->constrained('components')
                ->cascadeOnDelete();

            $table->foreignId('maintenance_id')
                ->nullable()
                ->constrained('maintenance_records')
                ->nullOnDelete();

            $table->enum('cost_type', [
                'Maintenance',
                'Repair',
                'Replacement',
                'Inspection',
                'Other'
            ]);

            $table->string('description');

            $table->decimal('amount', 12, 2);

            $table->date('cost_date');

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_records');
    }
};