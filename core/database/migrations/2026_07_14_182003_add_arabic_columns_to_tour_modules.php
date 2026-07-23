<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'name_ar')) {
                    $table->string('name_ar')->nullable()->after('name');
                }
            });
        }

        if (Schema::hasTable('locations')) {
            Schema::table('locations', function (Blueprint $table) {
                if (!Schema::hasColumn('locations', 'name_ar')) {
                    $table->string('name_ar')->nullable()->after('name');
                }
            });
        }

        if (Schema::hasTable('areas')) {
            Schema::table('areas', function (Blueprint $table) {
                if (!Schema::hasColumn('areas', 'name_ar')) {
                    $table->string('name_ar')->nullable()->after('name');
                }
            });
        }

        if (Schema::hasTable('plans')) {
            Schema::table('plans', function (Blueprint $table) {
                if (!Schema::hasColumn('plans', 'name_ar')) {
                    $table->string('name_ar')->nullable()->after('name');
                    $table->text('details_ar')->nullable()->after('details');
                    $table->text('included_ar')->nullable()->after('included');
                    $table->text('excluded_ar')->nullable()->after('excluded');
                    $table->text('tour_plan_ar')->nullable()->after('tour_plan');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn(['name_ar']);
            });
        }
        
        if (Schema::hasTable('locations')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->dropColumn(['name_ar']);
            });
        }
        
        if (Schema::hasTable('areas')) {
            Schema::table('areas', function (Blueprint $table) {
                $table->dropColumn(['name_ar']);
            });
        }

        if (Schema::hasTable('plans')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn(['name_ar', 'details_ar', 'included_ar', 'excluded_ar', 'tour_plan_ar']);
            });
        }
    }
};
