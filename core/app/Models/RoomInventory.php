<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomInventory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function contractRoomType()
    {
        return $this->belongsTo(ContractRoomType::class, 'contract_room_type_id');
    }
}
