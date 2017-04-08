<?php

namespace App\Http\Controllers\Admin;

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
             echo 'ok';

        }else{
            return view('admin.login');
        }

    }
    public function code()
    {
        $code = new Code;
        $code->make();
    }

}
