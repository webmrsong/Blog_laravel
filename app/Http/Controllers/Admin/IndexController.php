<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends CommonController
{
    public function index()
    {
        return view('admin.index');
    }
    public function info()
    {
        return view('admin.info');
    }
    public function pass(Request $request)
    {
        if($input = $request->input())
        {
            $rules = [
              'password' => 'required|between:6,20|confirmed',
            ];
            $message =[
              'password.required' => '新密码不能为空',
                'password.between' => '新密码必须是6到20之间',
                'password.confirmed' => '新密码与确认密码不一致'
            ];
            $validator = \Validator::make($input,$rules,$message);
            if ($validator->passes())
            {
                $user =User::first();
                $_password =\Crypt::decrypt($user->user_pass);
                if($input['password_o'] == $_password)
                {
                    $user->user_pass = \Crypt::encrypt($input['password']);
                    $user->update();
                    return redirect('admin/info');

                }else{
                    return back()->with('errors','原密码错误!');
                }
            }else{
                return back()->withErrors($validator);
            }

        }else{
            return view('admin.pass');
        }
    }
}
