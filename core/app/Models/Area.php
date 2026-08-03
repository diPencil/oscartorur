<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory, GlobalStatus;

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() == 'ar' && !empty($this->name_ar)) {
            return $this->name_ar;
        }

        return (string) $this->name;
    }
}
