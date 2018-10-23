<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppointmentModel extends Model
{
    protected $fillable = ['user_id', 'booking_order_id', 'appointment_title', 'appointment_body', 'is_read'];
}
