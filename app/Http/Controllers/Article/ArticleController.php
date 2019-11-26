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
        if ($request->method() == 'GET')
        {
            $cates = (new ArticleCate())->getOptionTree();

            // $cates = ArticleCate::with('getCateTree')->select('id','pid','name')->first()->toArray(); // 有层级的

            return view('Article.add',['cates'=>$cates]);
        }

        return (new Article())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = Article::find($id);

            $cates = (new ArticleCate())->getOptionTree();

            // $cates = ArticleCate::with('getCateTree')->select('id','pid','name')->first()->toArray(); // 有层级的

            return view('Article.edit',['info'=>$info,'cates'=>$cates]);
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

    /**
     * 文章接口
     * @param (int)$request->page
     * @param (int)$request->limit
     * @param (string)$request->search 可选 获取标题中包含指定字符的文章
     * @param (int)$request->cate 可选 获取指定分类下的所有子孙分类关联的文章
     */
    public function articleapi(Request $request)
    {
        return (new Article())->articleApi($request);
    }

    /**
     * 分类接口
     * @param (int)$request->cate 可选 获取指定分类下的所有子分类，仅查询子分类
     */
    public function articlecateapi(Request $request)
    {
        return (new ArticleCate())->articleCateApi($request);
    }

    /**
     * 详情接口
     * @param (int)$request->id 必选 获取指定ID的文章详情
     */
    public function articledetailapi(Request $request)
    {
        $id = (int)$request->id;

        if ($id < 1) return ['status'=>false,'msg'=>MISS_PAR];

        $info = (new Article())->getDetail($id);

        if (!$info) return ['status'=>false,'msg'=>DATA_ERR];

        // 处理图片路径
        if ($info->body){
            // 匹配到指定字符，并在其前面插入指定字符串
            $info->content = preg_replace('#src="/([^"]+?)"#','src="http://official.com/$1"',$info->body);
        }

        return ['status'=>true,'data'=>$info];

    }

}
