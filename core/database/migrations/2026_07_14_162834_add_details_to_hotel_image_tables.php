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
        Schema::table('hotel_images', function (Blueprint $table) {
            $table->string('category')->nullable()->after('image');
            $table->string('alt_text')->nullable()->after('title');
            $table->tinyInteger('status')->default(1)->after('is_cover');
        });

        Schema::table('room_type_images', function (Blueprint $table) {
            $table->string('title')->nullable()->after('image');
            $table->string('alt_text')->nullable()->after('title');
            $table->tinyInteger('status')->default(1)->after('is_cover');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_image_tables', function (Blueprint $table) {
            //
        });
    }
};
