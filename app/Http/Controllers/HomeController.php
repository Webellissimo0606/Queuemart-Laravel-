<?php

namespace App\Http\Controllers;

use App\SendCode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\BookingOrderModel;
use App\ServiceModel;
use App\PackageModel;
use App\PopupModel;
use App\AppointmentModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        return view('home');
    }

    public function complete_profile(Request $request){
        $this->middleware('auth');
        $user=Auth::user();

        if(!is_object($user)){
            return redirect("login");
        }

        if($user->complete_profile=="1"){
           
            return redirect("/custom_bookings");
        }

        if($request->method()=="POST"){

            $res=Validator::make($request->all(), [
                "email"=>"required|unique:users,email",
                "name"=>"required",
                "nationality"=>"required",
                "ic"=>"required",
            ]);


            $user->update([
                "email"=>$request->get("email"),
                "name"=>$request->get("name"),
                "nationality"=>$request->get("nationality"),
                "ic"=>$request->get("ic"),
                "complete_profile"=>"1",
            ]);

            if (session()->has('order')) {
                $input['user_id'] = $user->id;
                $input['branch_id'] = session('order')['branch_id'];
                $input['service_id'] = session('order')['service_id'];
                $input['package_id'] = session('order')['package_id'];
                $package_like = PackageModel::find(session('order')['package_id']);
                if ($package_like->package_price == null || $package_like->package_price == 0) {
                    $input['booking_status'] = 4;
                } else {
                    $input['booking_status'] = 1;
                    if (session('order')['promo_code'] != '0') {
                        $promo_booking = BookingOrderModel::where('promo_code', session('order')['promo_code'])->get()->first();
                        if (isset($promo_booking)) {
                            if ($promo_booking->package_id == session('order')['package_id']) {
                                $input['promo_id'] = $promo_booking->id;
                            } else {
                                $input['promo_id'] = 0;
                            }
                        } else {
                            $input['promo_id'] = 0;
                        } 
                    }else {
                        $input['promo_id'] = 0;
                    } 
                }
                $input['promo_code'] = '0';
                $input['booking_day'] = session('order')['booking_day'];
                $input['booking_time'] = session('order')['booking_time'];
                $input['client_notice'] = session('order')['client_notice'];
                $input['manager_id'] = $user->id;
                $input['booked_time'] = now();
                $input['arrived_check'] = 0;
                $input['qrcode_field'] = bcrypt($user->id.str_random(40));
                $same_order = BookingOrderModel::where('user_id', '=', $user->id)
                                                ->where('service_id', '=', session('order')['service_id'])
                                                ->where('branch_id', '=', session('order')['branch_id'])
                                                ->where('package_id', '=', session('order')['package_id'])
                                                ->where('booking_status', '=', 1)
                                                ->where('booking_day', '=', session('order')['booking_day'])
                                                ->where('booking_time', '=', session('order')['booking_time'])
                                                ->get();
                if (count($same_order) > 0) {
                    session()->forget('order');
                    return redirect()->intended('/carservice/alert_popup');
                } else {
                    $new_booking = BookingOrderModel::create($input);
                    $company_id = ServiceModel::find(session('order')['service_id']);
                    $reminder = PopupModel::where('user_id', $company_id->user_id)->get()->first();
                    $holdinghours = $reminder->holdinghours;
                    $appointment = AppointmentModel::where('booking_order_id', '=', $new_booking->id)->get()->first();
                    $appoint['user_id'] = $new_booking->user_id;
                    $appoint['booking_order_id'] = $new_booking->id;
                    $appoint['appointment_title'] = $reminder->home_top_1_title;
                    $appoint['appointment_body'] = str_replace('#HoldingHoursRemain', $holdinghours, $reminder->home_top_1_des);
                    $appoint['is_read'] = 1;
                    if ($package_like->package_price != null || $package_like->package_price != 0) {
                        if (!$appointment) {
                            AppointmentModel::create($appoint);
                        } else {
                            $appointment->update($appoint);
                        }
                        if ($reminder->sms_payment == 1) {
                            SendCode::sendSMS($user->phone_number, $appoint['appointment_title'], $appoint['appointment_body']);
                        } 
                    }
                    session()->forget('order');
                }
                
            }
            return redirect("/custom_bookings");            
        }
        return view("front.client_detail",["user"=>$user]);
    }    
}
