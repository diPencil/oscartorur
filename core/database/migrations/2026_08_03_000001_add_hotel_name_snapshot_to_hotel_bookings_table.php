<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('hotel_bookings', 'hotel_name_snapshot')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->string('hotel_name_snapshot')->nullable()->after('hotel_id');
            });
        }

        if (Schema::hasColumn('hotel_bookings', 'hotel_name_snapshot')) {
            DB::table('hotel_bookings')
                ->whereNull('hotel_bookings.hotel_name_snapshot')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('hotels')
                        ->whereColumn('hotels.id', 'hotel_bookings.hotel_id');
                })
                ->orderBy('id')
                ->chunkById(500, function ($bookings) {
                    foreach ($bookings as $booking) {
                        $hotelName = DB::table('hotels')
                            ->where('id', $booking->hotel_id)
                            ->value('name');

                        if ($hotelName) {
                            DB::table('hotel_bookings')
                                ->where('id', $booking->id)
                                ->whereNull('hotel_name_snapshot')
                                ->update(['hotel_name_snapshot' => $hotelName]);
                        }
                    }
                });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('hotel_bookings', 'hotel_name_snapshot')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->dropColumn('hotel_name_snapshot');
            });
        }
    }
};
