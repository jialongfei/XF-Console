<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('Link.index');

        return (new Link())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET') return view('Link.add');

        return (new Link())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = Link::find($id);

            return view('Link.edit',['info'=>$info]);
        }else{
            return (new Link())->editData($request,$id);
        }

    }

    public function delete(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        return (new Link())->delList($request);
    }

    public function detail(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $info = (new Link())->getDetail($id);

        if (!$info) error_notice(DATA_ERR);

        return view('Link.detail',$info);
    }
}
