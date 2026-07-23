<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractRoomType extends Model
{
    use HasFactory;

    public function contract()
    {
        return $this->belongsTo(HotelContract::class, 'contract_id');
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function ratePlans()
    {
        return $this->hasMany(RatePlan::class, 'contract_room_type_id');
    }
}
