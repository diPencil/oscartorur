<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Carbon\Carbon;
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
        'is_year_round'   => 'boolean',
        'seo_content'     => 'object'
    ];

    public function scopePubliclyAvailable($query)
    {
        return $query->active();
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->localizedValue('name', 'name_ar');
    }

    public function getDisplayDetailsAttribute(): string
    {
        return $this->localizedValue('details', 'details_ar');
    }

    public function getDisplayIncludedAttribute()
    {
        return app()->getLocale() == 'ar' && $this->hasItems($this->included_ar) ? $this->included_ar : $this->included;
    }

    public function getDisplayExcludedAttribute()
    {
        return app()->getLocale() == 'ar' && $this->hasItems($this->excluded_ar) ? $this->excluded_ar : $this->excluded;
    }

    public function getDisplaySeminarPlanAttribute()
    {
        return app()->getLocale() == 'ar' && $this->hasItems($this->seminar_plan_ar) ? $this->seminar_plan_ar : $this->seminar_plan;
    }

    public function getDisplayImageUrlAttribute(): string
    {
        $image = collect($this->images ?? [])->filter()->first();

        return $this->imageUrl($image, getFileSize('seminar'));
    }

    public function getAvailabilityLabelAttribute(): string
    {
        if ($this->is_year_round) {
            return trans('Available all year');
        }

        return $this->start_time ? showDateTime($this->start_time) : '-';
    }

    public function getHasBookingWindowClosedAttribute(): bool
    {
        if ($this->is_year_round || !$this->start_time) {
            return false;
        }

        return Carbon::parse($this->start_time)->lt(now());
    }

    public function imageUrl($image, $size = null): string
    {
        $image = trim(html_entity_decode((string) $image, ENT_QUOTES, 'UTF-8'));

        if (!$image) {
            return getImage('', $size);
        }

        if (filter_var($image, FILTER_VALIDATE_URL) || preg_match('/^https?:\/\//i', $image)) {
            return getImage($image, $size);
        }

        return getImage(getFilePath('seminar') . '/' . ltrim($image, '/'), $size);
    }

    protected function localizedValue(string $baseColumn, string $localizedColumn): string
    {
        $localizedValue = $this->getAttribute($localizedColumn);

        if (app()->getLocale() == 'ar' && filled($localizedValue)) {
            return $localizedValue;
        }

        return (string) $this->getRawOriginal($baseColumn);
    }

    protected function hasItems($value): bool
    {
        return collect($value ?? [])->filter(fn ($item) => filled($item))->isNotEmpty();
    }

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
