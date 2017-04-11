<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use app\libs\code\Code;

class LoginController extends CommonController
{
    public function login(Request $request)
    {
        if ( $input = $request->Input()){
            $code = new Code;
            $_code = $code->get();
            if(strtoupper($input['code']) != $_code)
            {
                return back()->with('msg','验证码错误');
            }
            $user = User::first();
            if($user->user_name != $input['user_name'] || \Crypt::decrypt($user->user_pass) != $input['user_pass'])
            {
                return back()->with('msg','用户名或者密码错误');
            }
            session(['user'=>$user]);
            dd(session('user'));


        }else{

            return view('admin.login');
        }

    }
    public function code()
    {
        $code = new Code;
        $code->make();
    }

     public function crypt()
    {
        $str = '123456';

        echo \Crypt::encrypt($str);

    }
}