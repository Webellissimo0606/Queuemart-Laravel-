<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\CompanyModel;
use App\ServiceModel;
use App\PackageModel;
use App\BranchModel;
use App\BranchServiceModel;
use App\TimeslotModel;
use App\Holiday;
use App\AdminRelationModel;


class BookingController extends Controller
{
    //company 
    public function companyInfo($key){
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
        $company = CompanyModel::where('user_id', $user_id)->get()->first();
        if (!isset($company)) {
            $input['user_id'] = $user_id;
            $input['company_url'] = $user_id;
            $company = CompanyModel::create($input);
        }
        $users = CompanyModel::select()->get();
        $holidays = Holiday::where('user_id', $user_id)->get();   
        $user_single = User::find($user_id); 
        $supports = User::where('role_id', '3')->get();
        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();  
        return view('system.booking.company', compact('user_obj', 'user_id', 'company', 'holidays', 'users', 'user_single', 'supports', 'branch_services', 'branch_services_car'));
    }

    public function saveCompany(Request $request){
        if ($request->ajax()) {
            $compare_company = CompanyModel::where('user_id', '!=', $request->user_id)->where('company_url', $request->company_url)->get()->first();
            if (isset($compare_company)) {
                return 'You cannot this url. Please enter other url.';
            } else {
                $company = CompanyModel::where('user_id', $request->user_id)->get()->first();
                if (isset($company)) {
                    $input = $request->all();
                    $input['company_image'] = $company->company_image;

                    if ($request->hasFile('company_image')){                
                        $input['company_image'] = '/upload/company_image/'.time().'.'.$request->company_image->getClientOriginalExtension();
                        $request->company_image->move(public_path('/upload/company_image'), $input['company_image']);
                    }

                    $company->update($input);
                } else {
                    $input = $request->all();
                    $input['company_image'] = '/upload/company_image/default.png';

                    if ($request->hasFile('company_image')){
                        $input['company_image'] = '/upload/company_image/'.time().'.'.$request->company_image->getClientOriginalExtension();
                        $request->company_image->move(public_path('/upload/company_image'), $input['company_image']);

                    }

                    CompanyModel::create($input);
                }
                
                return 'success';
            }
            
        }
    }

    public function updateCompany(Request $request){
        if ($request->ajax()) {            
            $company = CompanyModel::where('user_id', $request->user_id)->get()->first();
            if (isset($company)) {
                $input = $request->all();
                $company->update($input);
            }            
            return 'success';
        }
    }

    public function addHoliday(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();
            $holiday = Holiday::create($input);
            return response($holiday);
        }
    }

    public function updateHoliday(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();
            $holiday = Holiday::where('holiday_date',$request->holiday_date)->get()->first();
            $holiday->update($input);
            return response($holiday);
        }
    }

    public function deleteHoliday(Request $request) {
        if ($request->ajax()) {
            $holiday = Holiday::where('holiday_date',$request->holiday_date)->get()->first();
            Holiday::destroy($holiday->id);
            return response('success');
        }
    }
    // company end

    // branch
    public function branchInfo($key){
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
        $services = ServiceModel::where('user_id', $user_id)->get();
        $branches = BranchModel::where('user_id', $user_id)->get();
        $user_single = User::find($user_id);

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();   

        return view('system.booking.branches', compact('user_obj', 'user_id', 'services', 'users', 'branches', 'user_single', 'branch_services', 'branch_services_car'));
    }

    public function saveBranch(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $input['branch_image'] = '/upload/branch_image/default.png';

            if ($request->hasFile('branch_image')){
                $input['branch_image'] = '/upload/branch_image/'.time().'.'.$request->branch_image->getClientOriginalExtension();
                $request->branch_image->move(public_path('/upload/branch_image'), $input['branch_image']);
            }

            $branch = BranchModel::create($input); 
            return response($branch);
        }
    }

    public function readBranches(Request $request){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }
        if ($request->ajax()) {
            $branch = BranchModel::where('user_id', $request->user_id)->get()->first();        
            $services = "";
            $relations = "";
            if ($branch!="") {
                $services = ServiceModel::where('user_id', $branch->user_id)->get();
                $relations = BranchServiceModel::where('branch_id',$branch->id)->get();
            }
            
            return view('system.booking.branch_content', compact('branch', 'services', 'relations'));
        }           
    }

    public function readBranch(Request $request){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }
        if ($request->ajax()) {
            $branch = BranchModel::find($request->id);
            $services = ServiceModel::where('user_id', $branch->user_id)->get();
            $relations = BranchServiceModel::where('branch_id',$branch->id)->get();
            return view('system.booking.branch_content', compact('branch', 'services', 'relations'));
        }
    }

    public function updateBranch(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $branch = BranchModel::find($request->id);

            $input['branch_image'] = $branch->branch_image;

            if ($request->hasFile('branch_image')){                
                $input['branch_image'] = '/upload/branch_image/'.time().'.'.$request->branch_image->getClientOriginalExtension();
                $request->branch_image->move(public_path('/upload/branch_image'), $input['branch_image']);
            }

            $branch->update($input);
            return response($branch);
        }
    }

    public function insertRelation(Request $request) {
        if ($request->ajax()) {

            $input['branch_id'] = $request->branch_id; 

            $relations = BranchServiceModel::where('branch_id',$request->branch_id)->get();
            if (count($relations) > 0) {
                foreach ($relations as $value) {
                    BranchServiceModel::destroy($value->id); 
                }                           
            }
            if (count($request->service_ids) > 0) {
               $input['service_id'] = '';
                foreach ($request->service_ids as $service_id) {
                    $input['service_id'] = $service_id;
                    BranchServiceModel::create($input);
                }  
            }
            return response(['message' => 'Success']);
        }
    }
    // branch end
    
    // service
    public function serviceInfo($key){
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
        $services = ServiceModel::where('user_id', $user_id)->orderBy('show_sequence', 'ASC')->get();
        $user_single = User::find($user_id);

        $branch_services = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'seminar')                                           
                                        ->get()->first();     
        $branch_services_car = ServiceModel::where('user_id', $key)->
                                        where('service_type', 'carservice')                                           
                                        ->get()->first();  

        return view('system.booking.service', compact('user_obj', 'user_id', 'services', 'users', 'user_single', 'branch_services', 'branch_services_car'));
    }

    public function saveService(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $input['service_image'] = '/upload/service_image/default.png';

            if ($request->hasFile('service_image')){
                $input['service_image'] = '/upload/service_image/'.time().'.'.$request->service_image->getClientOriginalExtension();
                $request->service_image->move(public_path('/upload/service_image'), $input['service_image']);

            }

            $service = ServiceModel::create($input);
            return response($service);
        }
    }

    public function updateService(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $service = ServiceModel::find($request->id);

            $input['service_image'] = $service->service_image;

            if ($request->hasFile('service_image')){                
                $input['service_image'] = '/upload/service_image/'.time().'.'.$request->service_image->getClientOriginalExtension();
                $request->service_image->move(public_path('/upload/service_image'), $input['service_image']);
            }

            $service->update($input);
            return response($service);
        }
    }

    public function readServices(Request $request){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }
        if ($request->ajax()) {
            $service = ServiceModel::where('user_id', $request->user_id)->orderBy('show_sequence', 'ASC')->get()->first();
            $packages = "";
            if ($service!="") {
                $packages = PackageModel::where('service_id', $service->id)->get();
                $timeslot = TimeslotModel::where('service_id', $service->id)->get()->first();
                $input['service_id'] = $service->id; 
                if (!$timeslot) {
                    $timeslot = TimeslotModel::create($input);
                }
            }
            return view('system.booking.service_content', compact('service', 'packages', 'timeslot'));
        }
    }

    public function readService(Request $request){
        $user_obj=Auth::user();
        if(!is_object($user_obj)){
            return redirect()->intended('admin/login');
        }

        if($user_obj->role_id=="1"){    
            return redirect()->intended('admin/login');
        }
        if ($request->ajax()) {
            $service = ServiceModel::find($request->id);
            $packages = PackageModel::where('service_id', $request->id)->get();
            $timeslot = TimeslotModel::where('service_id', $request->id)->get()->first();
            $input['service_id'] = $service->id; 
            if (!$timeslot) {
                $timeslot = TimeslotModel::create($input);
            }            
            return view('system.booking.service_content', compact('service', 'packages', 'timeslot'));
        }
    }

    public function savePackage(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $package = PackageModel::create($input);
            return response($package);
        }
    }

    public function saveFixedHours(Request $request){
        
        if ($request->ajax()) {            
            $input = $request->all();
            if (!isset($request->monday_active)) {
                $input['monday_active'] = 0;
            }
            if (!isset($request->tuesday_active)) {
                $input['tuesday_active'] = 0;
            }
            if (!isset($request->wednesday_active)) {
                $input['wednesday_active'] = 0;
            }
            if (!isset($request->thursday_active)) {
                $input['thursday_active'] = 0;
            }
            if (!isset($request->friday_active)) {
                $input['friday_active'] = 0;
            }
            if (!isset($request->saturday_active)) {
                $input['saturday_active'] = 0;
            }
            if (!isset($request->sunday_active)) {
                $input['sunday_active'] = 0;
            } 

            $input['monday_val'] = str_replace(" ","",$request->monday_val);
            $input['tuesday_val'] = str_replace(" ","",$request->tuesday_val);
            $input['wednesday_val'] = str_replace(" ","",$request->wednesday_val);
            $input['thursday_val'] = str_replace(" ","",$request->thursday_val);
            $input['friday_val'] = str_replace(" ","",$request->friday_val);
            $input['saturday_val'] = str_replace(" ","",$request->saturday_val);
            $input['sunday_val'] = str_replace(" ","",$request->sunday_val);

            $timeslot = TimeslotModel::find($request->id);
            $timeslot->update($input);
            return response($timeslot);
        }
    } 

    public function updateTimeslot(Request $request){
        
        if ($request->ajax()) {            
            $input['duration_show'] = $request->duration_show;
            $input['service_duration'] = $request->service_duration;
            $input['start_time'] = $request->service_start_time;

            $timeslot = TimeslotModel::where('service_id', $request->service_id);
            $timeslot->update($input);
            return 'success';
        }
    } 

    function calculation_session($start, $end, $duration) {
        $minutes_s = $this->minutes($start);
        $minutes_e = $this->minutes($end);
        $number = ($minutes_e - $minutes_s)/$duration;
        return $number;
    }

    function minutes($time){
        $time = explode(':', $time);
        return ($time[0]*60) + ($time[1]) + ($time[2]/60);
    }

    public function editPackage(Request $request){
        if ($request->ajax()) {            
            $package = PackageModel::find($request->id);
            return response($package);
        }
    } 

    public function deletePackage(Request $request){
        if ($request->ajax()) {            
            PackageModel::destroy($request->id);
            return 'success';
        }
    }

    public function deleteService(Request $request){
        if ($request->ajax()) {            
            ServiceModel::destroy($request->id);
            $packages = PackageModel::where('service_id', '=', $request->id)->get();
            foreach ($packages as $value) {
                PackageModel::destroy($value->id);
            }
            $branch_services = BranchServiceModel::where('service_id', '=', $request->id)->get();
            foreach ($branch_services as $value) {
                BranchServiceModel::destroy($value->id);
            }
            $timeslots = TimeslotModel::where('service_id', '=', $request->id)->get();
            foreach ($timeslots as $value) {
                TimeslotModel::destroy($value->id);
            }
            return 'success';
        }
    }  

    public function deleteBranch(Request $request){
        if ($request->ajax()) {            
            BranchModel::destroy($request->id);            
            $branch_services = BranchServiceModel::where('branch_id', '=', $request->id)->get();
            foreach ($branch_services as $value) {
                BranchServiceModel::destroy($value->id);
            }
            return 'success';
        }
    }

    public function updatePackage(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $package = PackageModel::find($request->id);
            $package->update($input);
            return response($package);
        }
    }
    // service end 


}
