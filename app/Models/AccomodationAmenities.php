<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccomodationAmenities extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'icon_path'];
    
    // model has many configuration
    public function configurations() {
        return $this->hasMany(AccommodationAmenityConfiguration::class);
    }
}
