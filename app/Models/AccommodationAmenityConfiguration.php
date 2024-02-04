<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationAmenityConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'accommodation_amenity_id',
        'description',
    ];

    // model belongs to accommodation
    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    // model belongs to accommodation amenity
    public function accommodationAmenity()
    {
        return $this->belongsTo(AccommodationAmenity::class);
    }
}
