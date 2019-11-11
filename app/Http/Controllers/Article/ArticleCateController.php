<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Models\ArticleCate;
use Illuminate\Http\Request;

class ArticleCateController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('ArticleCate.index');

        return (new ArticleCate())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET') return view('ArticleCate.add');

        return (new ArticleCate())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = ArticleCate::find($id);

            return view('ArticleCate.edit',['info'=>$info]);
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

        return view('ArticleCate.detail',$info);
    }
}
