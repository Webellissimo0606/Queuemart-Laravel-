<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PopupModel extends Model
{
    protected $fillable = ['user_id','question', 'answer', 'before_popup_title', 'before_popup_des', 'noholdcountdown', 'after_popup_title', 'after_popup_des', 'holdinghours', 'home_top_1_title', 'home_top_1_des', 'home_top_2_title', 'home_top_2_des', 'home_top_4_title', 'home_top_4_des', 'daysb4appointment', 'home_top_3_title', 'home_top_3_des', 'sms_after_booking', 'sms_payment', 'sms_cancel_admin', 'sms_cancel_client', 'sms_near_appt'];
}
