<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['user_id', 'holiday_date', 'holiday_for'];
}
