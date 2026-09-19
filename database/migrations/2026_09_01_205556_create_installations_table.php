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
        Schema::create('installations', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('location');

            $table->date('installation_date');

            $table->decimal('system_capacity', 10, 2)->nullable();

            $table->text('description')->nullable();

            $table->enum('status', [
                'Active',
                'Under Maintenance',
                'Inactive'
            ])->default('Active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installations');
    }
};