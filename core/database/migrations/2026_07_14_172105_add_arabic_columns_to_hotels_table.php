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
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
            $table->string('address_ar')->nullable()->after('address');
            $table->text('short_description_ar')->nullable()->after('short_description');
            $table->text('description_ar')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['name_ar', 'address_ar', 'short_description_ar', 'description_ar']);
        });
    }
};
