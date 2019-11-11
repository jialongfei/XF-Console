<?php

namespace App\Http\Controllers;

use App\Http\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SyncOldArticleController extends Controller
{
    public function index(Request $request)
    {

        $content_list = DB::table('article_content')->select('aid','typeid','body','userip')->get()->toArray();

        foreach ($content_list as $k => $v)
        {
            $title_data = DB::table('article_title')->where('id','=',$v->aid)->first();

            $content_list[$k]->title = $title_data->title;
            $content_list[$k]->ismake = $title_data->ismake;
            $content_list[$k]->channel = $title_data->channel;
            $content_list[$k]->click = $title_data->click;
            $content_list[$k]->writer = $title_data->writer;
            $content_list[$k]->litpic = $title_data->litpic;
            $content_list[$k]->pubdate = $title_data->pubdate;
            $content_list[$k]->senddate = $title_data->senddate;
            $content_list[$k]->keywords = $title_data->keywords;
            $content_list[$k]->description = $title_data->description;

            $content_list[$k]->cate = [];



            $cate_data = DB::table('old_article_cate')->where('typename','!=','新闻媒体')->where('id','=',$v->typeid)->first();

            if ($cate_data){
                array_push($content_list[$k]->cate,$cate_data->typename);
                $parent_cate = DB::table('old_article_cate')->where('typename','!=','新闻媒体')->where('id','=',$cate_data->reid)->first();
                if ($parent_cate){
                    array_push($content_list[$k]->cate,$parent_cate->typename);
                    $parent_parent_cate = DB::table('old_article_cate')->where('typename','!=','新闻媒体')->where('id','=',$parent_cate->reid)->first();
                    if ($parent_parent_cate){
                        array_push($content_list[$k]->cate,$parent_parent_cate->typename);
                        $parent_parent_parent_cate = DB::table('old_article_cate')->where('typename','!=','新闻媒体')->where('id','=',$parent_parent_cate->reid)->first();
                        if ($parent_parent_parent_cate){
                            array_push($content_list[$k]->cate,$parent_parent_parent_cate->typename);
                            $parent_parent_parent_parent_cate = DB::table('old_article_cate')->where('typename','!=','新闻媒体')->where('id','=',$parent_parent_parent_cate->reid)->first();
                            if ($parent_parent_parent_parent_cate){
                                array_push($content_list[$k]->cate,$parent_parent_parent_parent_cate->typename);
                            }
                        }
                    }
                }
            }

            $content_list[$k]->cate_string = implode(',',$content_list[$k]->cate);

            unset($content_list[$k]->cate);

            // 转存到新版文章表中
            $article['title'] = $content_list[$k]->title;
            $article['cate'] = $content_list[$k]->cate_string;
            $article['body'] = $content_list[$k]->body;
            $article['litpic'] = $content_list[$k]->litpic;
            $article['keywords'] = $content_list[$k]->keywords;
            $article['description'] = $content_list[$k]->description;
            $article['click'] = $content_list[$k]->click;
            $article['create_user_id'] = Session::get('user.id');;
            $article['update_user_id'] = Session::get('user.id');;
//            $article['ismake'] = $content_list[$k]->ismake;

            $article['created_at'] = date('Y-m-d H:i:s',$content_list[$k]->pubdate);
            $article['updated_at'] = date('Y-m-d H:i:s',$content_list[$k]->senddate);

            Article::create($article);

        }

        // todo 文章内容 文章分类信息 基本获取完成,下一步考虑如何把老数据的分类信息使用起来
        // 1.使用tag的方式是否会灵活/友好一些，每年仅需新增一个分类
        // 2.无限分类层级关系更明确，但每年需要加至少十几个分类
        // 3.使用tag方式的话，可以新增个定位字段，如： 某些（年份）tag放在左侧，某些（月份）tag放在顶部，以达到类似层级关系的效果
        // 4.使用tag方式的话，最好增加个sort字段以便排序

        echo "<pre>";
        var_dump($content_list);exit();

        // TODO: 后期可能需要处理的
        // 这些页面是否更应该是一个单独的HTML页面，而不是通过文章的排版去展示？毕竟后期路由显示会略显不同，如‘关于我们’的路由可能是 /articles/666 而不是 /about
        // $res = DB::table('old_article_cate')->where('content','!=','')->where('content','!=','&nbsp;')->get()->toArray();// 所有无关联文章，导航即内容页的分类数据
        // $res = DB::table('old_article_cate')->whereIn('content',['','&nbsp;'])->get()->toArray();// 需要关联到其他表的分类数据

        // TODO ： Filter eligible articel
//        $where = [];
//        $par = (array)$request->cate; // [id_1,id_2,id_3,...]
//
//        foreach ($par as $k => $v){
//            $where[] = ['name', 'like', '%'.$v.'%'];
//        }
//
//        DB::table('article')->where($where)->get();

    }
}
