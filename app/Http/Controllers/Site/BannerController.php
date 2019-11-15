<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('Banner.index');

        return (new Banner())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET') return view('Banner.add');

        return (new Banner())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = Banner::find($id);

            return view('Banner.edit',['info'=>$info]);
        }else{
            return (new Banner())->editData($request,$id);
        }

    }

    public function delete(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        return (new Banner())->delList($request);
    }

    public function detail(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $info = (new Banner())->getDetail($id);

        if (!$info) error_notice(DATA_ERR);

        return view('Banner.detail',$info);
    }
}
