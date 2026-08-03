<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('seminars') || Schema::hasColumn('seminars', 'is_year_round')) {
            return;
        }

        Schema::table('seminars', function (Blueprint $table) {
            $table->boolean('is_year_round')->default(false)->after('end_time')->index();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('seminars') || !Schema::hasColumn('seminars', 'is_year_round')) {
            return;
        }

        Schema::table('seminars', function (Blueprint $table) {
            $table->dropIndex(['is_year_round']);
            $table->dropColumn('is_year_round');
        });
    }
};
