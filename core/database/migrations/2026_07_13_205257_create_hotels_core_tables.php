<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('agencies')) {
            Schema::create('agencies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique()->nullable();
                $table->integer('country_id')->nullable();
                $table->integer('currency_id')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->decimal('credit_limit', 28, 8)->default(0);
                $table->string('payment_terms')->nullable();
                $table->timestamps();
            });
        }

        Schema::create('hotel_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('taxes_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // tax, service_fee, resort_fee, etc.
            $table->string('calculation_type'); // percentage, fixed
            $table->decimal('value', 28, 8);
            $table->boolean('included_in_price')->default(false);
            $table->integer('country_id')->nullable();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->tinyInteger('star_rating')->default(1);
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->longText('description')->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('inventory_mode')->default('shared'); // dedicated, shared, on_request
            $table->tinyInteger('status')->default(1);
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->index(['location_id', 'status', 'star_rating']);
        });

        Schema::create('hotel_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('locale');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();

            $table->unique(['hotel_id', 'locale']);
        });

        Schema::create('hotel_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('image');
            $table->string('title')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->timestamps();
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('type')->default('hotel'); // hotel or room
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('amenity_hotel', function (Blueprint $table) {
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('amenity_id');
            $table->unique(['hotel_id', 'amenity_id']);
        });

        Schema::create('hotel_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('contract_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('currency_id')->nullable();
            $table->string('market')->nullable();
            $table->string('nationality_restriction')->nullable();
            $table->integer('release_days')->default(0);
            $table->string('payment_terms')->nullable();
            $table->string('confirmation_mode')->default('instant'); // instant, on_request
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->longText('description')->nullable();
            $table->integer('max_adults')->default(2);
            $table->integer('max_children')->default(0);
            $table->integer('max_occupancy')->default(2);
            $table->decimal('size', 8, 2)->nullable();
            $table->integer('bathrooms_count')->default(1);
            $table->integer('base_inventory')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('contract_room_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('room_type_id');
            $table->string('supplier_room_code')->nullable();
            $table->integer('allotment')->default(0);
            $table->integer('release_days')->nullable();
            $table->timestamps();

            $table->unique(['contract_id', 'room_type_id']);
        });

        Schema::create('bed_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('room_type_beds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_type_id');
            $table->unsignedBigInteger('bed_type_id');
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->unique(['room_type_id', 'bed_type_id']);
        });

        Schema::create('room_type_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_type_id');
            $table->string('image');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->timestamps();
        });

        Schema::create('amenity_room_type', function (Blueprint $table) {
            $table->unsignedBigInteger('room_type_id');
            $table->unsignedBigInteger('amenity_id');
            $table->unique(['room_type_id', 'amenity_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('amenity_room_type');
        Schema::dropIfExists('room_type_images');
        Schema::dropIfExists('room_type_beds');
        Schema::dropIfExists('bed_types');
        Schema::dropIfExists('contract_room_types');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('hotel_contracts');
        Schema::dropIfExists('amenity_hotel');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('hotel_images');
        Schema::dropIfExists('hotel_translations');
        Schema::dropIfExists('hotels');
        Schema::dropIfExists('taxes_fees');
        Schema::dropIfExists('hotel_suppliers');
        Schema::dropIfExists('agencies');
    }
};
