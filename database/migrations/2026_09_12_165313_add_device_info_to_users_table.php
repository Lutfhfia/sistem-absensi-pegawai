<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('device_name')->nullable()->after('device_id');
            $table->string('device_platform')->nullable()->after('device_name');
            $table->string('device_browser')->nullable()->after('device_platform');
            $table->string('device_ip')->nullable()->after('device_browser');
            $table->decimal('device_latitude', 10, 8)->nullable()->after('device_ip');
            $table->decimal('device_longitude', 11, 8)->nullable()->after('device_latitude');
            $table->timestamp('device_last_login')->nullable()->after('device_longitude');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'device_name',
                'device_platform',
                'device_browser',
                'device_ip',
                'device_latitude',
                'device_longitude',
                'device_last_login',
            ]);
        });
    }
};
