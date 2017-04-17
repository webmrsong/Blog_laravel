<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoryController extends commonController
{
    /**
     * Display a listing of the resource.
     *
     * 分类列表
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorys = (new Category)->tree();
        return view('admin.category.index')->with('data',$categorys);

    }

    public function changeOrder(Request $request)
    {
        $input = $request->all();
        $cate = Category::find($input['cate_id']);
        $cate->cate_order = $input['cate_order'];
        $res = $cate->update();
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
     *创建分类
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Category::where('cate_pid',0)->get();
        return view('admin/category/add',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *添加分类  接受post数据
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $rules = [
            'cate_name' => 'required',
        ];
        $message =[
            'cate_name.required' => '分类名称不能为空',

        ];
        $validator = \Validator::make($input,$rules,$message);
        if ($validator->passes())
        {
            $res = Category::create($input);
            if($res){
                return redirect('admin/category');
            }else{
                return back()->with('errors','分类填充失败!请稍后重试');
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
    public function edit($cate_id)
    {
        $field = Category::find($cate_id);
        $data = Category::where('cate_pid',0)->get();
        return view('admin/category/edit',compact('field','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cate_id)
    {
        $input = $request->except('_token','_method');
        $res = Category::where('cate_id',$cate_id)->update($input);
        if ($res)
        {
            return redirect('admin/category');
        }else{
            return back()->with('errors','分类更新失败!请稍后重试');
        }
    }

    /**
     * Remove the specified resource from storage.
     *删除分类
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($cate_id)
    {
        $res = Category::where('cate_id',$cate_id)->delete();
        Category::where('cate_pid',$cate_id)->update(['cate_pid' => 0]);
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
