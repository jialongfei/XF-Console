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
}
