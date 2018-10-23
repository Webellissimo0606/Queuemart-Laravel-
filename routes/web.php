<?php

use Jenssegers\Agent\Agent;

$agent = new Agent();
$isDesktop = $agent->isDesktop();
//Temporary
Route::get('/send.php',function(){
    return view('send');
});
//Temporary
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['web']], function () {

    Route::get('/facebook_login','Auth\LoginController@facebook_login');
    Route::get('/facebook_response','Auth\LoginController@facebook_response');
});


Auth::routes();

Route::group(['prefix' =>'admin'], function () {

    //IndexController(Dashboard)
    Route::get('/','IndexController@index');    

    Route::get('/login','IndexController@login');

    Route::get('/logout','IndexController@logout');

    Route::get('/getEmployee','IndexController@getEmployee');

    Route::get('/getMember','IndexController@getMember');

    Route::get('/getService','IndexController@getService');
    
    Route::get('/deleteEmployee','IndexController@deleteEmployee');
    
    Route::post('/loginAdmin','IndexController@loginAdmin');

    Route::post('/editProfile','IndexController@editProfile');

    Route::get('/resetPassword','IndexController@resetPassword');
    
    Route::post('/addEmployee','IndexController@addEmployee');

    Route::post('/editEmployee','IndexController@editEmployee');

    //Customer Controller        

    Route::get('bookingHistory', ['as'=>'bookingHistory','uses'=>'Customers@bookingHistory']); 

    Route::get('editCustomer', ['as'=>'editCustomer','uses'=>'Customers@editCustomer']); 

    Route::post('updateCustomer', ['as'=>'updateCustomer','uses'=>'Customers@updateCustomer']); 

    //Booking Controller    
    //company 
    // Route::get('company', ['uses'=>'BookingController@company']);

    Route::post('saveCompany', ['as'=>'saveCompany','uses'=>'BookingController@saveCompany']);
    
    Route::post('updateCompany', ['as'=>'updateCompany','uses'=>'BookingController@updateCompany']);

    Route::post('addHoliday', ['as'=>'addHoliday','uses'=>'BookingController@addHoliday']); 

    Route::post('updateHoliday', ['as'=>'updateHoliday','uses'=>'BookingController@updateHoliday']); 

    Route::get('deleteHoliday', ['as'=>'deleteHoliday','uses'=>'BookingController@deleteHoliday']); 
    // company end

    // branch
    // Route::get('branches', ['uses'=>'BookingController@branches']);     

    Route::post('saveBranch', ['as'=>'saveBranch','uses'=>'BookingController@saveBranch']);    

    Route::get('readBranch', ['as'=>'readBranch','uses'=>'BookingController@readBranch']); 

    Route::get('readBranches', ['as'=>'readBranches','uses'=>'BookingController@readBranches']);    

    Route::post('updateBranch', ['as'=>'updateBranch','uses'=>'BookingController@updateBranch']);

    Route::get('insertRelation', ['as'=>'insertRelation','uses'=>'BookingController@insertRelation']);

    // branch end
    // Route::get('getService', ['as' => 'getService', 'uses' => 'BookingController@getService']);
    // service and package
    // Route::get('service', ['uses'=>'BookingController@service']);    

    Route::post('saveService', ['as'=>'saveService','uses'=>'BookingController@saveService']);    

    Route::get('readService', ['as'=>'readService','uses'=>'BookingController@readService']); 

    Route::get('readServices', ['as'=>'readServices','uses'=>'BookingController@readServices']);    

    Route::post('updateService', ['as'=>'updateService','uses'=>'BookingController@updateService']);

    Route::post('savePackage', ['as'=>'savePackage','uses'=>'BookingController@savePackage']);

    Route::post('updatePackage', ['as' => 'updatePackage', 'uses' => 'BookingController@updatePackage']);

    Route::get('updateTimeslot', ['as' => 'updateTimeslot', 'uses' => 'BookingController@updateTimeslot']);
    
    Route::post('saveFixedHours', ['as' => 'saveFixedHours', 'uses' => 'BookingController@saveFixedHours']);

    Route::get('editPackage', ['as' => 'editPackage', 'uses' => 'BookingController@editPackage']);

    Route::get('deletePackage', ['as' => 'deletePackage', 'uses' => 'BookingController@deletePackage']);

    Route::get('deleteService', ['as' => 'deleteService', 'uses' => 'BookingController@deleteService']);
    
    Route::get('deleteBranch', ['as' => 'deleteBranch', 'uses' => 'BookingController@deleteBranch']);
    // service and package name

    //BroadcastController  
    // panel news    

    Route::get('readPanelNews', ['as' => 'readPanelNews', 'uses' => 'BroadcastController@readPanelNews']);

    Route::post('addPanelNews', ['as' => 'addPanelNews', 'uses' => 'BroadcastController@addPanelNews']);

    Route::get('deletePanelNews', ['as' => 'deletePanelNews', 'uses' => 'BroadcastController@deletePanelNews']);

    Route::post('updatePanelNews', ['as' => 'updatePanelNews', 'uses' => 'BroadcastController@updatePanelNews']);

    Route::get('editPanelNews', ['as' => 'editPanelNews', 'uses' => 'BroadcastController@editPanelNews']);
    // panel news end

    // floating news    

    Route::get('readFloatNews', ['as' => 'readFloatNews', 'uses' => 'BroadcastController@readFloatNews']);

    Route::post('addFloatNews', ['as' => 'addFloatNews', 'uses' => 'BroadcastController@addFloatNews']);

    Route::get('deleteFloatNews', ['as' => 'deleteFloatNews', 'uses' => 'BroadcastController@deleteFloatNews']);

    Route::post('updateFloatNews', ['as' => 'updateFloatNews', 'uses' => 'BroadcastController@updateFloatNews']);

    Route::get('editFloatNews', ['as' => 'editFloatNews', 'uses' => 'BroadcastController@editFloatNews']);

    Route::get('activeFloatNews', ['as' => 'activeFloatNews', 'uses' => 'BroadcastController@activeFloatNews']);
    // floating news end    

    // popup     

    Route::post('updatePopup', ['as' => 'updatePopup', 'uses' => 'BroadcastController@updatePopup']);
    // popup end

    Route::get('getCalendar', ['as'=>'getCalendar','uses'=>'IndexController@getCalendar']);

    Route::get('getTimeslot', ['as'=>'getTimeslot','uses'=>'IndexController@getTimeslot']);
    
    Route::get('updateOrder', ['as'=>'updateOrder','uses'=>'IndexController@updateOrder']);
    
    Route::get('updateAdminNotice', ['as'=>'updateAdminNotice','uses'=>'IndexController@updateAdminNotice']);

    Route::get('updateClientNotice', ['as'=>'updateClientNotice','uses'=>'IndexController@updateClientNotice']);
    
    Route::get('getChart', ['as'=>'getChart','uses'=>'IndexController@getChart']);
    
    Route::get('updateArrivedCheck', ['as'=>'updateArrivedCheck','uses'=>'IndexController@updateArrivedCheck']);
    
    Route::get('modalDetail', ['as'=>'modalDetail','uses'=>'IndexController@modalDetail']);

    Route::get('getPrice', ['as'=>'getPrice','uses'=>'IndexController@getPrice']);

    Route::get('modalDetailSeminar', ['as'=>'modalDetailSeminar','uses'=>'IndexController@modalDetailSeminar']);
    
    Route::get('addAppointment', ['as'=>'addAppointment','uses'=>'IndexController@addAppointment']);

    Route::get('addAppointmentSeminar', ['as'=>'addAppointmentSeminar','uses'=>'IndexController@addAppointmentSeminar']);
    
    Route::get('addAppointmentClass', ['as'=>'addAppointmentClass','uses'=>'IndexController@addAppointmentClass']);

    Route::get('addUserAppointmentClass', ['as'=>'addUserAppointmentClass','uses'=>'IndexController@addUserAppointmentClass']);

    Route::get('company_support', ['as'=>'company_support','uses'=>'IndexController@company_support']);
    
    Route::get('company_member', ['as'=>'company_member','uses'=>'IndexController@company_member']);
    
    Route::get('readBookingSeminar', ['as'=>'readBookingSeminar','uses'=>'IndexController@readBookingSeminar']);

    Route::post('checkQRCode', ['as'=>'checkQRCode','uses'=>'Customers@checkQRCode']);

    Route::get('getBookingList', ['as'=>'getBookingList','uses'=>'Customers@getBookingList']);

    Route::get('getBookingListSeminar', ['as'=>'getBookingListSeminar','uses'=>'Customers@getBookingListSeminar']);

    Route::get('getWorkingList', ['as'=>'getWorkingList','uses'=>'Customers@getWorkingList']);

    Route::post('queuescreenManagement', ['as'=>'queuescreenManagement','uses'=>'Customers@queuescreenManagement']);

    Route::get('{key}/','IndexController@dashboard')->name('dashboard');

    Route::get('{key}/bookings','IndexController@bookings');

    Route::get('{key}/bookings_seminar','IndexController@bookings_seminar');

    Route::get('{key}/booking_seminar/{select_id}','IndexController@booking_seminar_con');

    Route::get('{key}/statistic','IndexController@statistic')->name('dash_statistic');

    Route::get('{key}/total_sms','IndexController@total_sms')->name('dash_total_sms');

    Route::get('{key}/company', ['as'=>'companyInfo','uses'=>'BookingController@companyInfo']); 

    Route::get('{key}/branches', ['as'=>'branchInfo','uses'=>'BookingController@branchInfo']);

    Route::get('{key}/service', ['as'=>'serviceInfo','uses'=>'BookingController@serviceInfo']); 

    Route::get('{key}/admin_role','Customers@admin_role')->name('admin_role');

    Route::get('{key}/member_management','Customers@member_management')->name('member_management');

    Route::get('{key}/qrcode_scan','Customers@qrcode_scan')->name('qrcode_scan');

    Route::get('{key}/qrcode_scan_screen/{branch_id}','Customers@qrcode_scan_screen')->name('qrcode_scan_screen');

    Route::get('{key}/qrcode_scan_screen_seminar/{branch_service_id}','Customers@qrcode_scan_screen_seminar')->name('qrcode_scan_screen_seminar');

    Route::get('{key}/client_list','Customers@client_list')->name('client_list');

    Route::get('{key}/booking_log','Customers@booking_log')->name('booking_log');

    Route::get('{key}/referral','Customers@referral')->name('referral');   

    Route::get('{key}/panel_news','BroadcastController@panel_news');

    Route::get('{key}/tricker_news','BroadcastController@tricker_news');

    Route::get('{key}/pop_up','BroadcastController@pop_up');

    Route::get('{key}/message_log','BroadcastController@message_log');

    Route::get('{key}/feedback_form','BroadcastController@feedback_form');
    
});


Route::get('/home',function(){
    $user_obj=\Illuminate\Support\Facades\Auth::user();

    if(empty($user_obj->activated)) {
        return redirect()->intended('/verify_phone');
    }

    if($user_obj->complete_profile=="1"){
        
        return redirect()->intended('/custom_bookings');
    }

    return redirect()->intended('/complete_profile');
});

Route::get('/logout',function(){
    \Illuminate\Support\Facades\Auth::logout();
    return redirect("/");
});

Route::get('/custom_bookings',function(){
    if (Cookie::get('custom_url') !== false) {
        return redirect(Cookie::get('custom_url').'/bookings');
    } else {
        return redirect('/');
    }
    
});

Route::get('booking_payment', 'Customers@booking_payment');//New added code

Route::post('backend-iPay', 'Customers@backend_code');//New added code

Route::post('response-iPay', 'Customers@response_code');//New added code

Route::get('/complete_profile','HomeController@complete_profile');
Route::post('/complete_profile','HomeController@complete_profile');

Route::get('/google_login','Auth\LoginController@google_login');

Route::post('userImageUpload', ['as'=>'userImageUpload','uses'=>'Customers@userImageUpload']); 
 
Route::get('booking_cancel', ['as'=>'booking_cancel','uses'=>'Customers@booking_cancel']); 

Route::get('reminderUser', ['as'=>'reminderUser','uses'=>'Customers@reminderUser']); 

Route::get('/verify_code','Customers@verify_code')->name('verify_code');

Route::get('/resend_code','VerifyController@resend_code')->name('resend_code');

Route::get('/verify_phone','Customers@verify_phone')->name('verify_phone');
Route::post('/verify_phone','Customers@verify_phone')->name('verify_phone');

Route::get('/client_detail','Customers@client_detail')->name('client_detail');

Route::get('deleteAppointments', ['as'=>'deleteAppointments','uses'=>'Customers@deleteAppointments']);

Route::get('getAppointments', ['as'=>'getAppointments','uses'=>'Customers@getAppointments']);

Route::get('getCalendar', ['as'=>'getCalendar','uses'=>'Customers@getCalendar']);

Route::get('alert_popup', ['as'=>'alert_popup','uses'=>'Customers@alert_popup']);

Route::get('ratingStar', ['as'=>'ratingStar','uses'=>'Customers@ratingStar']);

Route::get('verify','VerifyController@getVerify')->name('verify');
Route::post('verify','VerifyController@postVerify')->name('verify');

Route::get('/','Customers@index')->name('home');

Route::get('{company}/','Customers@indexCompany');

// if($isDesktop == true){
//     Route::get('/{key}/', function () {
//         return redirect()->intended('/{key}/desktop');
//     });
// } else {
//     Route::get('/{key}/', function () {
//         return redirect()->intended('/{key}/');
//     });
// }

Route::get('{company}/desktop','Customers@desktop');

Route::get('{company}/branch/{key}','Customers@branch');

Route::get('{company}/about/{key}','Customers@about');

Route::get('{company}/book/{branch_id}/{key}','Customers@book');

Route::get('{company}/reschedule/{key}','Customers@reschedule');

// here
Route::get('{company}/bookings','Customers@bookings')->name('bookings'); 

Route::get('{company}/credits','Customers@credits')->name('credits'); 

Route::get('{company}/order', ['as'=>'order','uses'=>'Customers@order']);

Route::get('{company}/reschedule_order', ['as'=>'reschedule_order','uses'=>'Customers@reschedule_order']);

Route::get('{company}/rating/{key}', ['as'=>'rating','uses'=>'Customers@rating']);






