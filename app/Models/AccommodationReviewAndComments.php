<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationReviewAndComments extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
