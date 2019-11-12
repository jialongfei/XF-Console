<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
