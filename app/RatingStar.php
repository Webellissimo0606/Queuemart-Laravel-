<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RatingStar extends Model
{
    protected $fillable = ['user_id', 'booking_id', 'rating_star', 'rating_text'];
}
