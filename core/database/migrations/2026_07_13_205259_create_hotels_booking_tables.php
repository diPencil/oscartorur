<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('booking_holds', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('converted_booking_id')->nullable();
            $table->dateTime('expires_at');
            $table->string('status')->default('active'); // active, converted, expired, released
            $table->timestamps();

            $table->index(['expires_at', 'status']);
        });

        Schema::create('booking_hold_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_hold_id');
            $table->unsignedBigInteger('contract_room_type_id');
            $table->unsignedBigInteger('rate_plan_id');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('rooms_count')->default(1);
            $table->integer('adults')->default(2);
            $table->integer('children')->default(0);
            $table->text('children_ages')->nullable(); // JSON array
            $table->timestamps();
        });

        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('hotel_id');
            $table->string('sales_channel')->default('b2c_web'); // b2c_web, b2b_agent, admin, api
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('customer_type')->default('b2c');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('rooms_count')->default(1);
            $table->integer('adults')->default(2);
            $table->integer('children')->default(0);
            $table->text('children_ages')->nullable(); // JSON
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->decimal('subtotal', 28, 8)->default(0);
            $table->decimal('taxes', 28, 8)->default(0);
            $table->decimal('fees', 28, 8)->default(0);
            $table->decimal('discount', 28, 8)->default(0);
            $table->decimal('total_price', 28, 8)->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->string('booking_status')->default('pending'); // pending, confirmed, cancelled, completed, no_show
            $table->text('special_requests')->nullable();
            $table->dateTime('cancellation_deadline')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['check_in', 'check_out', 'booking_status']);
        });

        Schema::create('booking_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_booking_id');
            $table->unsignedBigInteger('contract_room_type_id');
            $table->unsignedBigInteger('rate_plan_id');
            $table->integer('adults')->default(2);
            $table->integer('children')->default(0);
            $table->decimal('price', 28, 8)->default(0);
            
            // Snapshots
            $table->decimal('cost_price_snapshot', 28, 8)->default(0);
            $table->decimal('selling_price_snapshot', 28, 8)->default(0);
            $table->decimal('markup_snapshot', 28, 8)->default(0);
            $table->decimal('tax_snapshot', 28, 8)->default(0);
            $table->string('rate_plan_name_snapshot')->nullable();
            $table->text('cancellation_policy_snapshot')->nullable();
            $table->string('meal_plan_snapshot')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_room_nights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_room_id');
            $table->unsignedBigInteger('room_inventory_id')->nullable();
            $table->date('stay_date');
            $table->decimal('cost_price', 28, 8)->default(0);
            $table->decimal('selling_price', 28, 8)->default(0);
            $table->decimal('markup_amount', 28, 8)->default(0);
            $table->decimal('tax_amount', 28, 8)->default(0);
            $table->decimal('fees_amount', 28, 8)->default(0);
            $table->decimal('discount_amount', 28, 8)->default(0);
            $table->decimal('total_amount', 28, 8)->default(0);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_guests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_booking_id');
            $table->unsignedBigInteger('booking_room_id')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('guest_type')->default('adult'); // adult, child
            $table->string('nationality')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('passport_number')->nullable();
            $table->boolean('is_lead_guest')->default(false);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_booking_id');
            $table->string('payment_reference')->nullable();
            $table->string('gateway')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 28, 8);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->text('gateway_response')->nullable();
            $table->timestamps();

            $table->unique(['gateway', 'transaction_id']);
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('hotel_booking_id');
            $table->decimal('amount', 28, 8);
            $table->string('reason')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('refunded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_booking_id');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_inventory_id');
            $table->unsignedBigInteger('hotel_booking_id')->nullable();
            $table->string('movement_type'); // booking, cancellation, manual_adjustment, hold, release
            $table->integer('quantity');
            $table->integer('before_quantity');
            $table->integer('after_quantity');
            $table->string('reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_price_breakdown', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_booking_id');
            $table->unsignedBigInteger('booking_room_id')->nullable();
            $table->string('type'); // tax, fee, discount, markup
            $table->string('title');
            $table->decimal('amount', 28, 8);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_price_breakdown');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('booking_status_history');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_guests');
        Schema::dropIfExists('booking_room_nights');
        Schema::dropIfExists('booking_rooms');
        Schema::dropIfExists('hotel_bookings');
        Schema::dropIfExists('booking_hold_rooms');
        Schema::dropIfExists('booking_holds');
    }
};
