<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;

use App\SendCode;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\CompanyModel;
use App\ServiceModel;
use App\PackageModel;
use App\BranchModel;
use App\BranchServiceModel;

use App\Panel_news;
use App\Float_news;
use App\PopupModel;
use App\Holiday;

use App\BookingOrderModel;
use App\BookingStatusModel;
use App\TimeslotModel;
use App\AppointmentModel;
use App\RatingStar;
use App\AdminRelationModel;
use App\CreditsModel;
use App\NexmoPrice;


class IndexController extends Controller
{
    //

    public function index()
    {
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }

        if ($user_obj->role_id == 2 || $user_obj->role_id == 4) {
            return redirect()->intended('admin/'.$user_obj->id);
        } else if ($user_obj->role_id == 3) {
            $user_support = CompanyModel::where('support_id', $user_obj->id)->get()->first();
            if (isset($user_support)) {
                return redirect()->intended('admin/'.$user_support->user_id);
            } else {
                return 'You cannot access admin page.';
            }
            
        } else if ($user_obj->role_id == 5 || $user_obj->role_id == 6) {
            $employee = AdminRelationModel::where('user_id', $user_obj->id)->get()->first();            
            if (isset($employee)) {
                $user_employee = CompanyModel::find($employee->company_id);
                return redirect()->intended('admin/'.$user_employee->user_id);
            } else {
                return 'You cannot access admin page.';
            }
            
        }
        // $users = User::whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get();
        // return view('system.dashboard.index', compact('user_obj', 'users'));
    }

    public function dashboard($key)
    {
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

        $check_user = User::where('id', $key)->whereIn('role_id', [2,4])->orderBy('role_id', 'ASC')->get()->first();
        if (!isset($check_user)) {
            return redirect()->intended('admin');
        }

        $company = CompanyModel::where('user_id', $key)->get()->first();
        if (!isset($company)) {
            return redirect()->intended('admin/'.$key.'/company');
        }

        $user_id = $key;
        $users = CompanyModel::select()->get();
        $user_single = User::find($user_id);   

        $current_day=date("d");
        $current_month=date("m");
        $current_year=date("Y");
        $sum_year=BookingOrderModel::
            where("user_id",$key)->
            where("booking_status", 2)->
            whereYear("booking_day",$current_year)->
            sum('paid_price');

        $sum_month=BookingOrderModel::
            where("user_id",$key)->
            where("booking_status", 2)->
            whereMonth("booking_day",$current_month)->
            whereYear("booking_day",$current_year)->
            sum('paid_price');

        $sum_day=BookingOrderModel::
            where("user_id",$key)->
            where("booking_status", 2)->
            whereDay("booking_day",$current_day)->
            whereMonth("booking_day",$current_month)->
            whereYear("booking_day",$current_year)->
            sum('paid_price');

        $total_today_booking=BookingOrderModel::
            where("user_id",$key)->
            whereIn("booking_status",[1,2,4,7])->
            whereDay("booking_day",$current_day)->
            whereMonth("booking_day",$current_month)->
            whereYear("booking_day",$current_year)->
            get()->count();

        $total_today_not_arrived=BookingOrderModel::
            where("user_id",$key)->
            whereIn("booking_status",[1,4])->
            whereDay("booking_day",$current_day)->
            whereMonth("booking_day",$current_month)->
            whereYear("booking_day",$current_year)->
            get()->count();

        $rating_month=BookingOrderModel::
            where("user_id",$key)->
            where("rating_check", 1)->
            whereMonth("booking_day",$current_month)->
            whereYear("booking_day",$current_year)->
            get()->count();

        $nexmo_balance = NexmoPrice::select()->get()->last();
        // var_dump($nexmo_balance);die();
        // var_dump($sum_day, $sum_month, $sum_year);die();
        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();     
        return view('system.dashboard.index', compact('user_obj', 'users', 'user_id', 'user_single', 'branch_services', 'branch_services_car', 'sum_year', 'sum_month', 'sum_day', 'total_today_booking', 'total_today_not_arrived', 'rating_month', 'nexmo_balance'));
    }

    public function login()
    {
        return view('system.login');
    }

    public function logout()
    {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect("admin/login");
    }

    public function editProfile(Request $request) {
        if ($request->ajax()) {
            $login=Auth::attempt([
                "password"=>$request->get('password'),
                "id"=>$request->get('id'),
                "provider"=>"site",
            ]);
            if($login){
                if ($request->new_password != $request->confirm_password) {
                    return 'Password Error2';
                } else {
                    $user = User::where('id', $request->id)->get()->first();
                    $input['name'] = $request->name;
                    $input['email'] = $request->email;
                    $input['password'] = Hash::make($request->new_password);
                    $input['user_image'] = $user->user_image;

                    if ($request->hasFile('user_image')){                
                        $input['user_image'] = '/upload/user_image/'.time().'.'.$request->user_image->getClientOriginalExtension();
                        $request->user_image->move(public_path('/upload/user_image'), $input['user_image']);
                    }
                    $user->update($input);
                    return 'success';
                }
                
            } else {
                return 'Password Error1';
            }
        }
    }

    public function resetPassword(Request $request) {
        if ($request->ajax()) {
            $user_obj=Auth::user();
            $login=Auth::attempt([
                "password"=>$request->current_password,
                "id"=>$user_obj->id,
                "provider"=>"site",
            ]);
            if($login){
                if ($request->new_password != $request->confirm_password) {
                    return 'Password Error';
                } else {
                    $user = User::where('id', $user_obj->id)->get()->first();
                    $input['password'] = Hash::make($request->new_password);
                    $user->update($input);
                    return 'success';
                }
                
            } else {
                return 'Password Error';
            }
        }
    }

    public function addEmployee(Request $request) {
        if ($request->ajax()) {
            
            if ($request->password != $request->confirm_password) {
                return 'Password Error';
            } else {
                $input['name'] = $request->name;
                $input['email'] = $request->email;
                $input['password'] = Hash::make($request->password);
                $input['role_id'] = $request->role_id;
                $input['provider'] = 'site';
                $input['activated'] = 1;
                $input['complete_profile'] = 1;
                $user = User::create($input);

                if ($request->role_id == 2 || $request->role_id == 4) {
                    $input['user_id'] = $user->id;
                    $input['question'] = 'What is your car plate number, color and car model?';
                    $input['answer'] = 'VE 1002, Black, Toyota Vios';
                    $input['before_popup_title'] = 'Be on time!';
                    $input['before_popup_des'] = 'Please be reminded to arrive 30mins before your appointment time to avoid disappointment. Normally we are fully packed and your slot will be taken if you are late for 10 mins. Thanks!';
                    $input['after_popup_title'] = '( #TimeNow - #bookingDateTime ) left, please complete your payment!';
                    $input['after_popup_des'] = 'You are less than #NoHoldCountDown hours away from the appointment, please complete your payment within 15 mins to avoid auto-cancellation.';
                    $input['home_top_1_title'] = 'Make Payment!';
                    $input['home_top_1_des'] = 'Please be reminded to make payment. You have #HoldingHoursRemain hours remaining before your appointment is automatically cancelled.';
                    $input['home_top_2_title'] = 'Your appointment has been cancelled!';
                    $input['home_top_2_des'] = 'Please make your payment in time in the future. You can now reschedule from the system.';
                    $input['home_top_4_title'] = 'Your appointment has been cancelled!';
                    $input['home_top_4_des'] = 'Your appointment has been cancelled by yourself.';
                    $input['home_top_3_title'] = 'Your Appointment is near!';
                    $input['home_top_3_des'] = 'Please be reminded about your appointment on #AppointmentDateTime.';
                    $input['sms_after_booking'] = 0;
                    $input['sms_payment'] = 0;
                    $input['sms_cancel_admin'] = 0;
                    $input['sms_cancel_client'] = 0;
                    $input['sms_near_appt'] = 0;
                    $popup = PopupModel::create($input);
                }
                return 'success';
            }
        }
    }

    public function editEmployee(Request $request) {
        if ($request->ajax()) {
            if ($request->password != $request->confirm_password) {
                return 'Password Error';
            } else {
                $user = User::where('id', $request->id)->get()->first();
                $input['name'] = $request->name;
                $input['email'] = $request->email;
                $input['role_id'] = $request->role_id;
                if ($request->password != '') {
                    $input['password'] = Hash::make($request->password);
                }                
                $user->update($input);
                return 'success';
            }
        }
    }

    public function getEmployee(Request $request) {
        if ($request->ajax()) {            
            $employee = User::where('id', $request->id)->get()->first();
            $company_info = CompanyModel::where('support_id', $employee->id)->get();
            $company_info_b = AdminRelationModel::where('user_id', $employee->id)->get()->first();
            $data['employee'] = $employee;
            $data['companies'] = $company_info;
            $data['company_id'] = 0;
            if (isset($company_info_b)) {
                $data['company_id'] = $company_info_b->company_id;
            }            
            return $data;
        }
    }

    public function getMember(Request $request) {
        if ($request->ajax()) {            
            $employee = User::where('id', $request->id)->get()->first();
            $employee_info = AdminRelationModel::where('user_id', $employee->id)->get()->first();   
            $services = array();         
            if (isset($employee_info)) {
                $services = BranchServiceModel::join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                        where('branch_id', $employee_info->branch_id)->
                                        selectRaw('service_models.*')->get();
            }

            $service_ids = explode(',', $employee_info->service_id);   

            $data['employee'] = $employee;
            $data['employee_info'] = $employee_info;
            $data['services'] = $services;
            $data['service_ids'] = $service_ids;
                        
            return $data;
        }
    }

    public function getService(Request $request) {
        if ($request->ajax()) {            
            
            $services = BranchServiceModel::join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                        where('branch_id', $request->id)->
                                        selectRaw('service_models.*')->get();
            $data['services_get'] = $services;

            return $data;
        }
    }

    public function company_member(Request $request) {
        if ($request->ajax()) {
            
            $branch_staff = AdminRelationModel::where('user_id', $request->support_id)->get()->first();
            $input['branch_id'] = $request->employee_branch_id;
            $service_ids = array();
            $service_ids = $request->employee_service_ids;
            $input['service_id'] = null;
            if (isset($service_ids)) {
                $service_ids_str = '';
                
                foreach ($service_ids as $key => $service_id) {
                    if ($key == count($service_ids)-1) {
                        $service_ids_str .= $service_id;
                    } else {
                        $service_ids_str .= $service_id.',';
                    }
                }
                $input['service_id'] = $service_ids_str;
            }
            $branch_staff->update($input);
            return 'success';
        }
    }

    public function company_support(Request $request) {
        if ($request->ajax()) {

            $companies = CompanyModel::where('support_id',$request->support_id)->get();
            if (count($companies) > 0) {
                foreach ($companies as $value) {
                    $company = CompanyModel::find($value->id); 
                    $input['support_id'] = 0;
                    $company->update($input);
                }                           
            }
            if (isset($request->company_ids)) {
               $data['support_id'] = $request->support_id;
                foreach ($request->company_ids as $company_id) {
                    $com = CompanyModel::find($company_id); 
                    $com->update($data);
                }  
            }
            $branch_staff = AdminRelationModel::where('user_id', $request->support_id)->get();
            if (!isset($request->company_employee_id)) {                
                foreach ($branch_staff as $value) {
                    AdminRelationModel::destroy($value->id);
                }
            } else {
                foreach ($branch_staff as $value) {
                    if ($value->company_id != $request->company_employee_id) {
                        $input['user_id'] = $request->support_id;
                        $input['company_id'] = $request->company_employee_id;
                        $value->update($input);
                    }
                }
                $branch_staff_check = AdminRelationModel::where('user_id', $request->support_id)->where('company_id', $request->company_employee_id)->get()->first();
                if (!isset($branch_staff_check)) {
                    $input['user_id'] = $request->support_id;
                    $input['company_id'] = $request->company_employee_id;
                    AdminRelationModel::create($input);
                }
            }
            return 'success';
        }
    }

    public function deleteEmployee(Request $request) {
        if ($request->ajax()) {            
            $employee = User::where('id', $request->id)->get()->first();
            User::destroy($employee->id);
            $companys = CompanyModel::where('user_id', '=', $request->id)->get();
            foreach ($companys as $value) {
                CompanyModel::destroy($value->id);
            }
            $holidays = Holiday::where('user_id', '=', $request->id)->get();
            foreach ($holidays as $value) {
                Holiday::destroy($value->id);
            }
            $services = ServiceModel::where('user_id', '=', $request->id)->get();
            foreach ($services as $value) {
                ServiceModel::destroy($value->id);
            }
            $branches = BranchModel::where('user_id', '=', $request->id)->get();
            foreach ($branches as $value) {
                BranchModel::destroy($value->id);
            }
            $floats = Float_news::where('user_id', '=', $request->id)->get();
            foreach ($floats as $value) {
                Float_news::destroy($value->id);
            }
            $panels = Panel_news::where('user_id', '=', $request->id)->get();
            foreach ($panels as $value) {
                Panel_news::destroy($value->id);
            }
            $popups = PopupModel::where('user_id', '=', $request->id)->get();
            foreach ($popups as $value) {
                PopupModel::destroy($value->id);
            }
            return 'success';
        }
    }

    public function loginAdmin(Request $request){

        $user_obj=Auth::user();

        if ($request->ajax())
        {
            $login=Auth::attempt([
                "email"=>$request->get('email'),
                "password"=>$request->get('password'),
                "provider"=>"site",
            ]);

            if($login){
                Auth::login(Auth::user());
                $user_obj=Auth::user();

                $request->session()->save();

                if($user_obj->role_id=="1"){                    
                    return "You can't access admin page!!!";
                } else {
                    return 'success';
                }
            }
            else{
                return "invalid email or password";
            }
        }
    }

    public function bookings($key){
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

        $user_id = $key;
        $users = CompanyModel::select()->get();
        $user_single = User::find($user_id);
        
        $branches = BranchModel::where('user_id', $key)->get();
        $services = ServiceModel::where('user_id', $key)->get();

        if ($user_obj->role_id == 5 || $user_obj->role_id == 6) {
            $employee_branch = AdminRelationModel::where('admin_relation_models.user_id', $user_obj->id)->
                                        get()->first();
            $branches = BranchModel::where('id', $employee_branch->branch_id)->get();
        }

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();    

        return view('system.dashboard.booking', compact('user_obj', 'user_id', 'users', 'user_single', 'company', 'branches', 'services', 'branch_services', 'branch_services_car'));
    }

    public function bookings_seminar($key){
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

        $company = CompanyModel::where('user_id', $key)->get()->first();
        $branch_services = BranchServiceModel::join('branch_models', 'branch_models.id', '=', 'branch_service_models.branch_id')->
                                            join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                            where('service_models.user_id', $key)->
                                            where('service_models.service_type', 'seminar')
                                            ->selectRaw('
                                                branch_service_models.id,
                                                service_models.service_name,
                                                branch_models.branch_name
                                                ')
                                            ->get()->first();
        if ($user_obj->role_id == 5) {
            $employee_branch = AdminRelationModel::where('admin_relation_models.user_id', $user_obj->id)->
                                        get()->first();
            $branch_services = BranchServiceModel::join('branch_models', 'branch_models.id', '=', 'branch_service_models.branch_id')->
                                            join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                            where('service_models.user_id', $key)->
                                            where('branch_models.id', $employee_branch->branch_id)->
                                            where('service_models.service_type', 'seminar')
                                            ->selectRaw('
                                                branch_service_models.id,
                                                service_models.service_name,
                                                branch_models.branch_name
                                                ')
                                            ->get()->first();
        }
        if ($user_obj->role_id == 6) {
            $employee_branch = AdminRelationModel::where('admin_relation_models.user_id', $user_obj->id)->
                                        get()->first();
            $relation_service_ids = explode(',', $employee_branch->service_id);
            
            $branch_services = BranchServiceModel::join('branch_models', 'branch_models.id', '=', 'branch_service_models.branch_id')->
                                            join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                            where('service_models.user_id', $key)->
                                            where('branch_models.id', $employee_branch->branch_id)->
                                            whereIn('service_models.id', $relation_service_ids)->
                                            where('service_models.service_type', 'seminar')
                                            ->selectRaw('
                                                branch_service_models.id,
                                                service_models.service_name,
                                                branch_models.branch_name
                                                ')
                                            ->get();            
            
            if (count($branch_services)>0) {
                $branch_services = $branch_services[0];
            }            
        }
        if (isset($branch_services)) {
            return redirect()->intended('admin/'.$key.'/booking_seminar/'.$branch_services->id);
        } else {
            return redirect()->intended('admin/'.$key.'/bookings');
        }
    }

    public function booking_seminar_con($key, $branch_service_id) {
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

        $company = CompanyModel::where('user_id', $key)->get()->first();
        $branch_services = BranchServiceModel::join('branch_models', 'branch_models.id', '=', 'branch_service_models.branch_id')->
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
            $branch_services = BranchServiceModel::join('branch_models', 'branch_models.id', '=', 'branch_service_models.branch_id')->
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
            $relation_service_ids = explode(',', $employee_branch->service_id);
            $branch_services = BranchServiceModel::join('branch_models', 'branch_models.id', '=', 'branch_service_models.branch_id')->
                                            join('service_models', 'service_models.id', '=', 'branch_service_models.service_id')->
                                            where('service_models.user_id', $key)->
                                            where('branch_models.id', $employee_branch->branch_id)->
                                            whereIn('service_models.id', $relation_service_ids)->
                                            where('service_models.service_type', 'seminar')
                                            ->selectRaw('
                                                branch_service_models.id,
                                                service_models.service_name,
                                                branch_models.branch_name
                                                ')
                                            ->get();            
            if (count($branch_services) == 0) {
                return redirect()->intended('admin/'.$key.'/bookings');
            }
        }
        $branch_service = BranchServiceModel::find($branch_service_id);
        $bookings_all = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                 ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                 ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                 ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                 ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                 ->where('booking_order_models.branch_id', $branch_service->branch_id)
                                 ->where('booking_order_models.service_id', $branch_service->service_id)
                                 ->whereIn('booking_order_models.booking_status', [1, 4, 7])
                                 ->selectRaw('
                                    booking_order_models.id,
                                    users.name,
                                    branch_models.branch_name,
                                    service_models.service_name,
                                    package_models.package_name,
                                    booking_order_models.updated_at,
                                    booking_order_models.booking_time,
                                    booking_order_models.booking_status,
                                    booking_order_models.arrived_check,
                                    booking_status_models.booking_status as booking_status_text,
                                    CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                    ')
                                 ->get();
        $booked_bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                 ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                 ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                 ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                 ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                 ->where('booking_order_models.branch_id', $branch_service->branch_id)
                                 ->where('booking_order_models.service_id', $branch_service->service_id)
                                 ->where('booking_order_models.booking_status', 1)
                                 ->get();
        $booked_count = count($booked_bookings);
        $paid_bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                 ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                 ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                 ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                 ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                 ->where('booking_order_models.branch_id', $branch_service->branch_id)
                                 ->where('booking_order_models.service_id', $branch_service->service_id)
                                 ->where('booking_order_models.booking_status', 4)
                                 ->get();
        $paid_count = count($paid_bookings);
        $arrived_bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                 ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                 ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                 ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                 ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                 ->where('booking_order_models.branch_id', $branch_service->branch_id)
                                 ->where('booking_order_models.service_id', $branch_service->service_id)
                                 ->whereIn('booking_order_models.booking_status', [1,4])
                                 ->where('booking_order_models.arrived_check', 1)
                                 ->get();
        $arrived_count = count($arrived_bookings);
        $selected_id = $branch_service_id;
           
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();  

        return view('system.dashboard.booking_seminar', compact('user_obj', 'user_id', 'users', 'user_single', 'company', 'branch_services', 'branch_services_car', 'bookings_all', 'booked_count', 'paid_count', 'arrived_count', 'selected_id'));
    }

    public function getCalendar(Request $request)
    {
        $page_data=[];              
       
        $selected_month=$request->get("selected_month","");
        $selected_year=$request->get("selected_year","");

        if($selected_month!=""&&$selected_month==13){
            $selected_month='1';
            $selected_year=(string)($selected_year+1);
        }

        if($selected_month!=""&&$selected_month==0){
            $selected_month='12';
            $selected_year=(string)($selected_year-1);
        }

        $current_month=date("m");
        $current_year=date("Y");        

        $current_year=$selected_year;        
        $current_month=$selected_month;

        //get current month days
        $number_of_days=cal_days_in_month(CAL_GREGORIAN,$current_month,$current_year);
        
        $page_data["current_year"]=$current_year;
        $page_data["current_month"]=$current_month;
        $page_data["number_of_days"]=$number_of_days;

        return view('carservice.calendar_admin',$page_data);
    }

    public function getTimeslot(Request $request)
    {
        $user_obj=Auth::user();

        $timeslot_data=[];

        $timeslot_data["select_date_t"] = $request->date;

        $all_bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('service_models.user_id', $request->company_id)
                                     ->where('booking_order_models.branch_id', $request->branch_id)
                                     ->where('booking_order_models.booking_day', $request->date)
                                     ->where('service_models.service_type', 'carservice')
                                     ->whereIn('booking_order_models.booking_status', [1, 2, 4, 5, 6, 7])
                                     ->selectRaw('
                                        booking_order_models.id,
                                        users.name,
                                        service_models.service_name,
                                        package_models.package_name,
                                        booking_order_models.updated_at,
                                        booking_order_models.service_id,
                                        booking_order_models.booking_time,
                                        booking_order_models.client_notice,
                                        booking_order_models.booking_status,
                                        booking_order_models.arrived_check,
                                        booking_status_models.booking_status as booking_status_text,
                                        booking_order_models.order_duration,
                                        timeslot_models.duration,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->orderBy('booking_time', 'ASC')
                                     ->get(); 

        $all_services_t = ServiceModel::join('branch_service_models', 'branch_service_models.service_id', '=', 'service_models.id')->
            where('service_models.user_id', $request->company_id)->
            where('branch_service_models.branch_id', $request->branch_id)->
            where('service_models.service_type', 'carservice')->
            selectRaw('
                service_models.id,
                service_models.service_name
            ')->
            get();  

        if ($user_obj->role_id == 6) {
            $employee_branch = AdminRelationModel::where('admin_relation_models.user_id', $user_obj->id)->
                                        get()->first();
            $relation_service_ids = explode(',', $employee_branch->service_id);
            $all_services_t = ServiceModel::join('branch_service_models', 'branch_service_models.service_id', '=', 'service_models.id')->
                where('service_models.user_id', $request->company_id)->
                where('branch_service_models.branch_id', $request->branch_id)->
                whereIn('service_models.id', $relation_service_ids)->
                where('service_models.service_type', 'carservice')->
                selectRaw('
                    service_models.id,
                    service_models.service_name
                ')->
                get();             
        }

        $timeslot_data["all_bookings_t"] = $all_bookings->all();      
        $timeslot_data["all_services_t"] = $all_services_t->all();      

        return view('carservice.calendar.time_admin',$timeslot_data);
    }

    public function updateAdminNotice(Request $request) {
        if ($request->ajax()) {
            $user_obj=Auth::user();
            $order = BookingOrderModel::find($request->id);
            $input['admin_notice'] = $request->admin_notice;
            $input['manager_id'] = $user_obj->id;
            $order->update($input);
            return 'success';
        }
    }

    public function updateClientNotice(Request $request) {
        if ($request->ajax()) {
            $user_obj=Auth::user();
            $order = BookingOrderModel::find($request->id);
            $input['client_notice'] = $request->client_notice;
            $input['manager_id'] = $user_obj->id;
            $order->update($input);
            return 'success';
        }
    }

    public function creditFunction($user_id, $package_id, $old_order_id) {
        $promo_booking = BookingOrderModel::join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')->
                                        where('booking_order_models.id', $old_order_id)->
                                        selectRaw('booking_order_models.*, package_models.credit_amount, package_models.package_unit')->
                                        get()->first();
        if (isset($promo_booking)) {
            if ($promo_booking->user_id != $user_id && $promo_booking->package_id == $package_id) {
                $credits_rm = '0';
                $credits_sgd = '0';
                if ($promo_booking->package_unit == 'RM') {
                    $credits_rm = $promo_booking->credit_amount;
                } else if ($promo_booking->package_unit == 'SGD') {
                    $credits_sgd = $promo_booking->credit_amount;
                }
                $promo_user = User::find($promo_booking->user_id);
                $promo_input['credits_rm'] = intval($promo_user->credits_rm)+intval($credits_rm);
                $promo_input['credits_sgd'] = intval($promo_user->credits_sgd)+intval($credits_sgd);
                $promo_user->update($promo_input);
                $order_user = User::find($user_id);
                $order_input['credits_rm'] = intval($order_user->credits_rm)+intval($credits_rm);
                $order_input['credits_sgd'] = intval($order_user->credits_sgd)+intval($credits_sgd);
                $order_user->update($order_input);
                if ($credits_rm != '0' && $credits_rm != '') {
                    $promo_credit = array(
                        'booking_id' => $promo_booking->id, 
                        'user_id' => $promo_user->id, 
                        'other_id' => $order_user->id, 
                        'branch_id' => $promo_booking->branch_id, 
                        'service_id' => $promo_booking->service_id, 
                        'package_id' => $promo_booking->package_id, 
                        'credit_unit' => 'RM', 
                        'credit_amount' => $credits_rm, 
                    );
                    $order_credit = array(
                        'booking_id' => $promo_booking->id, 
                        'user_id' => $order_user->id, 
                        'other_id' => $promo_user->id, 
                        'branch_id' => $promo_booking->branch_id, 
                        'service_id' => $promo_booking->service_id, 
                        'package_id' => $promo_booking->package_id, 
                        'credit_unit' => 'RM', 
                        'credit_amount' => $credits_rm, 
                    );
                    CreditsModel::create($promo_credit);
                    CreditsModel::create($order_credit);
                } else if ($credits_sgd != '0' && $credits_sgd != '') {
                    $promo_credit = array(
                        'booking_id' => $promo_booking->id, 
                        'user_id' => $promo_user->id, 
                        'other_id' => $order_user->id, 
                        'branch_id' => $promo_booking->branch_id, 
                        'service_id' => $promo_booking->service_id, 
                        'package_id' => $promo_booking->package_id, 
                        'credit_unit' => 'SGD', 
                        'credit_amount' => $credits_sgd, 
                    );
                    $order_credit = array(
                        'booking_id' => $promo_booking->id, 
                        'user_id' => $order_user->id, 
                        'other_id' => $promo_user->id, 
                        'branch_id' => $promo_booking->branch_id, 
                        'service_id' => $promo_booking->service_id, 
                        'package_id' => $promo_booking->package_id, 
                        'credit_unit' => 'SGD', 
                        'credit_amount' => $credits_sgd, 
                    );
                    CreditsModel::create($promo_credit);
                    CreditsModel::create($order_credit);
                }
            }
        }
        return;
    }

    public function updateOrder(Request $request) {
        if ($request->ajax()) {
            $user_obj=Auth::user();
            $order = BookingOrderModel::find($request->id);
            if ($request->booking_status == 2) {
                $input['booking_status'] = $request->booking_status;
                $input['complete_time'] = now();
                $input['promo_code'] = rand(111111, 999999);
                if ($order->promo_id != 0) {
                    $this->creditFunction($order->user_id, $order->package_id, $order->promo_id);
                }
                $updated_at = $order->updated_at;
                $now = now();
                $interval = date_diff($now, $updated_at);
                $diff_i = $interval->format('%i');
                $diff_h = $interval->format('%h');
                $input['order_duration'] = $diff_h*60+$diff_i+1;
                $update_order = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                ->where('booking_order_models.id', $order->id)
                ->selectRaw('booking_order_models.id as order_id, booking_order_models.user_id as order_user_id, service_models.user_id as company_id')
                ->get()->first();
                $reminder = PopupModel::where('user_id', $update_order->company_id)->get()->first();
                $appointment = AppointmentModel::where('booking_order_id', '=', $update_order->order_id)->get()->first(); 
                $appoint_1['is_read'] = 0;
                $appointment->update($appoint_1);
            }

            if ($request->booking_status == 4) {
                if ($order->booking_status == 1) {
                    $input['booking_status'] = $request->booking_status;
                } else {
                    $input['booking_status'] = $order->booking_status;
                }
                $input['paid_time'] = now();
                $input['paid_price'] = $request->pay_price;
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
                $update_order = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                ->where('booking_order_models.id', $order->id)
                ->selectRaw('booking_order_models.id as order_id, booking_order_models.user_id as order_user_id, service_models.user_id as company_id')
                ->get()->first();
                $reminder = PopupModel::where('user_id', $update_order->company_id)->get()->first();
                $appointment = AppointmentModel::where('booking_order_id', '=', $update_order->order_id)->get()->first(); 
                $appoint_1['is_read'] = 0;
                $appointment->update($appoint_1);
            }
            if ($request->booking_status == 7) {
                $input['booking_status'] = $request->booking_status;
                $input['arrived_time'] = now();
                $update_order = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                ->where('booking_order_models.id', $order->id)
                ->selectRaw('booking_order_models.id as order_id, booking_order_models.user_id as order_user_id, service_models.user_id as company_id')
                ->get()->first();
                $reminder = PopupModel::where('user_id', $update_order->company_id)->get()->first();
                $appointment = AppointmentModel::where('booking_order_id', '=', $update_order->order_id)->get()->first(); 
                $appoint_1['is_read'] = 0;
                $appointment->update($appoint_1);
            }
            if ($request->booking_status == 6) {
                $input['booking_status'] = $request->booking_status;
                $update_order = BookingOrderModel::join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                ->where('booking_order_models.id', $order->id)
                ->selectRaw('booking_order_models.id as order_id, booking_order_models.user_id as order_user_id, service_models.user_id as company_id')
                ->get()->first();
                $appointment = AppointmentModel::where('booking_order_id', '=', $update_order->order_id)->get()->first(); 
                $reminder = PopupModel::where('user_id', $update_order->company_id)->get()->first();
                $appoint['user_id'] = $update_order->order_user_id;
                $appoint['booking_order_id'] = $update_order->order_id;
                $appoint['appointment_title'] = $reminder->home_top_2_title;
                $appoint['appointment_body'] = $reminder->home_top_2_des;
                $appoint['is_read'] = 1;
                $appointment->update($appoint);
                $user_phone = User::find($update_order->order_user_id);
                if ($reminder->sms_cancel_admin == 1) {
                    SendCode::sendSMS($user_phone->phone_number, $appoint['appointment_title'], $appoint['appointment_body']);
                }
            }            
            $input['manager_id'] = $user_obj->id;
            $order->update($input);

            $data['status'] = 'success';
            $data['orderData'] = $order;
            return $data;
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

    public function updateArrivedCheck(Request $request) {
        if ($request->ajax()) {
            $input['arrived_check'] = 1;
            $update_order = BookingOrderModel::find($request->id);
            $update_order->update($input);
            $data['status'] = 'success';
            $data['orderData'] = $update_order;
            return $data;
        }
    }

    public function modalDetail(Request $request) {
        if ($request->ajax()) {
            $order = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.id', $request->id)
                                     ->whereIn('booking_order_models.booking_status', [1, 2, 4, 5, 6, 7])
                                     ->selectRaw('
                                        booking_order_models.id,
                                        booking_order_models.user_id,
                                        service_models.service_name,
                                        package_models.package_name,
                                        package_models.package_unit,
                                        package_models.package_price,
                                        package_models.credit_amount,
                                        booking_order_models.booking_time,
                                        booking_order_models.booked_time,
                                        booking_order_models.paid_time,
                                        booking_order_models.paid_price,
                                        booking_order_models.arrived_time,
                                        booking_order_models.call_time,                                        
                                        booking_order_models.complete_time,
                                        booking_order_models.updated_at,
                                        booking_status_models.booking_status,
                                        booking_order_models.client_notice,
                                        booking_order_models.admin_notice,
                                        booking_order_models.reschedule_check,
                                        booking_order_models.reschedule_time,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->get()->first(); 
            $user = User::find($order->user_id);

            $price = $this->getPayAmount($order->user_id, $order->package_unit, $order->package_price, $order->credit_amount);

            return view('system.dashboard.modal_detail', compact('order', 'user', 'price'));
        }
    }

    public function getPrice(Request $request) {
        if ($request->ajax()) {
            $order = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.id', $request->id)
                                     ->whereIn('booking_order_models.booking_status', [1, 2, 4, 5, 6, 7])
                                     ->selectRaw('
                                        booking_order_models.id,
                                        booking_order_models.user_id,
                                        service_models.service_name,
                                        package_models.package_name,
                                        package_models.package_unit,
                                        package_models.package_price,
                                        package_models.credit_amount,
                                        booking_order_models.booking_time,
                                        booking_order_models.booked_time,
                                        booking_order_models.paid_time,
                                        booking_order_models.paid_price,
                                        booking_order_models.arrived_time,
                                        booking_order_models.call_time,                                        
                                        booking_order_models.complete_time,
                                        booking_order_models.updated_at,
                                        booking_status_models.booking_status,
                                        booking_order_models.client_notice,
                                        booking_order_models.admin_notice,
                                        booking_order_models.reschedule_check,
                                        booking_order_models.reschedule_time,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->get()->first(); 
            $user = User::find($order->user_id);

            $price = $this->getPayAmount($order->user_id, $order->package_unit, $order->package_price, $order->credit_amount);

            return $price;
        }
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

    public function modalDetailSeminar(Request $request) {
        if ($request->ajax()) {
            $order = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.id', $request->id)
                                     ->whereIn('booking_order_models.booking_status', [1, 2, 4, 5, 6, 7])
                                     ->selectRaw('
                                        booking_order_models.id,
                                        booking_order_models.user_id,
                                        service_models.service_name,
                                        package_models.package_name,
                                        package_models.package_unit,
                                        package_models.package_price,
                                        package_models.credit_amount,
                                        booking_order_models.booking_time,
                                        booking_order_models.booked_time,
                                        booking_order_models.paid_time,
                                        booking_order_models.paid_price,
                                        booking_order_models.arrived_time,
                                        booking_order_models.call_time,                                        
                                        booking_order_models.complete_time,
                                        booking_order_models.updated_at,
                                        booking_status_models.booking_status,
                                        booking_order_models.client_notice,
                                        booking_order_models.admin_notice,
                                        booking_order_models.reschedule_check,
                                        booking_order_models.reschedule_time,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->get()->first(); 
            $user = User::find($order->user_id);

            $price = $this->getPayAmount($order->user_id, $order->package_unit, $order->package_price, $order->credit_amount);

            return view('system.dashboard.modal_detail_seminar', compact('order', 'user', 'price'));
        }
    }

    public function addAppointment(Request $request) {
        if ($request->ajax()) {
            $service_appt = ServiceModel::find($request->id); 
            $packages_appt = PackageModel::where('service_id', $request->id)->get();
            $bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->where('branch_models.user_id', '=', $request->company_id)
                                     ->selectRaw('
                                        users.id
                                        ')
                                     ->groupBy('users.id')
                                     ->get();
            // $users_appt = array();
            // foreach ($bookings as $value) {

            //     $customer = User::where('id', $value->id)
            //                     ->get()->first();
                
            //     array_push($users_appt, $customer);
            // }
            $users_appt = User::select()->get();

            return view('system.dashboard.modal_add_appt', compact('service_appt', 'users_appt', 'packages_appt'));
        }
    }

    public function addAppointmentSeminar(Request $request) {
        if ($request->ajax()) {
            $company_id = $request->company_id;
            $service_branch = BranchServiceModel::find($request->selected_id); 
            $branch_id = $service_branch->branch_id;
            $service_appt = ServiceModel::find($service_branch->service_id); 
            $packages_appt = PackageModel::where('service_id', $service_branch->service_id)->get();
            $bookings = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->where('branch_models.user_id', '=', $company_id)
                                     ->selectRaw('
                                        users.id
                                        ')
                                     ->groupBy('users.id')
                                     ->get();
            // $users_appt = array();
            // foreach ($bookings as $value) {

            //     $customer = User::where('id', $value->id)
            //                     ->get()->first();
                
            //     array_push($users_appt, $customer);
            // }
            $users_appt = User::select()->get();

            return view('system.dashboard.modal_add_appt_seminar', compact('service_appt', 'users_appt', 'packages_appt', 'company_id', 'branch_id'));
        }
    }


    public function addAppointmentClass(Request $request) {
        if ($request->ajax()) {
            $company = CompanyModel::where('user_id', $request->company_user_id)->get()->first();
            $input['user_id'] = $request->user_id;
            $input['manager_id'] = $request->user_id;
            $input['booking_status'] = 1;
            $input['arrived_check'] = 0;
            $input['branch_id'] = $request->branch_id;
            $input['service_id'] = $request->service_id;
            $input['package_id'] = $request->package_id;
            $input['booking_day'] = $request->booking_day;
            $input['booking_time'] = $request->booking_time;
            $input['client_notice'] = $request->client_notice;
            $input['admin_notice'] = $request->admin_notice;
            $input['qrcode_field'] = bcrypt($request->user_id.str_random(40));
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
            if (!$appointment) {
                AppointmentModel::create($appoint);
            } else {
                $appointment->update($appoint);
            }
            $user_phone = User::find($new_booking->user_id);
            if ($reminder->sms_payment == 1) {
                SendCode::sendSMS($user_phone->phone_number, $appoint['appointment_title'], $appoint['appointment_body']);
            }

            $order = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.id', $new_booking->id)
                                     ->selectRaw('
                                        booking_order_models.id,
                                        users.name,
                                        booking_order_models.user_id,
                                        service_models.service_name,
                                        package_models.package_name,
                                        booking_order_models.booking_time,
                                        booking_order_models.booked_time,
                                        booking_order_models.client_notice,
                                        booking_order_models.paid_time,
                                        booking_order_models.arrived_time,
                                        booking_order_models.call_time,                                        
                                        booking_order_models.complete_time,
                                        booking_order_models.updated_at,
                                        booking_status_models.booking_status,
                                        booking_order_models.client_notice,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->get()->first(); 
            return $order;
        }
    }

    public function addUserAppointmentClass(Request $request) {
        if ($request->ajax()) {

            $same_name = User::where('name', $request->client_name)->get()->first();
            $same_phone = User::where('phone_number', $request->client_phone)->get()->first();
            $same_email = User::where('email', $request->client_email)->get()->first();

            if (isset($same_name)) {
                $data['same_user'] = 'This user name is aleady exist';
                return $data;
            }

            if (isset($same_phone)) {
                $data['same_phone'] = 'This phone number is aleady exist';
                return $data;
            }

            if (isset($same_email)) {
                $data['same_email'] = 'This email is aleady exist';
                return $data;
            }

            $new_user=User::create([
                'password' => Hash::make('clientid0000'),
                'name'=>$request->client_name,
                'phone_number'=>$request->client_phone,
                'nationality'=>$request->client_national,
                'ic'=>$request->client_ic,
                'email'=>$request->client_email,
                'activated'    =>  1,
                'role_id'    =>  1,
                "provider"=>"site"
            ]);

            $company = CompanyModel::where('user_id', $request->company_user_id)->get()->first();
            $input['user_id'] = $new_user->id;
            $input['manager_id'] = $new_user->id;
            $input['booking_status'] = 1;
            $input['arrived_check'] = 0;
            $input['branch_id'] = $request->branch_id;
            $input['service_id'] = $request->service_id;
            $input['package_id'] = $request->package_id;
            $input['booking_day'] = $request->booking_day;
            $input['booking_time'] = $request->booking_time;
            $input['client_notice'] = $request->client_notice;
            $input['admin_notice'] = $request->admin_notice;
            $input['qrcode_field'] = bcrypt($new_user->id.str_random(40));
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
            if (!$appointment) {
                AppointmentModel::create($appoint);
            } else {
                $appointment->update($appoint);
            }
            $user_phone = User::find($new_booking->user_id);
            if ($reminder->sms_payment == 1) {
                SendCode::sendSMS($user_phone->phone_number, $appoint['appointment_title'], $appoint['appointment_body']);
            }

            $order = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->join('package_models', 'package_models.id', '=', 'booking_order_models.package_id')
                                     ->join('booking_status_models', 'booking_status_models.id', '=', 'booking_order_models.booking_status')
                                     ->join('timeslot_models', 'timeslot_models.service_id', '=', 'booking_order_models.service_id')
                                     ->where('booking_order_models.id', $new_booking->id)
                                     ->selectRaw('
                                        booking_order_models.id,
                                        users.name,
                                        booking_order_models.user_id,
                                        service_models.service_name,
                                        package_models.package_name,
                                        booking_order_models.booking_time,
                                        booking_order_models.booked_time,
                                        booking_order_models.client_notice,
                                        booking_order_models.paid_time,
                                        booking_order_models.arrived_time,
                                        booking_order_models.call_time,                                        
                                        booking_order_models.complete_time,
                                        booking_order_models.updated_at,
                                        booking_status_models.booking_status,
                                        booking_order_models.client_notice,
                                        CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime
                                        ')
                                     ->get()->first(); 
            return $order;
        }
    }

    public function statistic(Request $request, $key)
    {
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
        $booked = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', $key)
                                     ->where('booking_order_models.booking_status', 1)
                                     ->get()->count();
        $paid = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', $key)
                                     ->where('booking_order_models.booking_status', 4)
                                     ->get()->count();
        $bookednofee = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', $key)
                                     ->where('booking_order_models.booking_status', 3)
                                     ->get()->count();
        $completed = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', $key)
                                     ->where('booking_order_models.booking_status', 2)
                                     ->get()->count();
        $working = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', $key)
                                     ->where('booking_order_models.booking_status', 7)
                                     ->get()->count();
        $cancelledbyclient = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', $key)
                                     ->where('booking_order_models.booking_status', 5)
                                     ->get()->count();
        $cancelledbyadmin = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', $key)
                                     ->where('booking_order_models.booking_status', 6)
                                     ->get()->count();
        $outdated = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', $key)
                                     ->where('booking_order_models.booking_status', 9)
                                     ->get()->count();
        $refunded = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->where('branch_models.user_id', $key)
                                     ->where('booking_order_models.booking_status', 10)
                                     ->get()->count();
        $stautsChart = array();
        $stautsChart[0]=array(
            'name' => 'Booked',
            'y' => $booked
        );
        $stautsChart[1]=array(
            'name' => 'BookedNoFee',
            'y' => $bookednofee
        );
        $stautsChart[2]=array(
            'name' => 'Completed',
            'y' => $completed
        );
        $stautsChart[3]=array(
            'name' => 'Working',
            'y' => $working
        );
        $stautsChart[4]=array(
            'name' => 'CancelledByClient',
            'y' => $cancelledbyclient
        );
        $stautsChart[5]=array(
            'name' => 'CancelledByAdmin',
            'y' => $cancelledbyadmin
        );
        $stautsChart[6]=array(
            'name' => 'Paid',
            'y' => $paid
        );
        $stautsChart[7]=array(
            'name' => 'OutDated',
            'y' => $outdated
        );
        $stautsChart[8]=array(
            'name' => 'Refunded',
            'y' => $refunded
        );

        $signChart = array();
        $site_sign = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->where('branch_models.user_id', '=', $key)
                                     ->where('users.provider', '=', 'site')
                                     ->selectRaw('
                                        users.id
                                        ')
                                     ->groupBy('users.id')
                                     ->get()
                                     ->count();
        $facebook_sign = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->where('branch_models.user_id', '=', $key)
                                     ->where('users.provider', '=', 'facebook')
                                     ->selectRaw('
                                        users.id
                                        ')
                                     ->groupBy('users.id')
                                     ->get()
                                     ->count();
        $google_sign = BookingOrderModel::join('branch_models', 'branch_models.id', '=', 'booking_order_models.branch_id')
                                     ->join('users', 'users.id', '=', 'booking_order_models.user_id')
                                     ->where('branch_models.user_id', '=', $key)
                                     ->where('users.provider', '=', 'google')
                                     ->selectRaw('
                                        users.id
                                        ')
                                     ->groupBy('users.id')
                                     ->get()
                                     ->count();
        $signChart[0]=array(
            'name' => 'Handphone Only',
            'y' => $site_sign
        );   
        $signChart[1]=array(
            'name' => 'Facebook',
            'y' => $facebook_sign
        ); 
        $signChart[2]=array(
            'name' => 'Google',
            'y' => $google_sign
        );

        $ratingChart = array();
        $branches_rating = BranchModel::where('user_id', $key)->selectRaw('id, branch_name')->get();
        foreach ($branches_rating as $key => $value) {
            $ratings = [];
            
            $star5 = RatingStar::join('booking_order_models', 'booking_order_models.id', '=', 'rating_stars.booking_id')->
                                where('booking_order_models.branch_id', $value->id)->
                                where('rating_stars.rating_star', 5)->
                                get()->count();
            $star4 = RatingStar::join('booking_order_models', 'booking_order_models.id', '=', 'rating_stars.booking_id')->
                                where('booking_order_models.branch_id', $value->id)->
                                where('rating_stars.rating_star', 4)->
                                get()->count();
            $star3 = RatingStar::join('booking_order_models', 'booking_order_models.id', '=', 'rating_stars.booking_id')->
                                where('booking_order_models.branch_id', $value->id)->
                                where('rating_stars.rating_star', 3)->
                                get()->count();
            $star2 = RatingStar::join('booking_order_models', 'booking_order_models.id', '=', 'rating_stars.booking_id')->
                                where('booking_order_models.branch_id', $value->id)->
                                where('rating_stars.rating_star', 2)->
                                get()->count();
            $star1 = RatingStar::join('booking_order_models', 'booking_order_models.id', '=', 'rating_stars.booking_id')->
                                where('booking_order_models.branch_id', $value->id)->
                                where('rating_stars.rating_star', 1)->
                                get()->count();
            $ratings = [$star5, $star4, $star3, $star2, $star1];
            
            $ratingChart[$key] = array(
                'name' => $value->branch_name,
                'data' => $ratings
            );
        }

        $ratings = RatingStar::join('booking_order_models', 'booking_order_models.id', '=', 'rating_stars.booking_id')->
                            join('users', 'users.id', '=', 'rating_stars.user_id')->
                            join('service_models', 'service_models.id', '=', 'booking_order_models.service_id')->
                            where('service_models.user_id', $user_id)->
                            selectRaw('
                                rating_stars.id,
                                users.name,
                                rating_stars.rating_star,
                                rating_stars.rating_text,
                                CONCAT(booking_order_models.booking_day," ",booking_order_models.booking_time) AS appt_datetime,
                                rating_stars.created_at                                
                            ')->
                            latest('created_at')->paginate(5);

        if ($request->ajax()) {
            return view('system.dashboard.rating_pagination', ['ratings' => $ratings])->render();  
        }

        $current_services = ServiceModel::where('user_id', $user_id)->get();

        $payment_data = array();
        foreach ($current_services as $value) { 
            $single_data = array();           
            $single_data['name'] = $value->service_name;
            $single_data['data'] = array();
            $single_data['data'][0] = BookingOrderModel::where('service_id', $value->id)
                                     ->whereIn('booking_status', [1,4,7,2])
                                     ->get()->count();
            $single_data['data'][1] = BookingOrderModel::where('service_id', $value->id)
                                     ->where('booking_status', 1)
                                     ->get()->count();
            $single_data['data'][2] = BookingOrderModel::where('service_id', $value->id)
                                     ->where('reschedule_check', 1)
                                     ->get()->count();
            $single_data['data'][3] = BookingOrderModel::where('service_id', $value->id)
                                     ->where('booking_status', 5)
                                     ->get()->count();

            array_push($payment_data, $single_data);
        }

        return view('system.dashboard.statistic', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services_car', 'branch_services', 'stautsChart', 'signChart', 'ratingChart', 'ratings', 'payment_data'));
    }

    public function getChart(Request $request) {
        if ($request->ajax()) {
            $data['col'] = array();
            $data['row'] = array();
            if ($request->chart_type == 'month') {
                $current_month=$request->current_month;
                $current_year=$request->current_year; 
                $number_of_days=cal_days_in_month(CAL_GREGORIAN,$current_month,$current_year);
                $col_array = array();
                for($day=1;$day<=$number_of_days;$day++) {
                    if($day<10){
                        $day="0".$day;                
                    }
                    $days = $current_year.'-'.$current_month.'-'.$day;
                    array_push($col_array, $days);
                }

                $current_services = ServiceModel::where('user_id', $request->user_id)->get();
                $current_branches = BranchModel::where('user_id', $request->user_id)->get();

                $total_data = array();
                $service_revenue_data = array();
                foreach ($current_services as $value) {
                    $single_data = array();
                    $single_data['name'] = $value->service_name;
                    $single_data['data'] = array();
                    $revenue_data = array();
                    $revenue_data['name'] = $value->service_name;
                    $revenue_data['data'] = array();
                    foreach ($col_array as $key) {
                        $order_count = BookingOrderModel::where('service_id', $value->id)
                                             ->where('booking_day', $key)
                                             ->get()->count(); 
                        array_push($single_data['data'], $order_count);
                        $revenue_services = BookingOrderModel::where('service_id', $value->id)
                                                ->where('booking_day', $key)
                                                ->where('booking_status', 2)
                                                ->get();
                        $revenue = 0;
                        foreach ($revenue_services as $reve) {
                            $revenue = floatval($revenue)+floatval($reve->paid_price);
                        }
                        array_push($revenue_data['data'], $revenue);
                    }
                    array_push($total_data, $single_data);
                    array_push($service_revenue_data, $revenue_data);
                }
                $data['col'] = $col_array;
                $data['row'] = $total_data;
                $data['service_revenue'] = $service_revenue_data;

                $branch_data = array();
                $branch_revenue_data = array();
                foreach ($current_branches as $value) {
                    $single_data = array();
                    $single_data['name'] = $value->branch_name;
                    $single_data['data'] = array();
                    $revenue_data = array();
                    $revenue_data['name'] = $value->branch_name;
                    $revenue_data['data'] = array();
                    foreach ($col_array as $key) {
                        $order_count = BookingOrderModel::where('branch_id', $value->id)
                                             ->where('booking_day', $key)
                                             ->get()->count(); 
                        array_push($single_data['data'], $order_count);
                        $revenue_branches = BookingOrderModel::where('branch_id', $value->id)
                                                ->where('booking_day', $key)
                                                ->where('booking_status', 2)
                                                ->get();
                        $revenue = 0;
                        foreach ($revenue_branches as $reve) {
                            $revenue = floatval($revenue)+floatval($reve->paid_price);
                        }
                        array_push($revenue_data['data'], $revenue);
                    }
                    array_push($branch_data, $single_data);
                    array_push($branch_revenue_data, $revenue_data);
                }
                $data['branch'] = $branch_data;
                $data['branch_revenue'] = $branch_revenue_data;


            } else if ($request->chart_type == 'year') {
                $current_year=$request->current_year; 
                $col_array = array();
                for($month=1;$month<=12;$month++) {
                    if($month<10){
                        $month="0".$month;                
                    }
                    $months = $current_year.'-'.$month;
                    array_push($col_array, $months);
                }

                $current_services = ServiceModel::where('user_id', $request->user_id)->get();
                $current_branches = BranchModel::where('user_id', $request->user_id)->get();

                $total_data = array();
                $service_revenue_data = array();
                foreach ($current_services as $value) {
                    $single_data = array();
                    $single_data['name'] = $value->service_name;
                    $single_data['data'] = array();
                    $revenue_data = array();
                    $revenue_data['name'] = $value->service_name;
                    $revenue_data['data'] = array();
                    foreach ($col_array as $key) {
                        $order_count = BookingOrderModel::where('service_id', $value->id)
                                             ->whereYear('booking_day', explode('-', $key)[0])
                                             ->whereMonth('booking_day', explode('-', $key)[1])
                                             ->get()->count(); 
                        array_push($single_data['data'], $order_count);
                        $revenue_services = BookingOrderModel::where('service_id', $value->id)
                                                ->whereYear('booking_day', explode('-', $key)[0])
                                                ->whereMonth('booking_day', explode('-', $key)[1])
                                                ->where('booking_status', 2)
                                                ->get();
                        $revenue = 0;
                        foreach ($revenue_services as $reve) {
                            $revenue = floatval($revenue)+floatval($reve->paid_price);
                        }
                        array_push($revenue_data['data'], $revenue);
                    }
                    array_push($total_data, $single_data);
                    array_push($service_revenue_data, $revenue_data);
                }
                $data['col'] = $col_array;
                $data['row'] = $total_data;
                $data['service_revenue'] = $service_revenue_data;

                $branch_data = array();
                $branch_revenue_data = array();
                foreach ($current_branches as $value) {
                    $single_data = array();
                    $single_data['name'] = $value->branch_name;
                    $single_data['data'] = array();
                    $revenue_data = array();
                    $revenue_data['name'] = $value->branch_name;
                    $revenue_data['data'] = array();
                    foreach ($col_array as $key) {
                        $order_count = BookingOrderModel::where('branch_id', $value->id)
                                             ->whereYear('booking_day', explode('-', $key)[0])
                                             ->whereMonth('booking_day', explode('-', $key)[1])
                                             ->get()->count(); 
                        array_push($single_data['data'], $order_count);
                        $revenue_branches = BookingOrderModel::where('branch_id', $value->id)
                                                ->whereYear('booking_day', explode('-', $key)[0])
                                                ->whereMonth('booking_day', explode('-', $key)[1])
                                                ->where('booking_status', 2)
                                                ->get();
                        $revenue = 0;
                        foreach ($revenue_branches as $reve) {
                            $revenue = floatval($revenue)+floatval($reve->paid_price);
                        }
                        array_push($revenue_data['data'], $revenue);
                    }
                    array_push($branch_data, $single_data);
                    array_push($branch_revenue_data, $revenue_data);
                }
                $data['branch'] = $branch_data;
                $data['branch_revenue'] = $branch_revenue_data;
                
            }

            // var_dump($data['branch_revenue']);die();
            return $data;
        }
    }

    public function total_sms($key)
    {
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

        return view('system.dashboard.total-sms', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services_car', 'branch_services'));
    }


}
