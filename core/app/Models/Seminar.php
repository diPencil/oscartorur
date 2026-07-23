<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use GlobalStatus;

    protected $casts = [
        'included'        => 'object',
        'included_ar'     => 'object',
        'excluded'        => 'object',
        'excluded_ar'     => 'object',
        'seminar_plan'    => 'object',
        'seminar_plan_ar' => 'object',
        'images'          => 'array',
        'seo_content'     => 'object'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    // Relations
    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'plan_id')->where('type', 'seminar');
    }
}
