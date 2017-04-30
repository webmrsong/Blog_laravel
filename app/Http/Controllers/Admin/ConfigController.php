<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Config;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\DomCrawler\Link;

class ConfigController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data =Config::orderBy('conf_order','asc')->get();
        foreach ($data as $k=>$v){
            switch ($v->field_type){
                case 'input':
                    $data[$k]->_html='<input type="text" name="conf_content[]" class="lg" value="'.$v->conf_content.'">';
                    break;
                case 'textarea':
                    $data[$k]->_html='<textarea typt="text" name="conf_content[]">'.$v->conf_content.'</textarea>';
                    break;
                case 'radio':
                    $arr = explode(',',$v->field_value);
                    $str='';
                    $c='';

                    foreach ($arr as $m=>$n){
                        $r=explode('|',$n);
                        $c= $v->conf_content == $r[0] ? ' checked ':'';
                        $str .= '<input type="radio" name="conf_content[]" class="lg" value="'.$r[0].'"'.$c.'>'.$r[1].'　';
                    }
                    $data[$k]->_html = $str;
                    break;
            }
        }
        return view('admin.config.index',compact('data'));
    }
    //配置项内容更新
    public function changeContent(Request $request)
    {
        $input = $request->all();
        foreach ($input['conf_id'] as $k=>$v){
            Config::where('conf_id',$v)->update(['conf_content'=>$input['conf_content'][$k]]);
        }
        $this->putFile();
        return back()->with('errors','配置项参数更新成功!');
    }

    public function putFile()
    {
        $config = Config::pluck('conf_content','conf_name')->all();
        $path = base_path().'/config/web.php';
        $str = '<?php return '.var_export($config,true).';';
        file_put_contents($path,$str);

    }



    public function changeOrder(Request $request)
    {
        $input = $request->all();
        $link = Config::find($input['conf_id']);
        $link->conf_order = $input['conf_order'];
        $res = $link->update();
        if ($res){
            $data =[
                'ststus' => 0,
                'msg' => '配置项排序成功'
            ];
        }else{
            $data =[
                'ststus' => 1,
                'msg' => '配置项更新排序失败,请稍后重试'
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
        return view('admin.config.add');
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
            'conf_name' => 'required',
            'conf_title' => 'required',
        ];
        $message =[
            'conf_name.required' => '配置项名称不能为空',
            'conf_title.required' => '配置项标题不能为空',

        ];
        $validator = \Validator::make($input,$rules,$message);
        if ($validator->passes())
        {
            $res = Config::create($input);
            if($res){
                return redirect('admin/config');
            }else{
                return back()->with('errors','配置项信息填充失败!请稍后重试');
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
    public function edit($conf_id)
    {
        $field = Config::find($conf_id);

        return view('admin/config/edit',compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $conf_id)
    {
        $input = $request->except('_token','_method');
        $res = Config::where('conf_id',$conf_id)->update($input);
        if ($res)
        {
            $this->putFile();
            return redirect('admin/config');
        }else{
            return back()->with('errors','配置项更新失败!请稍后重试');
    }}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($conf_id)
    {
        $res = Config::where('conf_id',$conf_id)->delete();

        if($res){
            $this->putFile();
            $data = [
                'status' => 0,
                'msg' => '配置项删除成功!!'
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '配置项删除失败,请稍后重试!!'
            ];
        }
        return $data;
    }
}
