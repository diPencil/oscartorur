<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cancellation_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('cancellation_policy_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cancellation_policy_id');
            $table->integer('from_hours_before')->default(0);
            $table->integer('to_hours_before')->nullable();
            $table->string('penalty_type'); // fixed_amount, percentage, first_night, full_booking
            $table->decimal('penalty_value', 28, 8)->default(0);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('rate_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_room_type_id');
            $table->string('name');
            $table->unsignedBigInteger('meal_plan_id')->nullable();
            $table->unsignedBigInteger('cancellation_policy_id')->nullable();
            $table->string('payment_type')->nullable(); // prepaid, post_paid
            $table->boolean('refundable')->default(false);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('rate_plan_child_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rate_plan_id');
            $table->integer('min_age');
            $table->integer('max_age');
            $table->string('pricing_type'); // free, percentage, fixed
            $table->decimal('value', 28, 8)->default(0);
            $table->boolean('requires_extra_bed')->default(false);
            $table->timestamps();
        });

        Schema::create('room_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rate_plan_id');
            $table->date('date');
            $table->decimal('cost_price', 28, 8);
            $table->decimal('selling_price', 28, 8);
            $table->decimal('single_supplement', 28, 8)->default(0);
            $table->decimal('extra_adult_price', 28, 8)->default(0);
            $table->integer('minimum_stay')->default(1);
            $table->integer('maximum_stay')->nullable();
            $table->boolean('closed_to_arrival')->default(false);
            $table->boolean('closed_to_departure')->default(false);
            $table->timestamps();

            $table->unique(['rate_plan_id', 'date']);
            $table->index(['date', 'rate_plan_id']);
        });

        Schema::create('room_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_room_type_id');
            $table->date('date');
            $table->integer('total_inventory')->default(0);
            $table->integer('reserved_inventory')->default(0);
            $table->integer('held_inventory')->default(0);
            $table->integer('blocked_inventory')->default(0);
            $table->boolean('stop_sale')->default(false);
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->unique(['contract_room_type_id', 'date']);
            $table->index(['date', 'contract_room_type_id']);
        });

        Schema::create('markup_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('customer_type')->nullable(); // b2c, b2b, corporate
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('market')->nullable();
            $table->string('markup_type'); // percentage, fixed_amount, per_night, per_booking
            $table->decimal('markup_value', 28, 8);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('priority')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('markup_rules');
        Schema::dropIfExists('room_inventories');
        Schema::dropIfExists('room_rates');
        Schema::dropIfExists('rate_plan_child_policies');
        Schema::dropIfExists('rate_plans');
        Schema::dropIfExists('cancellation_policy_rules');
        Schema::dropIfExists('cancellation_policies');
    }
};
