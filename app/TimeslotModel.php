<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeslotModel extends Model
{
    protected $fillable = [
    	'service_id', 'duration', 'calendar', 'arrived', 'show_estimated', 'reschedule_allow',
        'sunday_active', 'sunday_val',
        'monday_active', 'monday_val',
        'tuesday_active', 'tuesday_val',
        'wednesday_active', 'wednesday_val',
        'thursday_active', 'thursday_val',
        'friday_active', 'friday_val',
        'saturday_active', 'saturday_val',
        'duration_show', 'service_duration', 'start_time'
    ];
}
