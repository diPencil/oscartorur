<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hotel extends Model
{
    use HasFactory, GlobalStatus;

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function getNameAttribute($value)
    {
        if (app()->getLocale() == 'ar' && !empty($this->name_ar)) {
            return $this->name_ar;
        }
        return $value;
    }

    protected static function booted()
    {
        static::creating(function ($hotel) {
            if (empty($hotel->hotel_code)) {
                // Generate a temporary code; it will be updated after creation to use the ID.
                $hotel->hotel_code = 'HTL-TEMP-' . Str::random(6);
            }
        });

        static::created(function ($hotel) {
            if (str_starts_with($hotel->hotel_code, 'HTL-TEMP-')) {
                $hotel->hotel_code = 'HTL-' . str_pad($hotel->id, 6, '0', STR_PAD_LEFT);
                $hotel->save();
            }
        });
    }

    /**
     * Checks if the hotel meets all requirements to become active.
     */
    public function checkActivationReadiness(): array
    {
        $errors = [];
        
        if (empty($this->hotel_code) || empty($this->name) || empty($this->location_id)) {
            $errors[] = "Basic information is incomplete.";
        }

        if (!$this->images()->where('is_cover', 1)->exists()) {
            $errors[] = "Hotel cover image is missing.";
        }

        if ($this->roomTypes()->count() === 0) {
            $errors[] = "At least one room type is required.";
        } else {
            foreach ($this->roomTypes as $room) {
                if ($room->images()->count() == 0) {
                    $errors[] = "Room type '{$room->name}' is missing an image.";
                }
            }
        }

        // User requested not to require Contracts and Rate Plans for activation
        /*
        $activeContracts = HotelContract::where('hotel_id', $this->id)->where('status', 1)->count();
        if ($activeContracts === 0) {
            $errors[] = "At least one active contract is required.";
        }

        $activeRatePlans = \App\Models\RatePlan::whereHas('contractRoomType', function($q) {
            $q->whereHas('contract', function($c) {
                $c->where('hotel_id', $this->id)->where('status', 1);
            });
        })->where('status', 1)->count();

        if ($activeRatePlans === 0) {
            $errors[] = "At least one active rate plan is required.";
        }
        */

        return $errors;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function supplier()
    {
        return $this->belongsTo(HotelSupplier::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_hotel');
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function getStatusBadgeAttribute()
    {
        $html = '';
        if ($this->status == 'active') {
            $html = '<span class="badge badge--success">'.trans('Active').'</span>';
        } elseif ($this->status == 'draft') {
            $html = '<span class="badge badge--secondary">'.trans('Draft').'</span>';
        } elseif ($this->status == 'inactive') {
            $html = '<span class="badge badge--warning">'.trans('Inactive').'</span>';
        } elseif ($this->status == 'suspended') {
            $html = '<span class="badge badge--danger">'.trans('Suspended').'</span>';
        }
        return $html;
    }

    public function getStartingPriceAttribute()
    {
        return \Illuminate\Support\Facades\DB::table('room_rates')
            ->join('rate_plans', 'room_rates.rate_plan_id', '=', 'rate_plans.id')
            ->join('contract_room_types', 'rate_plans.contract_room_type_id', '=', 'contract_room_types.id')
            ->join('hotel_contracts', 'contract_room_types.contract_id', '=', 'hotel_contracts.id')
            ->where('hotel_contracts.hotel_id', $this->id)
            ->where('hotel_contracts.status', 1)
            ->where('rate_plans.status', 1)
            ->where('room_rates.selling_price', '>', 0)
            ->whereDate('room_rates.date', '>=', now()->toDateString())
            ->min('room_rates.selling_price') ?? 0;
    }
}
