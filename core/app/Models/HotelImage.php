<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelImage extends Model
{
    use HasFactory;

    public function getDisplayUrlAttribute(): string
    {
        $image = trim(html_entity_decode((string) $this->image, ENT_QUOTES, 'UTF-8'));

        if (!$image) {
            return getImage(getFilePath('hotelImage') . '/default.png');
        }

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return getImage($image, getFileSize('hotelImage'));
        }

        return getImage(getFilePath('hotelImage') . '/' . $image, getFileSize('hotelImage'));
    }
}
