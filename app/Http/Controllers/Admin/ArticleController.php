<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Article;
use App\Http\Model\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Article::orderBy('art_id','desc')->paginate(3);
        //dd($data->links());
        return view('admin.article.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = (new Category())->tree();
        return view('admin.article.add',compact('data'));
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
            'art_title' => 'required',
            'art_content' => 'required',
        ];
        $message =[
            'art_title.required' => '文章名称不能为空',
            'art_content.required' => '文章内容不能为空',

        ];
        $validator = \Validator::make($input,$rules,$message);
        if ($validator->passes())
        {
            $res = Article::create($input);
            if($res){
                return redirect('admin/article');
            }else{
                return back()->with('errors','文章添加失败!请稍后重试');
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($art_id)
    {
        $field = Article::find($art_id);
        $data = (new Category())->tree();
        return view('admin.article.edit',compact('field','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $art_id)
    {
        $input = $request->except('_token','_method');
        //dd($input);
        $re = Article::where('art_id',$art_id)->update($input);
        if($re){
            return redirect('admin/article');
        }else{
            return back()->with('errors','文章更新失败，请稍后重试！');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($art_id)
    {
        $res = Article::where('art_id',$art_id)->delete();

        if($res){
            $data = [
                'status' => 0,
                'msg' => '文章删除成功!!'
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '文章信息删除失败,请稍后重试!!'
            ];
        }
        return $data;
    }
}
