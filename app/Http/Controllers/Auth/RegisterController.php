<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use \App\SendCode;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/verify_code';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['phone_number'] = $data['phone_number_1'].$data['phone_number_2'];
        return Validator::make($data, [
            'phone_number' => 'required|min:7|max:15|unique:users,phone_number',
            'password' => 'required|string|min:3',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // $data['phone_number']=$data['phone_number_2'];

        $user=User::create([
            'password' => Hash::make($data['password']),
            'phone_number'=>$data['phone_number_1'].$data['phone_number_2'],
            'activated'    =>  0,
            'role_id'    =>  1,
            "provider"=>"site"
        ]);

        if (is_object($user)){
            $user->code = SendCode::sendCode($user->phone_number);
            $user->save();
        }

        return $user;
    }

    protected function register(Request $request){

        $this->validator($request->all())->validate();

        event(new Registered(
            $user = $this->create($request->all())
        ));

        //return $this->registered($request, $user) ?:redirect('/register?phone='.$request->phone_number);

        return \redirect("/verify?phone=$request->phone_number_1"."$request->phone_number_2")->send();
    }


   
}
