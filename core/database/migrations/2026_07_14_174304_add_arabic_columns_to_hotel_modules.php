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
        Schema::table('hotel_suppliers', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
        
        Schema::table('amenities', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
            $table->text('description_ar')->nullable()->after('description');
        });

        Schema::table('bed_types', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });

        Schema::table('hotel_contracts', function (Blueprint $table) {
            $table->string('contract_name_ar')->nullable()->after('contract_name');
        });

        Schema::table('rate_plans', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });

        Schema::table('cancellation_policies', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
            $table->text('description_ar')->nullable()->after('description');
        });

        Schema::table('agencies', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_suppliers', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });
        
        Schema::table('amenities', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn(['name_ar', 'description_ar']);
        });

        Schema::table('bed_types', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });

        Schema::table('hotel_contracts', function (Blueprint $table) {
            $table->dropColumn('contract_name_ar');
        });

        Schema::table('rate_plans', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });

        Schema::table('cancellation_policies', function (Blueprint $table) {
            $table->dropColumn(['name_ar', 'description_ar']);
        });

        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });
    }
};
