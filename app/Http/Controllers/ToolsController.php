<?php

namespace App\Http\Controllers;

use App\Http\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ToolsController extends Controller
{
    public function uploadImg(Request $request)
    {
        $file = $request->file('image');

        return img_upload_one($file,'image');
    }

    public function layeditImg(Request $request)
    {
        $file = $request->file('file');

        $res = img_upload_one($file,'image');

        if (!$res['status']){
            return [
                'code' => 10,
                'msg' => $res['msg'],
            ];
        }

        return [
            'code' => 0,
            'msg' => '上传成功',
            'data' => [
                'src' => $res['path']
            ],
        ];

    }

    public function checkroute()
    {

        $permission_list = array_map(function ($v){ return $v['path']; },Permission::where('path','!=','javascript:;')->get()->toArray());

        foreach (app()->routes->getRoutes() as $k => $value)
            $k > 5 && $value->uri != '/' && !in_array('/'.$value->uri,$permission_list) && $neglected[] = $value->uri;

        return $neglected ?? [];
    }

}
