<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Config;
use Validator;
use Session;
use App\Http\Helper\Helper;

class CommonAuthController extends Controller
{
    public function Login(Request $request){
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(),[
                    'email'      => 'required',
                    'password'   =>'required',
                ],

                [
                    'email.required'    => 'Please enter email.',
                    'password.required'    => 'Please enter password.',
                ]
            );

            if ($validator->fails())
            {
                $messages = $validator->messages();
                foreach ($messages->all() as $message)
                {
                    return response()->json(['errors'=>$validator->errors()->all()]);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $email = $request->email;
            $password = $request->password;
            
            if(Auth::attempt(['email' => $email, 'password' => $password]))
            {
                if(Auth::user()->role_id == Config::get('constant.roles.Admin'))
                {  
                    return redirect('/home');
                }else if(Auth::user()->role_id == Config::get('constant.roles.User')){

                    return redirect('/dashboard');
                }
                else
                {
                    \Auth::logout();
                    return redirect()->back();
                }
            }
            else
            {
                return redirect()->back();
            }
        }else{
            return view('login');
        }
    }


    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
