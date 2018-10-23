<?php

namespace App\Console\Commands;

use App\SendCode;
use App\User;
use Illuminate\Console\Command;
use App\BookingStatusModel;
use App\BookingOrderModel;
use App\TimeslotModel;
use App\PopupModel;
use App\AppointmentModel;
use App\ServiceModel;

class cronTimeSlot extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TimeSlot:autoInsert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Timeslot will be update automatically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $bookings = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                ->where('service_models.service_type', 'carservice')
                ->whereIn('booking_order_models.booking_status', [1, 4])
                ->selectRaw('
                    booking_order_models.id,
                    booking_order_models.user_id,
                    booking_order_models.updated_at,
                    booking_order_models.booking_status,
                    service_models.user_id as company_id,
                    CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                    ')
                ->get();

        foreach ($bookings as $value) {
            $user_phone = User::find($value->user_id);
            if ($value->booking_status == 1) { 

                $reminder = PopupModel::where('user_id', $value->company_id)->get()->first();
                $appointment = AppointmentModel::where('booking_order_id', '=', $value->id)->get()->first();   
                
                $booking_updated_at = date_create($value->updated_at);
                $booking_date_time = date_create($value->appt_datetime);
                $now_time = now();

                $interval = date_diff($booking_date_time, $booking_updated_at);               

                $diff_h = $interval->format('%h'); 
                $diff_d = $interval->format('%d'); 
                $diff = $diff_d *24 + $diff_h; 

                if ($diff < $reminder->noholdcountdown) {
                    $interval_1 = date_diff($now_time, $booking_updated_at);
                    $diff_1 = $interval_1->format('%i');

                    if ($diff_1 > 15) {
                        
                        $update_booking = BookingOrderModel::find($value->id);
                        $input['booking_status'] = "6";
                        $update_booking->update($input);
                        $appoint['user_id'] = $value->user_id;
                        $appoint['booking_order_id'] = $value->id;
                        $appoint['appointment_title'] = $reminder->home_top_2_title;
                        $appoint['appointment_body'] = $reminder->home_top_2_des;
                        $appoint['is_read'] = 1;                        
                        if ($reminder->sms_cancel_admin == 1) {
                            SendCode::sendSMS($user_phone->phone_number, $appoint['appointment_title'], $appoint['appointment_body']);
                        }
                        $appointment->update($appoint);
                    }
                } else {

                    $interval_2 = date_diff($now_time, $booking_updated_at);
                    $diff_2_h = $interval_2->format('%h');
                    $diff_2_d = $interval_2->format('%d');
                    $diff_2 = $diff_2_d *24 + $diff_2_h;
                    $display_time = ($reminder->holdinghours) - $diff_2;
                    
                    $appoint['user_id'] = $value->user_id;
                    $appoint['booking_order_id'] = $value->id;
                    $appoint['appointment_title'] = $reminder->home_top_1_title;
                    $appoint['appointment_body'] = str_replace('#HoldingHoursRemain', $display_time, $reminder->home_top_1_des);
                    $appoint['is_read'] = 1;
                    $appointment->update($appoint);

                    if ($diff_2 > $reminder->holdinghours) {
                        $update_booking = BookingOrderModel::find($value->id);
                        $input['booking_status'] = "6";
                        $update_booking->update($input);
                        $appoint['user_id'] = $value->user_id;
                        $appoint['booking_order_id'] = $value->id;
                        $appoint['appointment_title'] = $reminder->home_top_2_title;
                        $appoint['appointment_body'] = $reminder->home_top_2_des;
                        $appoint['is_read'] = 1;
                        if ($reminder->sms_cancel_admin == 1) {
                            SendCode::sendSMS($user_phone->phone_number, $appoint['appointment_title'], $appoint['appointment_body']);
                        }
                        $appointment->update($appoint);
                    }
                }
            }
        }
    }
}

// 