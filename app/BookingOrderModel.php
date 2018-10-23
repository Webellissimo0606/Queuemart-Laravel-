<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingOrderModel extends Model
{
    protected $fillable = ['user_id', 'branch_id', 'service_id', 'package_id', 'booking_status', 'arrived_check', 'qrcode_field', 'manager_id', 'booking_day', 'booking_time', 'booked_time', 'paid_time', 'paid_price', 'arrived_time', 'call_time', 'complete_time', 'order_duration', 'client_notice', 'admin_notice', 'promo_id', 'promo_code', 'rating_check', 'reschedule_check', 'reschedule_time'];
}
