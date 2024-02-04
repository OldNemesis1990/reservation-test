<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccomodationAmenities extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'icon_path'];
}
