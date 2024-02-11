<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'price_per_adult', 'price_per_child', 'user_id', 'amenities'];

    // model has many bookings
    public function bookings() {
        return $this->hasMany(AccommodationBooking::class);
    }

    // model has many images
    public function images() {
        return $this->hasMany(AccommodationImagePath::class);
    }

    public function ratings() {
        return $this->hasMany(AccommodationReviewAndComments::class);
    }
}
