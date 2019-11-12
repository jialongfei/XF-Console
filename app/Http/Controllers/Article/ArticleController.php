<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Models\Article;
use App\Http\Models\ArticleCate;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('Article.index');

        return (new Article())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET') return view('Article.add');

        return (new Article())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = Article::find($id);

            return view('Article.edit',['info'=>$info]);
        }else{
            return (new Article())->editData($request,$id);
        }

    }

    public function delete(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        return (new Article())->delList($request);
    }

    public function detail(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $info = (new Article())->getDetail($id);

        if (!$info) error_notice(DATA_ERR);

        return view('Article.detail',$info);
    }

    public function selectcate(Request $request)
    {
        $has = $request->hascate;

        $cate_list = ArticleCate::select('name','id as value')->get()->toArray();

        if ($cate_list && $has)
        {
            $has_arr = explode(',',$has);
            foreach ($cate_list as $k => $v)
            {
                in_array($v['name'],$has_arr) && $cate_list[$k]['selected'] = true;
            }
        }

        return ['status'=>true,'data'=>$cate_list];
    }

}
