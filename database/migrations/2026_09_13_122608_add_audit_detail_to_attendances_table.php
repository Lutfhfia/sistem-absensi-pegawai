<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('accuracy_masuk', 10, 2)
                ->nullable()
                ->after('longitude_masuk');

            $table->string('device_name_masuk')
                ->nullable()
                ->after('foto_masuk');

            $table->string('device_platform_masuk')
                ->nullable()
                ->after('device_name_masuk');

            $table->string('device_browser_masuk')
                ->nullable()
                ->after('device_platform_masuk');

            $table->string('device_ip_masuk')
                ->nullable()
                ->after('device_browser_masuk');

            $table->string('device_id_masuk')
                ->nullable()
                ->after('device_ip_masuk');

            $table->decimal('accuracy_pulang', 10, 2)
                ->nullable()
                ->after('longitude_pulang');

            $table->string('device_name_pulang')
                ->nullable()
                ->after('foto_pulang');

            $table->string('device_platform_pulang')
                ->nullable()
                ->after('device_name_pulang');

            $table->string('device_browser_pulang')
                ->nullable()
                ->after('device_platform_pulang');

            $table->string('device_ip_pulang')
                ->nullable()
                ->after('device_browser_pulang');

            $table->string('device_id_pulang')
                ->nullable()
                ->after('device_ip_pulang');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'accuracy_masuk',
                'device_name_masuk',
                'device_platform_masuk',
                'device_browser_masuk',
                'device_ip_masuk',
                'device_id_masuk',
                'accuracy_pulang',
                'device_name_pulang',
                'device_platform_pulang',
                'device_browser_pulang',
                'device_ip_pulang',
                'device_id_pulang',
            ]);
        });
    }
};