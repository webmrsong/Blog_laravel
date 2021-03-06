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
            return redirect('admin/index');


        }else{

            return view('admin.login');
        }

    }
    public function code()
   {
       $code = new Code;
        $code->make();
   }
    //退出后台,清空sesion
    public function quit()
    {
        session(['user'=> null]);
        //dd(session('user'));
        return redirect('admin/login');
    }

}
