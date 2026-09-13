<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('permits', 'approved_by')) {
            Schema::table('permits', function (Blueprint $table) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
                $table->index('approved_by');
            });
        }

        if (!Schema::hasColumn('permits', 'approved_at')) {
            Schema::table('permits', function (Blueprint $table) {
                $table->dateTime('approved_at')->nullable()->after('approved_by');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('permits', 'approved_at')) {
            Schema::table('permits', function (Blueprint $table) {
                $table->dropColumn('approved_at');
            });
        }

        if (Schema::hasColumn('permits', 'approved_by')) {
            Schema::table('permits', function (Blueprint $table) {
                $table->dropIndex(['approved_by']);
                $table->dropColumn('approved_by');
            });
        }
    }
};
