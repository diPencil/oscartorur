<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use GlobalStatus;

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() != 'ar') {
            return $this->name;
        }

        if (!empty($this->name_ar)) {
            return $this->name_ar;
        }

        $translations = [
            'hurghada' => 'الغردقة',
            'marsa alam' => 'مرسى علم',
            'sharm el-sheikh' => 'شرم الشيخ',
            'sharm el sheikh' => 'شرم الشيخ',
        ];

        $normalizedName = strtolower(trim(preg_replace('/\s+/', ' ', str_replace(['-', '_'], ' ', (string) $this->name))));
        $normalizedNameWithHyphen = strtolower(trim(preg_replace('/\s+/', ' ', (string) $this->name)));

        return $translations[$normalizedName]
            ?? $translations[$normalizedNameWithHyphen]
            ?? $this->name;
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
}
