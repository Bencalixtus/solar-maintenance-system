<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'Administrator',
                'Technician',
                'Supervisor'
            ])->default('Technician')->after('password');

            $table->string('phone')->nullable()->after('role');

            $table->enum('status', [
                'Active',
                'Inactive'
            ])->default('Active')->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'status'
            ]);
        });
    }
};