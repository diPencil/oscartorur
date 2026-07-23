<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory, GlobalStatus;

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function beds()
    {
        return $this->belongsToMany(BedType::class, 'room_type_beds')->withPivot('quantity');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_room_type');
    }

    public function images()
    {
        return $this->hasMany(RoomTypeImage::class);
    }

    public function contractRoomTypes()
    {
        return $this->hasMany(ContractRoomType::class);
    }
}
