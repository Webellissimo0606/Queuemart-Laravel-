<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Panel_news;
use App\Float_news;
use App\PopupModel; 
use App\ServiceModel; 
use App\CompanyModel; 
use App\AdminRelationModel; 

class BroadcastController extends Controller
{
    //panel news
    public function panel_news($key){
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

        return view('system.broadcast.panel-news', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car'));

    }

    public function readPanelNews(Request $request){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }
        $panel_news = Panel_news::where('user_id', $request->user_id)->get();
        return view('system.broadcast.panel-news-list', compact('panel_news'));
    }

    public function addPanelNews(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $input['news_image'] = '/upload/news_image/default.png';

            if ($request->hasFile('news_image')){
                $input['news_image'] = '/upload/news_image/'.time().'.'.$request->news_image->getClientOriginalExtension();
                $request->news_image->move(public_path('/upload/news_image'), $input['news_image']);

            }

            $panel_news = Panel_news::create($input);
            return response($panel_news);
        }
    }

    public function deletePanelNews(Request $request){
        if ($request->ajax()) {
            Panel_news::destroy($request->id);
            return response(['message' => 'Panel News deleted successfully.']);
        }
    }

    public function editPanelNews(Request $request){
        if ($request->ajax()) {
            $panel_news = Panel_news::find($request->id);
            return response($panel_news);
        }
    }

    public function updatePanelNews(Request $request){
        if ($request->ajax()) {

            $input = $request->all();
            $panel_news = Panel_news::find($request->id);

            $input['news_image'] = $panel_news->news_image;

            if ($request->hasFile('news_image')){                
                $input['news_image'] = '/upload/news_image/'.time().'.'.$request->news_image->getClientOriginalExtension();
                $request->news_image->move(public_path('/upload/news_image'), $input['news_image']);
            }

            $panel_news->update($input);
            return response($panel_news);
        }
    }
    // panel news end
    
    // float news
    public function tricker_news($key){
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

        return view('system.broadcast.tricker-news', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car'));
    }

    public function readFloatNews(Request $request){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }
        $float_news = Float_news::where('user_id', $request->user_id)->get();
        return view('system.broadcast.float-news-list', compact('float_news'));
    }

    public function addFloatNews(Request $request){
        if ($request->ajax()) {
            $input = $request->all();            
            $float_news = Float_news::create($input);
            return response($float_news);
        }
    }

    public function deleteFloatNews(Request $request){

        if ($request->ajax()) {
            Float_news::destroy($request->id);
            return response(['message' => 'Float News deleted successfully.']);
        }
    }

    public function editFloatNews(Request $request){
        if ($request->ajax()) {
            $float_news = Float_news::find($request->id);
            return response($float_news);
        }
    }

    public function updateFloatNews(Request $request){
        if ($request->ajax()) {

            $input = $request->all();
            $input['float_active'] = 0;
            $float_news = Float_news::find($request->id);
            $float_news->update($input);
            return response($float_news);
        }
    }

    public function activeFloatNews(Request $request){
        if ($request->ajax()) {

            $float_news = Float_news::where('user_id', $request->user_id)->get();
            if (count($float_news) > 0) {
                foreach ($float_news as $float_new) {
                    $active['float_active'] = '0';
                    $float_new->update($active);
                }
            }
            $input = $request->all();
            $float_new_active = Float_news::find($request->id);
            $float_new_active->update($input);
            return response($float_new_active);
        }
    }
    // float news end
    
    // popup
    public function pop_up($key){
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

        $popup = PopupModel::where('user_id', $key)->get();
        if (count($popup) == 0) {
            $input['user_id'] = $key;
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
        } else {
            $popup = $popup[0];
        }

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();  

        return view('system.broadcast.pop-up', compact('popup', 'user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car'));
    }

    public function updatePopup(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            if (!isset($request->sms_after_booking)) {
                $input['sms_after_booking'] = 0;
            }
            if (!isset($request->sms_payment)) {
                $input['sms_payment'] = 0;
            }
            if (!isset($request->sms_cancel_admin)) {
                $input['sms_cancel_admin'] = 0;
            }
            if (!isset($request->sms_cancel_client)) {
                $input['sms_cancel_client'] = 0;
            }
            if (!isset($request->sms_near_appt)) {
                $input['sms_near_appt'] = 0;
            }
            $popup = PopupModel::find($request->id);            
            $popup->update($input);
            return response($popup);

        }
    }
    // popup news

    public function message_log($key){
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

        return view('system.broadcast.message-log', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car'));
    }
    
    public function feedback_form($key){
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

        return view('system.broadcast.feedback-form', compact('user_obj', 'user_id', 'users', 'user_single', 'branch_services', 'branch_services_car'));
    }

}
