<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
}
