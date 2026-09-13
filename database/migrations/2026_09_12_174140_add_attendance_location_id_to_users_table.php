<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('attendance_location_id')
                ->nullable()
                ->after('role')
                ->constrained('attendance_locations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign([
                'attendance_location_id',
            ]);

            $table->dropColumn('attendance_location_id');
        });
    }
};
