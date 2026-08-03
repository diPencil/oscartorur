<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use GlobalStatus;

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() == 'ar' && !empty($this->name_ar)) {
            return $this->name_ar;
        }

        return (string) $this->name;
    }

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

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
