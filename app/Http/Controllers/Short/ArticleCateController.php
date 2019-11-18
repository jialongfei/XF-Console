<?php

namespace App\Http\Controllers\Short;

use App\Http\Controllers\Controller;
use App\Http\Models\Short\ArticleCate;
use Illuminate\Http\Request;

class ArticleCateController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('ShortArticleCate.index');

        return (new ArticleCate())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET')
        {
            $cates = (new ArticleCate())->getOptionTree();

            // $cates = ArticleCate::with('getCateTree')->select('id','pid','name')->first()->toArray(); // 有层级的

            return view('ShortArticleCate.add',['cates'=>$cates]);
        }

        return (new ArticleCate())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = ArticleCate::find($id);

            $cates = (new ArticleCate())->getOptionTree();

            // $cates = ArticleCate::with('getCateTree')->select('id','pid','name')->first()->toArray(); // 有层级的

            return view('ShortArticleCate.edit',['info'=>$info,'cates'=>$cates]);
        }else{
            return (new ArticleCate())->editData($request,$id);
        }

    }

    public function delete(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        return (new ArticleCate())->delList($request);
    }

    public function detail(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $info = (new ArticleCate())->getDetail($id);

        if (!$info) error_notice(DATA_ERR);

        $this->getParentCateName($info);

        return view('ShortArticleCate.detail',$info);
    }

    public function getParentCateName(&$info)
    {
        $info->parent_cate_name = ArticleCate::find($info->pid)->name;
    }
}
