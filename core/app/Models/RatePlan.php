<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatePlan extends Model
{
    use HasFactory, GlobalStatus;

    public function contractRoomType()
    {
        return $this->belongsTo(ContractRoomType::class, 'contract_room_type_id');
    }

    public function cancellationPolicy()
    {
        return $this->belongsTo(CancellationPolicy::class, 'cancellation_policy_id');
    }

    public function roomRates()
    {
        return $this->hasMany(RoomRate::class, 'rate_plan_id');
    }
}
