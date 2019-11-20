<?php

namespace App\Http\Controllers;

use App\Http\Models\Article;
use App\Http\Models\ArticleCate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SyncOldArticleController extends Controller
{
    public function index(Request $request)
    {

        $cate_list = DB::table('old_article_cate')->get()->toArray();

        foreach ($cate_list as $k => $v){

            $cate['id'] = $v->id;
            $cate['pid'] = $v->reid;
            $cate['name'] = $v->typename;
            $cate['position'] = stripos($v->typename,'月')?'top':'left';
            $cate['sort'] = $v->sortrank;
            $cate['create_user_id'] = Session::get('user.id');
            $cate['update_user_id'] = Session::get('user.id');

            ArticleCate::create($cate);

        }

        $content_list = DB::table('article_content')->select('aid','typeid','body','userip')->get()->toArray();

        foreach ($content_list as $k => $v)
        {
            $title_data = DB::table('article_title')->where('id','=',$v->aid)->first();

            // 转存到新版文章表中
            $article['title'] = $title_data->title;
            $article['cate'] = $v->typeid;
            $article['body'] = $v->body;
            $article['litpic'] = $title_data->litpic;
            $article['keywords'] = $title_data->keywords;
            $article['description'] = $title_data->description;
            $article['click'] = $title_data->click;
            $article['create_user_id'] = Session::get('user.id');
            $article['update_user_id'] = Session::get('user.id');
//            $article['ismake'] = $content_list[$k]->ismake;

            $article['created_at'] = date('Y-m-d H:i:s',$title_data->pubdate);
            $article['updated_at'] = date('Y-m-d H:i:s',$title_data->senddate);

            Article::create($article);

        }

        die('ok');

    }

    public function duanqi(Request $request)
    {

        $cate_list = DB::table('old_cate')->get()->toArray();

        foreach ($cate_list as $k => $v){

            $cate['id'] = $v->id;
            $cate['pid'] = $v->reid;
            $cate['name'] = $v->typename;
            $cate['position'] = stripos($v->typename,'月')?'top':'left';
            $cate['sort'] = $v->sortrank;
            $cate['create_user_id'] = Session::get('user.id');
            $cate['update_user_id'] = Session::get('user.id');

            \App\Http\Models\Short\ArticleCate::create($cate);

        }

        $content_list = DB::table('old_article')->select('aid','typeid','body','userip')->get()->toArray();

        foreach ($content_list as $k => $v)
        {
            $title_data = DB::table('old_title')->where('id','=',$v->aid)->first();

            // 转存到新版文章表中
            $article['title'] = $title_data->title;
            $article['cate'] = $v->typeid;
            $article['body'] = $v->body;
            $article['litpic'] = $title_data->litpic;
            $article['keywords'] = $title_data->keywords;
            $article['description'] = $title_data->description;
            $article['click'] = $title_data->click;
            $article['create_user_id'] = Session::get('user.id');
            $article['update_user_id'] = Session::get('user.id');
//            $article['ismake'] = $content_list[$k]->ismake;

            $article['created_at'] = date('Y-m-d H:i:s',$title_data->pubdate);
            $article['updated_at'] = date('Y-m-d H:i:s',$title_data->senddate);

            \App\Http\Models\Short\Article::create($article);

        }

        die('ok');

    }

}
