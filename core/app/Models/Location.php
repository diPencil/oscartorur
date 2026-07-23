<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use GlobalStatus;

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function seminars()
    {
        return $this->hasMany(Seminar::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }
}
