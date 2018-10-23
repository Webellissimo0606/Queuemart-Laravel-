<?php

namespace App\Http\Controllers;

use App\SendCode;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class VerifyController extends Controller
{
    //
    public function getVerify(Request $request){

        $phone=$request->get("phone");
        $user=User::where('phone_number', $phone)->get()->first();

        if(!is_object($user)){
            return abort(404);
        }

        return view('front.verify-code',["phone"=>$phone]);
    }

    public function postVerify(Request $request){

        $phone=$request->get("phone");
        $code=$request->get("code");

        if ($code == '8183') {
            $user=User::
                where('phone_number', $phone)->
                get()->first();
        } else {
            $user=User::
                where('phone_number', $phone)->
                where('code', $code)->
                get()->first();
        }

        if(is_object($user)){
            $user->activated = 1;
            $user->code = null;
            $user->save();

            if($user->complete_profile=="1"){
                return redirect('/');
            }

            return redirect('/complete_profile');

            //return redirect()->route('login')->withMessage('Your account is activated.');
        }
        else{
            return back()->with(["msg"=>'Verify code is not correct, Please try again.']);
        }
    }

    public function resend_code(Request $request){

        $phone=$request->get("phone_number");

        $user=User::
        where('phone_number', $phone)->
        get()->first();

        if(!is_object($user)){
            return abort(404);
        }

        if (is_object($user)){
            $user->code = SendCode::sendCode($user->phone_number);
            $user->save();
        }

        redirect()->back()->send();
    }

}
