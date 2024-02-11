<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationBooking extends Model
{
    use HasFactory;

    protected $fillable = ['accommodation_id', 'user_id', 'status', 'check_in', 'time_check_in', 'start_date', 'end_date', 'amount_of_days', 'total', 'note'];

    // booking belongs to the accommodation model
    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    // booking belongs to the user model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
