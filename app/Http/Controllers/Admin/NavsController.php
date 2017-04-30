<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\navs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\DomCrawler\Link;

class navsController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =Navs::orderBy('nav_order','asc')->get();
        return view('admin.navs.index',compact('data'));
    }
    public function changeOrder(Request $request)
    {
        $input = $request->all();
        $link = Navs::find($input['nav_id']);
        $link->nav_order = $input['nav_order'];
        $res = $link->update();
        if ($res){
            $data =[
                'ststus' => 0,
                'msg' => '分类更新排序成功'
            ];
        }else{
            $data =[
                'ststus' => 1,
                'msg' => '分类更新排序失败,请稍后重试'
            ];
        }
        return $data;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.navs.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $rules = [
            'nav_name' => 'required',
            'nav_url' => 'required',
        ];
        $message =[
            'nav_name.required' => '导航名称不能为空',
            'nav_url.required' => '导航地址不能为空',

        ];
        $validator = \Validator::make($input,$rules,$message);
        if ($validator->passes())
        {
            $res = Navs::create($input);
            if($res){
                return redirect('admin/navs');
            }else{
                return back()->with('errors','自定义导航填充失败!请稍后重试');
            }
        }else{
            return back()->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nav_id)
    {
        $field = Navs::find($nav_id);

        return view('admin/navs/edit',compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nav_id)
    {
        $input = $request->except('_token','_method');
        $res = Navs::where('nav_id',$nav_id)->update($input);
        if ($res)
        {
            return redirect('admin/navs');
        }else{
            return back()->with('errors','导航更新失败!请稍后重试');
    }}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nav_id)
    {
        $res = Navs::where('nav_id',$nav_id)->delete();

        if($res){
            $data = [
                'status' => 0,
                'msg' => '导航信息删除成功!!'
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '导航信息删除失败,请稍后重试!!'
            ];
        }
        return $data;
    }
}
