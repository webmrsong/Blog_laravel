<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('Filedata');
        if ($file->isValid())
        {
            $realPath = $file->getRealPath();
            $extension = $file->getClientOriginalExtension();
            $newName = date('YmdHis').mt_rand(100,999).'.'.$extension;
            $path = $file->move(base_path().'/public/uploads',$newName);
            $filePath = 'uploads/'.$newName;
            return $filePath;
        }
    }
}
