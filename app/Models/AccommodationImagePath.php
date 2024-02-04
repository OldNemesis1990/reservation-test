<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationImagePath extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'image_path',
        'featured_image',
    ];

    // model belongs to accommodation
    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }
}
