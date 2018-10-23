<?php

namespace App\Http\Controllers;

use App\SendCode;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Jenssegers\Agent\Agent;

use Illuminate\Cookie\CookieJar;

use App\CompanyModel;
use App\ServiceModel;
use App\PackageModel;
use App\BranchModel;
use App\BranchServiceModel;

use App\Panel_news;
use App\Float_news;
use App\PopupModel;

use App\User;
use App\BookingOrderModel;
use App\BookingStatusModel;

use App\TimeslotModel;
use App\Holiday;
use App\AppointmentModel;
use App\QueueScreenModel;
use App\RatingStar;
use App\AdminRelationModel;
use App\Http\Controllers\PaymentGateway;
use App\CreditsModel;

use Cookie;


class Customers extends Controller
{
    //
    public function __construct(){
        // $this->middleware('auth');
    }

    public function index(){
        
        $companys = CompanyModel::select()->get();
        // $agent = new Agent();
        // $platform = $agent->isDesktop();
        // if (isset($platform)) {
        //     return redirect("/desktop");
        // }
        return view('front.index', compact('companys'));
    }

    public function indexCompany($key){
        $company_url = $key;
        $company = CompanyModel::where('company_url', $key)->get()->first();        
        if (!isset($company)) {
            return redirect("/");
        }
        $panel_news = Panel_news::where('user_id', $company->user_id)->get();
        $branches = BranchModel::where('user_id', $company->user_id)->get();
        $branch = BranchModel::where('user_id', $company->user_id)->get()->first();
        
        $services = BranchServiceModel::join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                        where('branch_service_models.branch_id',$branch->id)->
                                        selectRaw('
                                                service_models.*
                                            ')->
                                        orderBy('service_models.show_sequence', 'ASC')->
                                        get();
        

        $user_id = 0;
        $reminder = 0;
        $user=Auth::user();

        if(!is_object($user)){
            $user_id = 0;
            $reminder = 0;
        }
        else {
             $user_id = $user->id;
             $reminder = $user->reminder_whatsapp;
        }

        return view('front.home', compact('company', 'panel_news', 'branches', 'branch', 'services', 'user_id', 'company_url', 'reminder'));
    }

    public function desktop($company) {
        $company_url = $company;
        return view('desktop.index', compact('company_url'));
    }

    public function getAppointments(Request $request) {
        if ($request->ajax()) {
            $appointments = AppointmentModel::join('booking_order_models', 'booking_order_models.id', '=', 'appointment_models.booking_order_id')
                                            ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                            ->join('company_models', 'company_models.user_id', '=', 'service_models.user_id')
                                            ->where('appointment_models.user_id', $request->id)
                                            ->where('company_models.company_url', '=', $request->company_url)
                                            ->where('appointment_models.is_read', '=', 1)
                                            ->selectRaw('
                                                appointment_models.id,
                                                appointment_models.appointment_title,
                                                appointment_models.appointment_body,
                                                booking_order_models.booking_status
                                            ')
                                            ->get();
            $data['count'] = count($appointments);
            $data['messages'] = $appointments;
            return $data;
        }
    }

    public function reminderUser(Request $request) {
        if ($request->ajax()) {
            $user=Auth::user();
            if(is_object($user)){
                $input['reminder_whatsapp'] = 1;
                $user->update($input);
                return 'success';
            }
        }
    }

    public function deleteAppointments(Request $request) {
        if ($request->ajax()) {
            $input['is_read'] = 0;
            $appointment = AppointmentModel::find($request->id);
            $appointment->update($input);
            return 'success';
        }
    }

    public function branch($company_url, $key) {
        $company = CompanyModel::where('company_url', $company_url)->get()->first();
        $panel_news = Panel_news::where('user_id', $company->user_id)->get();
        $branches = BranchModel::where('user_id', $company->user_id)->get();
        $branch = BranchModel::find($key);
        
        $services = BranchServiceModel::join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                        where('branch_service_models.branch_id',$branch->id)->
                                        selectRaw('
                                                service_models.*
                                            ')->
                                        orderBy('service_models.show_sequence', 'ASC')->
                                        get();

        $user_id = 0;
        $reminder = 0;
        $user=Auth::user();

        if(!is_object($user)){
            $user_id = 0;
            $reminder = 0;
        }
        else {
             $user_id = $user->id;
             $reminder = $user->reminder_whatsapp;
        }

        return view('front.home', compact('company', 'panel_news', 'branches', 'branch', 'services', 'user_id', 'company_url', 'reminder'));
    }

    public function about($company_url, $key){
        $company = CompanyModel::where('company_url', $company_url)->get()->first();
        $branch = BranchModel::find($key);
        return view('front.about', compact('branch', 'company'));
    }

    public function book($company_url,$branch_id,$key){

        $company = CompanyModel::where('company_url', $company_url)->get()->first();
        $reminder = PopupModel::where('user_id', $company->user_id)->get()->first();
        $service = ServiceModel::find($key);
        $packages = PackageModel::where('service_id', $service->id)->get();

        $full_package_ids = array(0);
        if ($service->service_type == 'seminar') {
            foreach ($packages as $value) {
                if ($value->package_participants == null) {
                    $participants = 0;
                } else {
                    $participants = intval($value->package_participants);
                }
                $booking_count = BookingOrderModel::where('package_id', $value->id)->whereIn('booking_status', [1,4,7])->get()->count();
                if ($participants != 0 && $participants <= $booking_count) {
                    array_push($full_package_ids, $value->id);
                }
            }
        }

        $user=Auth::user(); 

        if(!is_object($user)){
            $notice = '';
        } else {
            $booking_last = BookingOrderModel::where('user_id', $user->id)->
                                                where('branch_id', $branch_id)->
                                                where('service_id', $service->id)->
                                                orderBy('updated_at', 'DESC')->
                                                get()->first();
            if (!isset($booking_last)) {
                $notice = '';
            } else {
                $notice = $booking_last->client_notice;
            }            
        }
        
        return view('front.book', compact('company', 'service', 'packages', 'branch_id', 'reminder', 'company_url', 'notice', 'full_package_ids'));
    }

    public function reschedule(CookieJar $cookieJar, $company_url, $booking_id){
        $user=Auth::user();        

        if(!is_object($user)){
            $cookieJar->queue(cookie('custom_url', $company_url, 0));
            return redirect("login");
        }
        if($user->complete_profile=="1"){
            $company = CompanyModel::where('company_url', $company_url)->get()->first();
            $reminder = PopupModel::where('user_id', $company->user_id)->get()->first();
            $booking = BookingOrderModel::find($booking_id);
            $service = ServiceModel::find($booking->service_id);
            $package = PackageModel::find($booking->package_id);
            
            return view('front.reschedule', compact('company', 'service', 'package', 'reminder', 'booking', 'company_url'));
        }
    }

    public function getCalendar(Request $request)
    {
        $page_data=[];

        $request_id=$request->get("request_id");
        if(empty($request_id)){
            die("you must select request like ");
        }

        $service_obj=ServiceModel::findOrFail($request_id);
        if($service_obj->service_participants==0){
            $service_obj->service_participants=1;
        }
        $page_data["service_obj"]=$service_obj;

        $package_obj=PackageModel::findOrFail($request->package_id);
        if($package_obj->package_participants==0){
            $package_obj->package_participants=1;
        }
        $page_data["package_obj"]=$package_obj;


        $page_data["disable_prev_button"]="";
        $page_data["disable_next_button"]="";

        $selected_month=$request->get("selected_month","");
        $selected_year=$request->get("selected_year","");

        if($selected_month!=""&&$selected_month==13){
            $selected_month=1;
            $selected_year=$selected_year+1;
        }

        if($selected_month!=""&&$selected_month==0){
            $selected_month=12;
            $selected_year=$selected_year-1;
        }


        $current_month=date("m");
        $current_year=date("Y");

        if(
            $current_month==$selected_month&&
            $current_year==$selected_year
        ){
            $page_data["disable_prev_button"]="disabled";
        }

        if($selected_year>$current_year){
            $current_year=$selected_year;
            $current_month=$selected_month;
        }

        if($selected_month > $current_month){
            $current_month=$selected_month;
        }



        //get current month days
        $number_of_days=cal_days_in_month(CAL_GREGORIAN,$current_month,$current_year);


        //get all timeslot for the service
        $timeslot_for_service=TimeslotModel::
        where("service_id",$request_id)->
        get()->first();

        if(!is_object($timeslot_for_service)){
            die("there is no timeslot for this service");
        }

        if($timeslot_for_service->calendar!=0){

            $diff_in_month=
                Carbon::createFromTimestamp(
                    strtotime(date("Y")."-".date("m")."-1")
                )->
                diff(Carbon::createFromTimestamp(
                strtotime("$current_year-$current_month-1")
            ))->days;

            $diff_in_month=(int)($diff_in_month/30);
            $diff_in_month=$diff_in_month+1;

            if($timeslot_for_service->calendar-$diff_in_month==0){
                $page_data["disable_next_button"]="disabled";
            }

            if($diff_in_month>($timeslot_for_service->calendar+1)){
                $current_month=$request->get("selected_month","")-1;
                if ($current_month==12) {
                    $current_year = $current_year-1;
                }
            }
        }

        //get all bookings
        $all_bookings=BookingOrderModel::
            where("service_id",$request_id)->
            where("branch_id",$request->branch_id)->
            whereIn("booking_status",[1, 3, 4, 7])->
            whereMonth("booking_day",$current_month)->
            whereYear("booking_day",$current_year)->
            get()->groupBy("booking_day");

        $all_bookings_p=BookingOrderModel::
            where("package_id",$request->package_id)->
            where("branch_id",$request->branch_id)->
            whereIn("booking_status",[1, 3, 4, 7])->
            whereMonth("booking_day",$current_month)->
            whereYear("booking_day",$current_year)->
            get()->groupBy("booking_day");


        //get all holidays
        $all_holidays=Holiday::
            join('company_models', 'company_models.user_id', '=', 'holidays.user_id')->
            where('company_models.company_url', $request->company_url)->
            // whereMonth("holiday_date",$current_month)->
            // whereYear("holiday_date",$current_year)->
            get()->groupBy("holiday_date");

        $page_data["request_id"]=$request_id;
        $page_data["current_year"]=$current_year;
        $page_data["current_month"]=$current_month;
        $page_data["number_of_days"]=$number_of_days;

        $page_data["timeslot_for_service"]=$timeslot_for_service;
        $page_data["all_bookings"]=$all_bookings->all();
        $page_data["all_bookings_p"]=$all_bookings_p->all();
        $page_data["all_holidays"]=$all_holidays->all();

        return view('carservice.calendar',$page_data);
    }

    public function bookings(CookieJar $cookieJar, $company_url){

        $user=Auth::user();        

        if(!is_object($user)){
            $cookieJar->queue(cookie('custom_url', $company_url, 0));
            return redirect("login");
        }

        if($user->complete_profile=="1"){
            $cookieJar->queue(cookie('custom_url', $company_url, 0));
            $total_bookings = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                                    ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                                    ->join('company_models', 'company_models.user_id', '=', 'service_models.user_id')
                                                    ->where('booking_order_models.user_id', '=', $user->id)
                                                    ->where('company_models.company_url', '=', $company_url)
                                                    ->whereIn('booking_order_models.booking_status', [1, 4, 7])
                                                    ->get();
            $total_count = count($total_bookings);

            $company = CompanyModel::where('company_url', $company_url)->get()->first(); 

            $branch_first = BranchModel::where('user_id', $company->user_id)->get()->first(); 

            $floating_news = Float_news::where('user_id', $company->user_id)->where('float_active', 1)->get()->first();

            $bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('company_models', 'company_models.user_id', '=', 'service_models.user_id')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'service_models.id')
                                     ->where('booking_order_models.user_id', '=', $user->id)
                                     ->where('company_models.company_url', '=', $company_url)
                                     ->whereIn('booking_order_models.booking_status', [1, 4, 7])
                                     ->selectRaw('
                                        booking_order_models.id,
                                        booking_order_models.service_id,
                                        booking_order_models.created_at,
                                        booking_order_models.arrived_check,
                                        booking_order_models.qrcode_field,                                        
                                        users.name,
                                        branch_models.id as branch_id,
                                        branch_models.branch_name,
                                        branch_models.branch_address,
                                        branch_models.branch_tel_num,
                                        service_models.service_name,
                                        service_models.service_image,
                                        service_models.service_type,
                                        package_models.package_name,
                                        package_models.package_unit,
                                        package_models.package_price,
                                        timeslot_models.reschedule_allow,
                                        timeslot_models.duration_show,
                                        timeslot_models.service_duration,
                                        timeslot_models.start_time,
                                        booking_order_models.updated_at,
                                        booking_order_models.booking_status,
                                        booking_order_models.client_notice,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->orderBy('booking_order_models.updated_at', 'DESC')
                                     ->get();

            $past_bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('company_models', 'company_models.user_id', '=', 'service_models.user_id')
                                     ->where('booking_order_models.user_id', '=', $user->id)
                                     ->where('company_models.company_url', '=', $company_url)
                                     ->whereIn('booking_order_models.booking_status', [2, 5, 6])
                                     ->selectRaw('
                                        booking_order_models.id,
                                        branch_models.id as branch_id,
                                        service_models.id as service_id,
                                        booking_order_models.created_at,
                                        users.name,
                                        branch_models.branch_name,
                                        branch_models.branch_address,
                                        branch_models.branch_tel_num,
                                        service_models.service_name,
                                        service_models.service_image,
                                        package_models.package_name,
                                        package_models.package_unit,
                                        package_models.package_price,
                                        booking_order_models.updated_at,
                                        booking_order_models.booking_status,
                                        booking_order_models.rating_check,
                                        booking_order_models.promo_code,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->orderBy('booking_order_models.updated_at', 'DESC')
                                     ->limit(10)
                                     ->get();

            $user_info = User::find($user->id);

            return view('front.bookings', compact('company', 'user', 'bookings', 'total_count', 'past_bookings', 'floating_news', 'user_info', 'company_url', 'branch_first'));
        }  else {
            return redirect()->intended('/complete_profile');
        }    
    }

    public function rating(CookieJar $cookieJar, $company_url, $booking_id) {
        $user=Auth::user();        

        if(!is_object($user)){
            $cookieJar->queue(cookie('custom_url', $company_url, 0));
            return redirect("login");
        }

        if($user->complete_profile=="1"){

            $booking_id = $booking_id;

            $company_url = $company_url;

            $company = CompanyModel::where('company_url', $company_url)->get()->first();

            $booking = BookingOrderModel::where('user_id', $user->id)->where('id', $booking_id)->where('booking_status', 2)->get()->first();

            if (!isset($booking)) {
                return redirect()->intended('/'.$company_url.'/bookings/');
            }

            $user_info = User::find($user->id);

            return view('front.rating', compact('company', 'company_url' ,'user_info', 'booking_id'));
        }   else {
            return redirect()->intended('/complete_profile');
        } 
    }

    public function ratingStar(Request $request) {
        $user=Auth::user();
        if ($request->ajax()) {
            $booking = BookingOrderModel::find($request->booking_id);
            $input['rating_check'] = 1;
            $booking->update($input);
            $rating['user_id'] = $user->id;
            $rating['booking_id'] = $booking->id;
            $rating['rating_star'] = $request->rating_star;
            $rating['rating_text'] = $request->rating_text;
            RatingStar::create($rating);
            return 'success';
        }
    }

    public function credits(CookieJar $cookieJar, $company_url) {
        $user=Auth::user();        

        if(!is_object($user)){
            $cookieJar->queue(cookie('custom_url', $company_url, 0));
            return redirect("login");
        }

        if($user->complete_profile=="1"){

            $company = CompanyModel::where('company_url', $company_url)->get()->first(); 

            $branch_first = BranchModel::where('user_id', $company->user_id)->get()->first(); 

            $floating_news = Float_news::where('user_id', $company->user_id)->where('float_active', 1)->get()->first();

            $total_bookings = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                                    ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                                    ->join('company_models', 'company_models.user_id', '=', 'service_models.user_id')
                                                    ->where('booking_order_models.user_id', '=', $user->id)
                                                    ->where('company_models.company_url', '=', $company_url)
                                                    ->whereIn('booking_order_models.booking_status', [1, 4, 7])
                                                    ->get();
            $total_count = count($total_bookings);

            $user_info = User::find($user->id);

            $credits = CreditsModel::join('service_models', 'service_models.id', '=', 'credits_models.service_id')->
                                    join('package_models' , 'package_models.id', '=', 'credits_models.package_id')->
                                    join('users as me' , 'me.id', '=', 'credits_models.user_id')->
                                    join('users as otheruser' , 'otheruser.id', '=', 'credits_models.other_id')->
                                    where('credits_models.user_id', $user->id)->
                                    selectRaw('
                                            credits_models.*,
                                            service_models.service_name,
                                            package_models.package_name,
                                            me.name as myname,
                                            otheruser.name as othername
                                        ')->
                                    orderBy('credits_models.created_at', 'DESC')->limit(10)->get();


            return view('front.credits', compact('company', 'user', 'total_count', 'floating_news', 'user_info', 'company_url', 'branch_first', 'credits'));
        }   else {
            return redirect()->intended('/complete_profile');
        } 
    }

    public function order(CookieJar $cookieJar, $company_url, Request $request){ 
        if ($request->ajax()) {
            $input = $request->all();
            $user=Auth::user();

            $service_participants = ServiceModel::select('service_participants')->where('id', $request->service_id)->get();
            $service_participants_count = intval($service_participants[0]->service_participants);
            if(!is_object($user)){
                session(['order' => array(
                    'branch_id'=>$request->branch_id,
                    'service_id'=>$request->service_id,
                    'package_id'=>$request->package_id,
                    'booking_status'=>1,                    
                    'booking_day'=>$request->booking_day,
                    'booking_time'=>$request->booking_time,
                    'client_notice'=>$request->client_notice,
                    'promo_code'=>$request->promo_code
                )]);
                $cookieJar->queue(cookie('custom_url', $company_url, 0));
                return 'login';
            }

            if($user->complete_profile=="1"){
                $same_order = BookingOrderModel::where('user_id', '=', $user->id)
                                                ->where('service_id', '=', $request->service_id)
                                                ->where('branch_id', '=', $request->branch_id)
                                                ->where('package_id', '=', $request->package_id)
                                                ->where('booking_day', '=', $request->booking_day)
                                                ->where('booking_time', '=', $request->booking_time)
                                                ->get();
                if (count($same_order)>0) {
                    return 'max';
                } else {

                    $package_like = PackageModel::find($request->package_id);
                    $company = CompanyModel::where('company_url', $company_url)->get()->first();
                    $input['user_id'] = $user->id;
                    $input['manager_id'] = $user->id;
                    if ($package_like->package_price == null || $package_like->package_price == 0) {
                        $input['booking_status'] = 4;
                    } else {
                        $input['booking_status'] = 1;
                        if ($request->promo_code != '0') {
                            $promo_booking = BookingOrderModel::where('promo_code', $request->promo_code)->get()->first();
                            if (isset($promo_booking)) {
                                if ($promo_booking->package_id == $request->package_id) {
                                    $input['promo_id'] = $promo_booking->id;
                                } else {
                                    $input['promo_id'] = 0;
                                }
                            } else {
                                $input['promo_id'] = 0;
                            }
                        } else {
                            $input['promo_id'] = 0;
                        }                     
                    }
                    $input['promo_code'] = '0';
                    $input['arrived_check'] = 0;
                    $input['qrcode_field'] = bcrypt($user->id.str_random(40));
                    $input['booked_time'] = now();
                    $new_booking = BookingOrderModel::create($input);
                    $reminder = PopupModel::where('user_id', $company->user_id)->get()->first();
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
                    return 'bookings';
                }
                
            } else {
                session(['order' => array(
                    'branch_id'=>$request->branch_id,
                    'service_id'=>$request->service_id,
                    'package_id'=>$request->package_id,
                    'booking_status'=>1,                    
                    'booking_day'=>$request->booking_day,
                    'booking_time'=>$request->booking_time,
                    'client_notice'=>$request->client_notice
                )]);
                $cookieJar->queue(cookie('custom_url', $company_url, 0));
                return 'complete_profile';
            } 
        }     
    }

    public function reschedule_order($company_url, Request $request){ 
        if ($request->ajax()) {

            $update_booking = BookingOrderModel::find($request->booking_id);
            $input['booking_day'] = $request->booking_day;
            $input['booking_time'] = $request->booking_time;
            $input['client_notice'] = $request->client_notice;
            $input['arrived_check'] = 0;
            $input['reschedule_check'] = 1;
            $input['reschedule_time'] = now();
            $update_booking->update($input);
                        
            return 'bookings';
                
        }     
    }

    public function creditsDraw($user_id, $credit_amount, $package_unit) {
        $order_user = User::find($user_id);
        if ($package_unit == 'RM') {
            $rm_order['credits_rm'] = intval($order_user->credits_rm)-intval($credit_amount);
            $order_user->update($rm_order);
        } elseif ($package_unit == 'SGD') {
            $sgd_order['credits_sgd'] = intval($order_user->credits_sgd)-intval($credit_amount);
            $order_user->update($sgd_order);
        }
        return;
    }

    public function getPayAmount($user_id, $package_unit, $package_price, $credit_amount) {
        $result = 0;
        $order_user = User::find($user_id);
        if ($package_unit == 'RM') {
            if (floatval($order_user->credits_rm) >= floatval($credit_amount)) {
                $result = floatval($package_price)-floatval($credit_amount);
            } else {
                $result = floatval($package_price);
            }
        } elseif ($package_unit == 'SGD') {
            if (floatval($order_user->credits_sgd) >= floatval($credit_amount)) {
                $result = floatval($package_price)-floatval($credit_amount);
            } else {
                $result = floatval($package_price);
            }
        }

        return $result;
    }

    public function booking_payment(Request $request){
        /*
     *  Credit Card(MYR): 2
     *  Maybank2u       : 6
     *  Alliance Online : 8
     *  AmOnline        : 10
     *  RHB Online      : 14
     *  Hong Leong Bank : 15
     *  FPX             : 16
     *  CIMB Clicks     : 20
     *  WebCash         : 22
     *  Celcom AirCash  : 100
     *  Bank Rakyat     : 102
     *  Affin Online    : 103
     *  PayPal (MYR)    : 48
     */
        if ($request->ajax()) {
            $booking = BookingOrderModel::join('users', 'users.id', '=', 'booking_order_models.user_id')->
                                        join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')->
                                        where('booking_order_models.id', $request->id)->
                                        selectRaw('
                                                booking_order_models.id,
                                                booking_order_models.user_id,
                                                users.name,
                                                users.email,
                                                users.phone_number,
                                                package_models.package_unit,
                                                package_models.package_price,
                                                package_models.credit_amount
                                            ')->
                                        get()->first();

            if ($booking->package_unit == 'RM') {
                $currency_unit = 'MYR';
                $payment_id = '16';
            } else if ($booking->package_unit == 'SGD') {
                $currency_unit = 'SGD';
                $payment_id = '38';
            }

            $booking_price = $this->getPayAmount($booking->user_id, $booking->package_unit, $booking->package_price, $booking->credit_amount);
            /*
                $booking_price is genenerated price value without decimal.
                You need to find ways to generate a standard currency value with two decimal
                E.g: 15.00
            */
            $booking_price  =   (int)$booking_price.".00";

            $paymentGateway =   new PaymentGateway();
            //$paymentGateway->setMerchantKey('3eO46CjOg4');
            //$merchantCode = $paymentGateway->setMerchantCode('M13745');
            $refNo = $paymentGateway->setRefNo($booking->id);//Your order ID. Temporary use rand() to generate order ID. Once payment is success/failed, you can retrieve it by using $request->input("RefNo");
            $amount = $paymentGateway->setAmount($booking_price);//$booking_price
            $currency = $paymentGateway->setCurrency($currency_unit);
            $signatureType  =   $paymentGateway->setSignatureType("SHA256");
            $signature = $paymentGateway->compileSignature();
            return view("payment-gateway.request", [
                "merchantCode"  =>  $paymentGateway->merchantCode,
                "paymentId"  =>  $payment_id,//Testing is only allowed to use credit card option. The rest will show fail message
                "refNo"  =>  $refNo,//order id
                "amount"  =>  $amount,//amount
                "currency"  =>  $currency,
                "desc"  =>  "Payment for order : #".$refNo,
                "name"  =>  $booking->name,
                "email" => $booking->email,
                "contact"   =>  $booking->phone_number,
                "signatureType" =>  $signatureType,
                "signature" =>  $signature,
                "requestUrl"    =>  $paymentGateway->requestUrl,// send to ipay88 payment page. POST method
                "responseUrl" => $paymentGateway->respondUrl,// send back from ipay88 to queuemart.me with payment success or failure message
                "backendUrl" => $paymentGateway->backendUrl,// backend process order update. Put order update code in this function
            ]);
        }
    }

    //this function for ipay server to update the order
    public function backend_code(Request $request){
       //Update your order status in here
       //This function is only for order update!!! No need to show or render any view.
        $paymentGateway =   new PaymentGateway();
        //$paymentGateway->setMerchantKey('3eO46CjOg4');
        $paymentGateway->setMerchantCode($request->input("MerchantCode"));
        $paymentGateway->setRefNo($request->input("RefNo"));//order id
        $paymentGateway->setAmount($request->input("Amount"));
        $paymentGateway->setCurrency($request->input("Currency"));
        $paymentGateway->setPaymentId($request->input("PaymentId"));
        $paymentGateway->setPaymentStatus($request->input("Status"));
        if($paymentGateway->verifySignature($request->input("Signature")) === true){
            if($request->input("Status") == "1"){ 
                $order = BookingOrderModel::find($request->input("RefNo"));
                $input['booking_status'] = 4;
                $input['paid_time'] = now();
                $input['paid_price'] = $request->input("Amount");
                $relation_order = BookingOrderModel::join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')->
                                                    where('booking_order_models.id', $order->id)->
                                                    selectRaw('
                                                            booking_order_models.user_id,
                                                            package_models.package_unit,
                                                            package_models.package_price,
                                                            package_models.credit_amount
                                                        ')->get()->first();
                if ($relation_order->package_price > $request->pay_price) {
                    $this->creditsDraw($relation_order->user_id, $relation_order->credit_amount, $relation_order->package_unit);
                }
                $order->update($input);
                echo "RECEIVEOK";
            }
        }
    }

    //this function for ipay to return the user from payment page to your website
    public function response_code(Request $request){
        //this page is to indicate payment success or failure to the user.
        $paymentGateway =   new PaymentGateway();
        // dd($request->all());
        // $paymentGateway->setMerchantKey('3eO46CjOg4');
        $paymentGateway->setMerchantCode($request->input("MerchantCode"));
        $paymentGateway->setRefNo($request->input("RefNo"));//order id
        $paymentGateway->setAmount($request->input("Amount"));
        $paymentGateway->setCurrency($request->input("Currency"));
        $paymentGateway->setPaymentId($request->input("PaymentId"));
        $paymentGateway->setPaymentStatus($request->input("Status"));
        if($paymentGateway->verifySignature($request->input("Signature")) === true){
            if($request->input("Status") == "1"){          
                return redirect(Cookie::get('custom_url').'/bookings');
            }else{
                return redirect(Cookie::get('custom_url').'/bookings');
            }
        }else{
           return redirect(Cookie::get('custom_url').'/bookings');
        }
    }

    public function booking_cancel(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();
            $booking_cancel = BookingOrderModel::find($request->id);
            $input['booking_status'] = 5;
            $booking_cancel->update($input);
            $service = ServiceModel::find($booking_cancel->service_id);
            $reminder = PopupModel::where('user_id', $service->user_id)->get()->first();

            $appointment = AppointmentModel::where('booking_order_id', '=', $booking_cancel->id)->get()->first();
            $appoint['user_id'] = $booking_cancel->user_id;
            $appoint['booking_order_id'] = $booking_cancel->id;
            $appoint['appointment_title'] = $reminder->home_top_4_title;
            $appoint['appointment_body'] = $reminder->home_top_4_des;
            $appoint['is_read'] = 1;
            if (!$appointment) {
                AppointmentModel::create($appoint);
            } else {
                $appointment->update($appoint);
            }  
            $user_phone = User::find($booking_cancel->user_id);
            if ($reminder->sms_cancel_client == 1) {
                SendCode::sendSMS($user_phone->phone_number, $appoint['appointment_title'], $appoint['appointment_body']);
            }          
            return 'success';
        }
    }

    public function verify_code(){

        return view('front.verify-code');
    }

    public function verify_phone(Request $request){

        $user=Auth::user();

        $page_data=[];

        if(!is_object($user)){
            return $this->about("404");
        }

        //if user is activated so this page is useless for him
        if(!empty($user->activated)){
            redirect('/')->send();
        }

        $page_data["errors"]=[];

        if($request->method()=="POST"){
            $data = $request->all();
            $data['phone_number'] = $data['phone_number_1'].$data['phone_number_2'];
            $res=Validator::make($data, [
                "phone_number"=>"required|min:7|max:15|unique:users,phone_number"
            ]);


            if($res->errors()->count()==0){
                $user->phone_number=$data['phone_number'];
                $user->code = SendCode::sendCode($user->phone_number);
                $user->save();

                return \redirect("/verify?phone=$user->phone_number")->send();
            }

            $page_data["errors"]=$res->errors()->all();
        }


        return view('front.verify-phone',$page_data);
    }       

    public function client_detail(){

        return view('front.client_detail');
    } 

// admin
    public function client_list($key){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }

        if ($user_obj->role_id == 4) {
            if ($user_obj->id != $key) {
                return redirect()->intended('admin');
            }
        }

        $check_user = User::where('id', $key)->whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get()->first();
        if (!isset($check_user)) {
            return redirect()->intended('admin');
        }

        if ($user_obj->role_id == 3) {
            $check_support = CompanyModel::where('support_id', $user_obj->id)->where('user_id', $key)->get()->first();
            if(!isset($check_support)) {
                return redirect()->intended('admin');
            }
        }

        if ($user_obj->role_id == 6) {
            return redirect()->intended('admin');
        }

        if ($user_obj->role_id == 5) {
        $employee = AdminRelationModel::join('company_models', 'company_models.id' , '=', 'admin_relation_models.company_id')->
                                        where('admin_relation_models.user_id', $user_obj->id)->
                                        where('company_models.user_id', $key)->
                                        get()->first();            
            if (!isset($employee)) {
                return redirect()->intended('admin');
            }
        }

        $user_id = $key;
        $users = CompanyModel::select()->get();
        $user_single = User::find($user_id);

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();  

        $bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->where('branch_models.user_id', '=', $key)
                                     ->selectRaw('
                                        users.id
                                        ')
                                     ->groupBy('users.id')
                                     ->get();
        $customers = array();
        foreach ($bookings as $value) {

            $customer = User::where('id', $value->id)
                            ->get()->first();
            $last_visit = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', '=', $key)
                                     ->where('booking_order_models.user_id', '=', $customer->id)                                     
                                     ->orderBy('booking_order_models.updated_at', 'DESC')
                                     ->selectRaw('
                                        booking_order_models.updated_at
                                        ')
                                     ->get()->first();
            $numbers_booking = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', '=', $key)
                                     ->where('booking_order_models.user_id', '=', $customer->id)                                     
                                     ->get()->count();

            $total_revenue = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', '=', $key)
                                     ->where('booking_order_models.user_id', '=', $customer->id)                                     
                                     ->where('booking_order_models.booking_status', '=', 2)                                     
                                     ->sum('paid_price');

            $data['customer'] = $customer;
            $data['last_visit'] = $last_visit->updated_at;
            $data['numbers_booking'] = $numbers_booking;
            $data['total_revenue'] = $total_revenue;
            array_push($customers, $data);
        }
        return view('system.customer.client-list', compact('customers', 'user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car'));
    }

    public function bookingHistory(Request $request){
        $history = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->where('booking_order_models.user_id', $request->id)
                                     ->where('service_models.user_id', $request->company_id)
                                     ->selectRaw('
                                        booking_order_models.id,
                                        booking_order_models.updated_at,
                                        users.name,
                                        branch_models.branch_name,
                                        service_models.service_name,
                                        package_models.package_name,
                                        booking_order_models.updated_at,
                                        booking_status_models.booking_status,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->orderBy('booking_order_models.updated_at', 'DESC')
                                     ->get();  
        return view('system.customer.booking_history_modal', compact('history'));
    }

    public function editCustomer(Request $request){
        $user = User::find($request->id);
        return view('system.customer.customer_edit_modal', compact('user'));
    }

    public function updateCustomer(Request $request){
        if ($request->ajax()) {
            $exist_user = User::where('phone_number', $request->phone_number)->where('id', '!=', $request->id)->get()->first();
            if (isset($exist_user)) {
                $data['same_user'] = 'This phone number is aleady exist';
                return $data;
            }
            $input = $request->all();
            $old_user = User::find($request->id);
            $old_user->update($input);
            return response($old_user);
        }
    }

    public function booking_log($key){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }

        if ($user_obj->role_id == 4) {
            if ($user_obj->id != $key) {
                return redirect()->intended('admin');
            }
        }

        $check_user = User::where('id', $key)->whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get()->first();
        if (!isset($check_user)) {
            return redirect()->intended('admin');
        }

        if ($user_obj->role_id == 3) {
            $check_support = CompanyModel::where('support_id', $user_obj->id)->where('user_id', $key)->get()->first();
            if(!isset($check_support)) {
                return redirect()->intended('admin');
            }
        }

        if ($user_obj->role_id == 6) {
            return redirect()->intended('admin');
        }

        if ($user_obj->role_id == 5) {
        $employee = AdminRelationModel::join('company_models', 'company_models.id' , '=', 'admin_relation_models.company_id')->
                                        where('admin_relation_models.user_id', $user_obj->id)->
                                        where('company_models.user_id', $key)->
                                        get()->first();            
            if (!isset($employee)) {
                return redirect()->intended('admin');
            }
        }

        $user_id = $key;
        $users = User::whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get();
        $user_single = User::find($user_id);

        $bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users as t1', 't1.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('users as t2', 't2.id', '=', 'booking_order_models.manager_id')
                                     ->where('service_models.user_id', '=', $key)
                                     ->selectRaw('
                                        booking_order_models.id,
                                        booking_order_models.updated_at,
                                        t1.name,
                                        t2.name as manager_name,
                                        branch_models.branch_name,
                                        service_models.service_name,
                                        package_models.package_name,
                                        booking_order_models.updated_at,
                                        booking_status_models.booking_status,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->get();
            $booked = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.booking_status', '=', '1')
                                     ->where('service_models.user_id', '=', $key)
                                     ->get();
            $booked_count = count($booked);

            $bookedNoFee = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.booking_status', '=', '3')
                                     ->where('service_models.user_id', '=', $key)
                                     ->get();
            $bookedNoFee_count = count($bookedNoFee);

            $paid = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.booking_status', '=', '4')
                                     ->where('service_models.user_id', '=', $key)
                                     ->get();
            $paid_count = count($paid);

            $arrived = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.booking_status', '=', '7')
                                     ->where('service_models.user_id', '=', $key)
                                     ->get();
            $arrived_count = count($arrived);

            $cancelledbyadmin = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.booking_status', '=', '6')
                                     ->where('service_models.user_id', '=', $key)
                                     ->get();
            $cancelledbyadmin_count = count($cancelledbyadmin);

            $cancelledbyclient = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                         ->where('booking_order_models.booking_status', '=', '5')
                                         ->where('service_models.user_id', '=', $key)
                                         ->get();
            $cancelledbyclient_count = count($cancelledbyclient);    
            $completedOrder = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                         ->where('booking_order_models.booking_status', '=', '2')
                                         ->where('service_models.user_id', '=', $key)
                                         ->get();
            $completedOrder_count = count($completedOrder);  

            $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first(); 
               
            $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();        
        return view('system.customer.booking-log', compact('bookings', 'booked_count', 'bookedNoFee_count', 'paid_count', 'arrived_count', 'cancelledbyadmin_count', 'cancelledbyclient_count', 'completedOrder_count', 'user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car'));
    }

    public function referral($key){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }

        if ($user_obj->role_id == 4) {
            if ($user_obj->id != $key) {
                return redirect()->intended('admin');
            }
        }

        $check_user = User::where('id', $key)->whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get()->first();
        if (!isset($check_user)) {
            return redirect()->intended('admin');
        }

        if ($user_obj->role_id == 3) {
            $check_support = CompanyModel::where('support_id', $user_obj->id)->where('user_id', $key)->get()->first();
            if(!isset($check_support)) {
                return redirect()->intended('admin');
            }
        }

        if ($user_obj->role_id == 5 || $user_obj->role_id == 6) {
            return redirect()->intended('admin');
        }

        $user_id = $key;
        $users = CompanyModel::select()->get();
        $user_single = User::find($user_id);

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();   

        return view('system.customer.referral', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car'));
    }

    
    public function admin_role($key){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id!="2"){    
            return redirect()->intended('admin/');
        }

        $check_user = User::where('id', $key)->whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get()->first();
        if (!isset($check_user)) {
            return redirect()->intended('admin');
        }

        $user_id = $key;
        $users = CompanyModel::select()->get();
        $user_single = User::find($user_id);

        $users_all = User::where('id','!=', $user_obj->id)->whereIn('role_id', [2,3,4,5,6])->orderBy('role_id', 'ASC')->get();

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();  
        $companies = CompanyModel::select()->get(); 

        return view('system.customer.admin-role', compact('user_obj', 'users_all', 'users', 'user_id', 'user_single', 'branch_services', 'branch_services_car', 'companies'));
    }

    public function member_management($key){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }

        if ($user_obj->role_id == 4) {
            if ($user_obj->id != $key) {
                return redirect()->intended('admin');
            }
        }

        $check_user = User::where('id', $key)->whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get()->first();
        if (!isset($check_user)) {
            return redirect()->intended('admin');
        }

        if ($user_obj->role_id == 3) {
            $check_support = CompanyModel::where('support_id', $user_obj->id)->where('user_id', $key)->get()->first();
            if(!isset($check_support)) {
                return redirect()->intended('admin');
            }
        }

        if ($user_obj->role_id == 5 || $user_obj->role_id == 6) {        
            return redirect()->intended('admin');
        }

        $user_id = $key;
        $users = CompanyModel::select()->get();
        $user_single = User::find($user_id);

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first(); 

        $branches = BranchModel::where('user_id', $key)->get();

        $services = BranchServiceModel::join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                        where('branch_id', $branches[0]->id)->
                                        selectRaw('service_models.*')->get();

        $company = CompanyModel::where('user_id', $key)->get()->first();

        $users_all = AdminRelationModel::join('users', 'users.id', '=', 'admin_relation_models.user_id')->
                                        where('admin_relation_models.company_id', $company->id)->
                                        selectRaw('users.*')->get();
        
        return view('system.customer.member_management', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car', 'branches', 'services', 'users_all'));
    }

    public function qrcode_scan($key){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }

        if ($user_obj->role_id == 4) {
            if ($user_obj->id != $key) {
                return redirect()->intended('admin');
            }
        }

        $check_user = User::where('id', $key)->whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get()->first();
        if (!isset($check_user)) {
            return redirect()->intended('admin');
        }

        if ($user_obj->role_id == 3) {
            $check_support = CompanyModel::where('support_id', $user_obj->id)->where('user_id', $key)->get()->first();
            if(!isset($check_support)) {
                return redirect()->intended('admin');
            }
        }

        if ($user_obj->role_id == 5 || $user_obj->role_id == 6) {
        $employee = AdminRelationModel::join('company_models', 'company_models.id' , '=', 'admin_relation_models.company_id')->
                                        where('admin_relation_models.user_id', $user_obj->id)->
                                        where('company_models.user_id', $key)->
                                        get()->first();            
            if (!isset($employee)) {
                return redirect()->intended('admin');
            }
        }

        $user_id = $key;
        $users = CompanyModel::select()->get();
        $user_single = User::find($user_id);

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first(); 

        $branches = BranchModel::where('user_id', $key)->get(); 

        $branch_service_queuescreen = BranchServiceModel::join('branch_models', 'branch_models.id', '=', 'branch_service_models.branch_id')->
                                            join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                            where('service_models.user_id', $key)->
                                            where('service_models.service_type', 'seminar')
                                            ->selectRaw('
                                                branch_service_models.id,
                                                service_models.service_name,
                                                branch_models.branch_name
                                                ')
                                            ->get();
        if ($user_obj->role_id == 5) {
            $employee_branch = AdminRelationModel::where('admin_relation_models.user_id', $user_obj->id)->
                                        get()->first();

            $branches = BranchModel::where('id', $employee_branch->branch_id)->get(); 
            $branch_service_queuescreen = BranchServiceModel::join('branch_models', 'branch_models.id', '=', 'branch_service_models.branch_id')->
                                            join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                            where('service_models.user_id', $key)->
                                            where('branch_models.id', $employee_branch->branch_id)->
                                            where('service_models.service_type', 'seminar')
                                            ->selectRaw('
                                                branch_service_models.id,
                                                service_models.service_name,
                                                branch_models.branch_name
                                                ')
                                            ->get();
        }

        if ($user_obj->role_id == 6) {
            $employee_branch = AdminRelationModel::where('admin_relation_models.user_id', $user_obj->id)->
                                        get()->first();

            $branches = BranchModel::where('id', $employee_branch->branch_id)->get(); 
            $relation_service_ids = explode(',', $employee_branch->service_id);
            $branch_service_queuescreen = BranchServiceModel::join('branch_models', 'branch_models.id', '=', 'branch_service_models.branch_id')->
                                            join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                            where('service_models.user_id', $key)->
                                            whereIn('service_models.id', $relation_service_ids)->
                                            where('service_models.service_type', 'seminar')
                                            ->selectRaw('
                                                branch_service_models.id,
                                                service_models.service_name,
                                                branch_models.branch_name
                                                ')
                                            ->get();
        }

        $queuescreen = QueueScreenModel::where('user_id', $key)->get()->first();
        if (!isset($queuescreen)) {
            $input['user_id'] = $user_id;
            $input['qr_screen_video'] = '/upload/queuescreen_video/default.mp4';
            $input['qr_screen_news'] = "Please enter floating news";
            $input['display_client'] = 0;
            $queuescreen = QueueScreenModel::create($input);
        }
        return view('system.customer.qrcode_scan_management', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car', 'branches', 'branch_service_queuescreen', 'queuescreen'));
    }

    public function queuescreenManagement(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();
            $input['display_client'] = 0;
            if ($request->display_client == null) {
                $input['display_client'] = 0;
            } else {
                $input['display_client'] = 1;
            }
            
            $queuescreen = QueueScreenModel::where('user_id', $request->user_id)->get()->first();
            if (isset($queuescreen)) {
                
                $input['qr_screen_video'] = $queuescreen->qr_screen_video;

                if ($request->hasFile('qr_screen_video')){                
                    $input['qr_screen_video'] = '/upload/queuescreen_video/'.time().'.'.$request->qr_screen_video->getClientOriginalExtension();
                    $request->qr_screen_video->move(public_path('/upload/queuescreen_video'), $input['qr_screen_video']);
                }

                $queuescreen->update($input);
            } else {

                $input['qr_screen_video'] = '/upload/queuescreen_video/default.mp4';

                if ($request->hasFile('qr_screen_video')){
                    $input['qr_screen_video'] = '/upload/queuescreen_video/'.time().'.'.$request->qr_screen_video->getClientOriginalExtension();
                    $request->qr_screen_video->move(public_path('/upload/queuescreen_video'), $input['qr_screen_video']);

                }

                QueueScreenModel::create($input);
            }
            
            return 'success';
        }
    }

    public function qrcode_scan_screen($key, $branch_id){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }

        if ($user_obj->role_id == 4) {
            if ($user_obj->id != $key) {
                return redirect()->intended('admin');
            }
        }

        $check_user = User::where('id', $key)->whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get()->first();
        if (!isset($check_user)) {
            return redirect()->intended('admin');
        }

        if ($user_obj->role_id == 3) {
            $check_support = CompanyModel::where('support_id', $user_obj->id)->where('user_id', $key)->get()->first();
            if(!isset($check_support)) {
                return redirect()->intended('admin');
            }
        }

        if ($user_obj->role_id == 5 || $user_obj->role_id == 6) {
        $employee = AdminRelationModel::join('company_models', 'company_models.id' , '=', 'admin_relation_models.company_id')->
                                        where('admin_relation_models.user_id', $user_obj->id)->
                                        where('admin_relation_models.branch_id', $branch_id)->
                                        where('company_models.user_id', $key)->
                                        get()->first();            
            if (!isset($employee)) {
                return redirect()->intended('admin');
            }
        }
        $company = CompanyModel::where('user_id', $key)->get()->first();
        $branch_id = $branch_id;
        $user_id = $key;
        $users = CompanyModel::select()->get();
        $user_single = User::find($user_id);

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first(); 

        $queuescreen = QueueScreenModel::where('user_id', $key)->get()->first();

        
        
        return view('system.customer.qrcode_scan', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car', 'branch_id', 'queuescreen', 'company'));
    }

    public function qrcode_scan_screen_seminar($key, $branch_service_id){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }

        if ($user_obj->role_id == 4) {
            if ($user_obj->id != $key) {
                return redirect()->intended('admin');
            }
        }

        $check_user = User::where('id', $key)->whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get()->first();
        if (!isset($check_user)) {
            return redirect()->intended('admin');
        }

        if ($user_obj->role_id == 3) {
            $check_support = CompanyModel::where('support_id', $user_obj->id)->where('user_id', $key)->get()->first();
            if(!isset($check_support)) {
                return redirect()->intended('admin');
            }
        }

        if ($user_obj->role_id == 5 || $user_obj->role_id == 6) {
        $employee = AdminRelationModel::join('company_models', 'company_models.id' , '=', 'admin_relation_models.company_id')->
                                        where('admin_relation_models.user_id', $user_obj->id)->
                                        where('company_models.user_id', $key)->
                                        get()->first();            
            if (!isset($employee)) {
                return redirect()->intended('admin');
            }
        }
        $company = CompanyModel::where('user_id', $key)->get()->first();
        $branch_service_queuescreen = BranchServiceModel::find($branch_service_id);
        $branch_id = $branch_service_queuescreen->branch_id;
        $service_id = $branch_service_queuescreen->service_id;
        $user_id = $key;
        $users = CompanyModel::select()->get();
        $user_single = User::find($user_id);

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first(); 

        $queuescreen = QueueScreenModel::where('user_id', $key)->get()->first();

        
        
        return view('system.customer.qrcode_scan_seminar', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car', 'branch_id', 'service_id', 'queuescreen', 'company'));
    }

    public function getBookingList(Request $request) {
        if ($request->ajax()) {
            $today = now()->format('Y-m-d');

            $queuescreen = QueueScreenModel::where('user_id', $request->company_id)->get()->first();

            $services_view = BranchServiceModel::join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                            where('branch_service_models.branch_id', $request->branch_id)->
                                            selectRaw('
                                                service_models.*
                                            ')->get();

            $all_bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('service_models.user_id', $request->company_id)
                                     ->where('booking_order_models.booking_day', $today)
                                     ->where('service_models.service_type', 'carservice')
                                     ->where('booking_order_models.branch_id', $request->branch_id)
                                     ->whereIn('booking_order_models.booking_status', [1, 2, 4, 7])
                                     ->selectRaw('
                                        booking_order_models.id,
                                        users.name,
                                        service_models.service_name,
                                        package_models.package_name,
                                        booking_order_models.updated_at,
                                        booking_order_models.service_id,
                                        booking_order_models.booking_time,
                                        booking_order_models.booking_status,
                                        booking_order_models.arrived_check,
                                        booking_status_models.booking_status as booking_status_text,
                                        booking_order_models.order_duration,
                                        booking_order_models.client_notice,
                                        timeslot_models.duration,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->orderBy('appt_datetime', 'ASC')
                                     ->get();
            $arrived_count = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('service_models.user_id', $request->company_id)
                                     ->where('booking_order_models.booking_day', $today)
                                     ->where('service_models.service_type', 'carservice')
                                     ->where('booking_order_models.branch_id', $request->branch_id)
                                     ->where('booking_order_models.arrived_check', 1)
                                     ->whereIn('booking_order_models.booking_status', [1, 2, 4, 7])                                     
                                     ->get()->count();
            $total_count = count($all_bookings);

            return view('system.customer.booking_list_content', compact('queuescreen', 'all_bookings', 'services_view', 'total_count', 'arrived_count'));
        }
    }

    public function getBookingListSeminar(Request $request) {
        if ($request->ajax()) {

            $queuescreen = QueueScreenModel::where('user_id', $request->company_id)->get()->first();

            $services_view = BranchServiceModel::join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                            where('branch_service_models.branch_id', $request->branch_id)->
                                            selectRaw('
                                                service_models.*
                                            ')->get();

            $all_bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('service_models.user_id', $request->company_id)
                                     ->where('service_models.service_type', 'seminar')
                                     ->where('booking_order_models.branch_id', $request->branch_id)
                                     ->whereIn('booking_order_models.booking_status', [1, 4, 7])
                                     ->selectRaw('
                                        booking_order_models.id,
                                        users.name,
                                        service_models.service_name,
                                        package_models.package_name,
                                        booking_order_models.updated_at,
                                        booking_order_models.service_id,
                                        booking_order_models.booking_time,
                                        booking_order_models.booking_status,
                                        booking_order_models.arrived_check,
                                        booking_status_models.booking_status as booking_status_text,
                                        booking_order_models.order_duration,
                                        booking_order_models.client_notice,
                                        timeslot_models.duration,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->orderBy('appt_datetime', 'ASC')
                                     ->get();
            $arrived_count = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('service_models.user_id', $request->company_id)
                                     ->where('service_models.service_type', 'seminar')
                                     ->where('booking_order_models.branch_id', $request->branch_id)
                                     ->where('booking_order_models.arrived_check', 1)
                                     ->whereIn('booking_order_models.booking_status', [1, 4, 7])                                     
                                     ->get()->count();
            $total_count = count($all_bookings);

            return view('system.customer.booking_list_content_seminar', compact('queuescreen', 'all_bookings', 'services_view', 'total_count', 'arrived_count'));
        }
    }

    public function getWorkingList(Request $request) {
        if ($request->ajax()) {
            $today = now()->format('Y-m-d');

            $queuescreen = QueueScreenModel::where('user_id', $request->company_id)->get()->first();

            $workings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('service_models.user_id', $request->company_id)
                                     ->where('booking_order_models.booking_day', $today)
                                     ->where('service_models.service_type', 'carservice')
                                     ->where('booking_order_models.branch_id', $request->branch_id)
                                     ->where('booking_order_models.booking_status', 7)
                                     ->selectRaw('
                                        booking_order_models.id,
                                        users.name,
                                        service_models.service_name,
                                        package_models.package_name,
                                        booking_order_models.updated_at,
                                        booking_order_models.service_id,
                                        booking_order_models.booking_time,
                                        booking_order_models.booking_status,
                                        booking_order_models.arrived_check,
                                        booking_status_models.booking_status as booking_status_text,
                                        booking_order_models.order_duration,
                                        booking_order_models.client_notice,
                                        timeslot_models.duration,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->orderBy('updated_at', 'DESC')
                                     ->limit(2)
                                     ->get();
            return view('system.customer.workings_tbody', compact('queuescreen', 'workings'));
        }
    }

    public function checkQRCode(Request $request) {
         $result =0;
         $update_order = '';
            if ($request->data) {
                $today = now()->format('Y-m-d');
                $check = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                ->where('service_models.user_id', $request->company_id)
                ->where('booking_order_models.qrcode_field',$request->data)
                ->where('booking_order_models.booking_day',$today)
                ->where('booking_order_models.arrived_check',0)
                ->selectRaw('booking_order_models.id')
                ->get()->first();
                if ($check) {
                    $input['arrived_check'] = 1;
                    $update_order = BookingOrderModel::find($check->id);
                    $update_order->update($input);
                    $result =1;
                 }else{
                    $result =0;
                 }              
            }

            $data['result'] = $result;
            $data['order'] = $update_order;
            
            return $data;
    }

    public function alert_popup() {
        return view('carservice.alert_popup');
    }

    public function userImageUpload(Request $request) {
        if ($request->ajax()) {

            $user=Auth::user();

            $input = $request->all();

            $update_user = User::find($user->id);

            $input['user_image'] = $update_user->user_image;

            if ($request->hasFile('user_image')){                
                $input['user_image'] = '/upload/user_image/'.time().'.'.$request->user_image->getClientOriginalExtension();
                $request->user_image->move(public_path('/upload/user_image'), $input['user_image']);
            }

            $update_user->update($input);

            return response($update_user);
        }
    }

}
