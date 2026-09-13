<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'accuracy_masuk')) {
                $table->decimal('accuracy_masuk', 10, 2)->nullable()->after('longitude_masuk');
            }

            if (!Schema::hasColumn('attendances', 'device_id_masuk')) {
                $table->string('device_id_masuk')->nullable()->after('foto_masuk');
            }

            if (!Schema::hasColumn('attendances', 'device_name_masuk')) {
                $table->string('device_name_masuk')->nullable()->after('device_id_masuk');
            }

            if (!Schema::hasColumn('attendances', 'device_platform_masuk')) {
                $table->string('device_platform_masuk')->nullable()->after('device_name_masuk');
            }

            if (!Schema::hasColumn('attendances', 'device_browser_masuk')) {
                $table->string('device_browser_masuk')->nullable()->after('device_platform_masuk');
            }

            if (!Schema::hasColumn('attendances', 'device_ip_masuk')) {
                $table->string('device_ip_masuk')->nullable()->after('device_browser_masuk');
            }

            if (!Schema::hasColumn('attendances', 'accuracy_pulang')) {
                $table->decimal('accuracy_pulang', 10, 2)->nullable()->after('longitude_pulang');
            }

            if (!Schema::hasColumn('attendances', 'device_id_pulang')) {
                $table->string('device_id_pulang')->nullable()->after('foto_pulang');
            }

            if (!Schema::hasColumn('attendances', 'device_name_pulang')) {
                $table->string('device_name_pulang')->nullable()->after('device_id_pulang');
            }

            if (!Schema::hasColumn('attendances', 'device_platform_pulang')) {
                $table->string('device_platform_pulang')->nullable()->after('device_name_pulang');
            }

            if (!Schema::hasColumn('attendances', 'device_browser_pulang')) {
                $table->string('device_browser_pulang')->nullable()->after('device_platform_pulang');
            }

            if (!Schema::hasColumn('attendances', 'device_ip_pulang')) {
                $table->string('device_ip_pulang')->nullable()->after('device_browser_pulang');
            }
        });
    }

    public function down(): void
    {
        $columns = [
            'accuracy_masuk',
            'device_id_masuk',
            'device_name_masuk',
            'device_platform_masuk',
            'device_browser_masuk',
            'device_ip_masuk',
            'accuracy_pulang',
            'device_id_pulang',
            'device_name_pulang',
            'device_platform_pulang',
            'device_browser_pulang',
            'device_ip_pulang',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('attendances', $column)) {
                Schema::table('attendances', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
