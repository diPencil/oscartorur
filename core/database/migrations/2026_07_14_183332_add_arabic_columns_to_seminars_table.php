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
        if (Schema::hasTable('seminars')) {
            Schema::table('seminars', function (Blueprint $table) {
                if (!Schema::hasColumn('seminars', 'name_ar')) {
                    $table->string('name_ar')->nullable()->after('name');
                    $table->text('details_ar')->nullable()->after('details');
                    $table->text('included_ar')->nullable()->after('included');
                    $table->text('excluded_ar')->nullable()->after('excluded');
                    $table->text('seminar_plan_ar')->nullable()->after('seminar_plan');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('seminars')) {
            Schema::table('seminars', function (Blueprint $table) {
                $table->dropColumn(['name_ar', 'details_ar', 'included_ar', 'excluded_ar', 'seminar_plan_ar']);
            });
        }
    }
};
