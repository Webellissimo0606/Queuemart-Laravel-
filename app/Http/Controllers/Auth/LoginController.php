<?php

namespace App\Http\Controllers\Auth;

use App\SendCode;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\BookingOrderModel;
use App\ServiceModel;
use App\PackageModel;
use App\AppointmentModel;
use App\PopupModel;

use Cookie;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request){

        $user_obj=Auth::user();

        if ($request->method() == "POST")
        {
            $login=Auth::attempt([
                "phone_number"=>$request->get('phone_number_1').$request->get('phone_number_2'),
                "password"=>$request->get('password'),
                "provider"=>"site",
            ]);

            if($login){
                Auth::login(Auth::user());
                $user_obj=Auth::user();

                $request->session()->save();

                if($user_obj->complete_profile=="1"){
                    if (session()->has('order')) {
                        $input['user_id'] = $user_obj->id;
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
                            } else {
                                $input['promo_id'] = 0;
                            } 
                        }
                        $input['promo_code'] = '0';
                        $input['booking_day'] = session('order')['booking_day'];
                        $input['booking_time'] = session('order')['booking_time'];
                        $input['client_notice'] = session('order')['client_notice'];
                        $input['manager_id'] = $user_obj->id;
                        $input['booked_time'] = now();
                        $input['arrived_check'] = 0;
                        $input['qrcode_field'] = bcrypt($user_obj->id.str_random(40));
                        $same_order = BookingOrderModel::where('user_id', '=', $user_obj->id)
                                                ->where('service_id', '=', session('order')['service_id'])
                                                ->where('branch_id', '=', session('order')['branch_id'])
                                                ->where('package_id', '=', session('order')['package_id'])
                                                ->where('booking_day', '=', session('order')['booking_day'])
                                                ->where('booking_time', '=', session('order')['booking_time'])
                                                ->get();
                        if (count($same_order) > 0) {
                            session()->forget('order');
                            return redirect()->intended('/alert_popup');
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
                                    SendCode::sendSMS($user_obj->phone_number, $appoint['appointment_title'], $appoint['appointment_body']);
                                }
                            }
                            session()->forget('order');
                        }
                    }
                    return redirect('/custom_bookings');
                }

                return redirect()->intended('/complete_profile');
            }
            else{
                redirect()->back()->with(["msg"=>"invalid phone or password"])->send();
            }
        }


    }

    public function facebook_login(){
        if(!session_id()) {
            session_start();
        }

        $app_id="1855016397899784";
        $app_secret="0665b00a783c6d24a9d15906011441e5";

        if(url("/")=="https://queuemart.me"){
            $app_id="478135799330383";
            $app_secret="585b4cb6bfc16fe97db39f9a8519b9c4";
        }

        $fb = new \Facebook\Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.2',
        ]);
        
        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email'];
        $loginUrl = $helper->getLoginUrl(url("/facebook_response"), $permissions);

        // echo ($loginUrl);

        return \Redirect::to($loginUrl);
    }

    public function facebook_response(Request $request){

        if(!session_id()) {
            session_start();
        }

        $app_id="1855016397899784";
        $app_secret="0665b00a783c6d24a9d15906011441e5";

        if(url("/")=="https://queuemart.me"){
            $app_id="478135799330383";
            $app_secret="585b4cb6bfc16fe97db39f9a8519b9c4";
        }

        $fb = new \Facebook\Facebook([
            'app_id' =>$app_id, // Replace {app-id} with your app id
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        }
        catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        }
        catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        $tokenMetadata->validateAppId($app_id);
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get('/me', $accessToken);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $me = $response->getGraphUser();

        $email=(isset($me['email']))?$me['email']:"";
        $provider_id=$me['id'];
        $username=(isset($me['name']))?$me['name']:"";

        return $this->register_via_api($request,$email,$provider_id,$username,"facebook");
    }

    public function google_login(Request $request){

        $email=$request->get("email");
        $provider_id=$request->get("provider_id");
        $username=$request->get("username");
        $provider="google";

        return $this->register_via_api($request,$email,$provider_id,$username,$provider);
    }

    public function register_via_api($request,$email,$provider_id,$username,$provider){
        $user_obj=User::
        where("provider",$provider)->
        where("provider_id",$provider_id)->get()->first();


        if(!is_object($user_obj)){
            $user_obj=User::create([
                "email"=>$email,
                "provider"=>$provider,
                "provider_id"=>$provider_id,
                "name"=>$username,
                "role_id"=>1
            ]);
        }


        Auth::login($user_obj,true);
        $user_obj=Auth::user();

        $request->session()->save();

        return $this->check_after_register_via_api($user_obj);
    }

    public function check_after_register_via_api($user_obj){
        if(empty($user_obj->activated)) {
            return redirect()->intended('/verify_phone');
        }
 
        if($user_obj->complete_profile=="1"){

            if (session()->has('order')) {
                $input['user_id'] = $user_obj->id;
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
                    } else {
                        $input['promo_id'] = 0;
                    }
                }
                $input['promo_code'] = '0';
                $input['booking_day'] = session('order')['booking_day'];
                $input['booking_time'] = session('order')['booking_time'];
                $input['client_notice'] = session('order')['client_notice'];
                $input['manager_id'] = $user_obj->id;
                $input['booked_time'] = now();
                $input['arrived_check'] = 0;
                $input['qrcode_field'] = bcrypt($user_obj->id.str_random(40));
                $same_order = BookingOrderModel::where('user_id', '=', $user_obj->id)
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
                            SendCode::sendSMS($user_obj->phone_number, $appoint['appointment_title'], $appoint['appointment_body']);
                        }
                    }
                    session()->forget('order');
                }
            }
            return redirect('/custom_bookings');
        }

        return redirect()->intended('/complete_profile');
    }
}
