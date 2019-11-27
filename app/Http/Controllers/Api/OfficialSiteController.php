<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Models\Article;
use Illuminate\Http\Request;

class OfficialSiteController extends Controller
{
    /**
     * Main page data api
     */
    public function index(Request $request)
    {
        // * @param (int)$request->page
        // * @param (int)$request->limit
        // * @param (string)$request->search 可选 获取标题中包含指定字符的文章
        // * @param (int)$request->cate 可选 获取指定分类下的所有子孙分类关联的文章
        $data['article'] = (new Article())->articleApi($request)['data'];

        return ['status'=>true,'data'=>$data];
    }
}
