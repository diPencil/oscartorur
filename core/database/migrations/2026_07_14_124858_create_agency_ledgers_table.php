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
        Schema::create('agency_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id');
            $table->string('transaction_type'); // booking, payment, refund, adjustment
            $table->unsignedBigInteger('reference_id')->nullable(); // hotel_booking_id or payment_id
            $table->decimal('amount', 28, 8);
            $table->decimal('balance_after', 28, 8);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_ledgers');
    }
};
