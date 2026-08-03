<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function hotelDisplayName(): Attribute
    {
        return Attribute::get(function ($value, $attributes) {
            return $this->getRelationValue('hotel')?->name
                ?? $attributes['hotel_name_snapshot'] ?? null
                ?? trans('Hotel unavailable');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rooms()
    {
        return $this->hasMany(BookingRoom::class, 'hotel_booking_id');
    }

    public function guests()
    {
        return $this->hasMany(BookingGuest::class, 'hotel_booking_id');
    }

    public function priceBreakdown()
    {
        return $this->hasMany(BookingPriceBreakdown::class, 'hotel_booking_id');
    }
}
