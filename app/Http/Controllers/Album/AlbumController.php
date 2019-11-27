<?php

namespace App\Http\Controllers\Album;

use App\Http\Controllers\Controller;
use App\Http\Models\Album;
use App\Http\Models\Photo;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('Album.index');

        return (new Album())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET')
        {
            // TODO 获取 相册分类
            $cates = [];

            return view('Album.add',['cates'=>$cates]);
        }

        return (new Album())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = Album::find($id);

            // TODO 获取 相册分类
            $cates = [];

            return view('Album.edit',['info'=>$info,'cates'=>$cates]);
        }else{
            return (new Album())->editData($request,$id);
        }

    }

    public function delete(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        return (new Album())->delList($request);
    }

    public function detail(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $info = (new Album())->getDetail($id);

        if (!$info) error_notice(DATA_ERR);

        return view('Album.detail',$info);
    }

    public function photoinfo(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            return view('Album.photoinfo',['id'=>$id]);
        }else{
            // 获取当前相册中所有图片并返回
            return (new Photo())->getLists($id);
        }
    }

    public function photodel(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        $id = (int)$request->id;

        return (new Photo())->deleteOne($id);
    }

    public function photoadd(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        return (new Photo())->addOne($request);
    }

}
