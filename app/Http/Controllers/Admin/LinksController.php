<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Links;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\DomCrawler\Link;

class LinksController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =Links::orderBy('link_order','asc')->get();
        return view('admin.links.index',compact('data'));
    }
    public function changeOrder(Request $request)
    {
        $input = $request->all();
        $link = Links::find($input['link_id']);
        $link->link_order = $input['link_order'];
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
        return view('admin.links.add');
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
            'link_name' => 'required',
            'link_url' => 'required',
        ];
        $message =[
            'link_name.required' => '链接名称不能为空',
            'link_url.required' => '链接不能为空',

        ];
        $validator = \Validator::make($input,$rules,$message);
        if ($validator->passes())
        {
            $res = Links::create($input);
            if($res){
                return redirect('admin/links');
            }else{
                return back()->with('errors','友情链接填充失败!请稍后重试');
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
    public function edit($link_id)
    {
        $field = Links::find($link_id);

        return view('admin/links/edit',compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $link_id)
    {
        $input = $request->except('_token','_method');
        $res = Links::where('link_id',$link_id)->update($input);
        if ($res)
        {
            return redirect('admin/links');
        }else{
            return back()->with('errors','分类更新失败!请稍后重试');
    }}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($link_id)
    {
        $res = Links::where('link_id',$link_id)->delete();

        if($res){
            $data = [
                'status' => 0,
                'msg' => '分类信息删除成功!!'
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '分类信息删除失败,请稍后重试!!'
            ];
        }
        return $data;
    }
}
