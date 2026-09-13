<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('user_id');

            $table->dateTime('waktu_masuk')->nullable()->after('tanggal');
            $table->dateTime('waktu_pulang')->nullable()->after('waktu_masuk');

            $table->decimal('latitude_masuk', 10, 8)->nullable()->after('waktu_pulang');
            $table->decimal('longitude_masuk', 11, 8)->nullable()->after('latitude_masuk');
            $table->double('jarak_masuk', 8, 2)->nullable()->after('longitude_masuk');
            $table->string('foto_masuk')->nullable()->after('jarak_masuk');

            $table->decimal('latitude_pulang', 10, 8)->nullable()->after('foto_masuk');
            $table->decimal('longitude_pulang', 11, 8)->nullable()->after('latitude_pulang');
            $table->double('jarak_pulang', 8, 2)->nullable()->after('longitude_pulang');
            $table->string('foto_pulang')->nullable()->after('jarak_pulang');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal',
                'waktu_masuk',
                'waktu_pulang',
                'latitude_masuk',
                'longitude_masuk',
                'jarak_masuk',
                'foto_masuk',
                'latitude_pulang',
                'longitude_pulang',
                'jarak_pulang',
                'foto_pulang',
            ]);
        });
    }
};
