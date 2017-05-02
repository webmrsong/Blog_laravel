<?php

namespace App\Http\Controllers\Home;



use App\Http\Model\Article;
use App\Http\Model\Category;
use App\Http\Model\Links;
use App\Http\Model\Navs;
use function Sodium\increment;

class IndexController extends CommonController
{
    public function index()
    {
        //站长推荐 点击率最高文章6张图片
        $pics = Article::orderby('art_view','desc')->take(6)->get();


        //文章列表页 分页5
        $data = Article::orderby('art_time','desc')->paginate(5);

        //友情链接
        $link = Links::all();
        return view('home.index',compact('pics','data','link'));
    }
    public function cate($cate_id)
    {
        Category::where('cate_id',$cate_id)->increment('cate_view');
        //读取该分类下的子分类
        $cate = Category::where('cate_pid',$cate_id)->get();
        //dd($cate);
        //文章列表页 分页5
        $data = Article::where('cate_id',$cate_id)->orderby('art_time','desc')->paginate(3);
        $field = Category::find($cate_id);
        return view('home.list',compact('field','data','cate'));
    }
    public function article($art_id)
    {
        Article::where('art_id',$art_id)->increment('art_view');
        //上一页下一页
        $article['pre'] = Article::where('art_id','<',$art_id)->orderby('art_id','desc')->first();
        $article['next'] = Article::where('art_id','>',$art_id)->orderby('art_id','asc')->first();

        $field = Article::join('category','article.cate_id','=','category.cate_id')->where('art_id',$art_id)->first();
        $data = Article::where('cate_id',$field->cate_id)->take(6)->get();
        return view('home.new',compact('field','article','data'));
    }
}
