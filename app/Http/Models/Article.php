<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Article extends Model
{
    protected $table = 'article';

    protected $fillable = ['title', 'cate', 'body', 'litpic', 'keywords', 'description', 'click', 'ismake', 'create_user_id', 'update_user_id', 'created_at', 'updated_at'];

    public $timestamps = true;// 数据同步时请将次项改为 false

    public function getLists($request)
    {
        $where = [];

        $page = (int)$request->page-1;
        $limit = (int)$request->limit;

        $request->search && $where[] = ['a.title','like','%'.$request->search.'%'];

        $list = $this->from('article as a')
            ->leftJoin('users as create_u','a.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','a.update_user_id','=','update_u.id')
            ->select(['a.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where($where)
            ->orderBy('created_at', 'DESC')
            ->offset($page*$limit)->limit($limit)
            ->get()
            ->toArray();

        $count = $this->from('article as a')
            ->leftJoin('users as create_u','a.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','a.update_user_id','=','update_u.id')
            ->select(['a.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where($where)
            ->orderBy('created_at', 'DESC')
            ->count('*');

        return [
            'code'=>0,
            'count'=>$count,
            'msg'=>'success!',
            'data'=> $list
        ];
    }

    public function getDetail($id)
    {
        $info = $this->from('article as a')
            ->leftJoin('users as create_u','a.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','a.update_user_id','=','update_u.id')
            ->select(['a.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where('a.id','=',$id)
            ->first();

        $info->cate_name = '无';

        $cate = ArticleCate::find($info->cate);

        if ($cate)
        {
            (new ArticleCate())->getOptionName($cate);

            $info->cate_name = $cate->name?:'无';
        }

        return $info;
    }

    public function addData($request)
    {

        $validatedData = $request->validate([
            'title' => 'string',
            'keywords' => 'string',
            'description' => 'string',
            'body' => 'string',
            'cate' => 'nullable|integer',
            'click' => 'nullable|integer',
            'sort' => 'nullable|integer',
            'litpic' => 'nullable|string',
            'status' => 'nullable|integer',
        ]);

        // 记录创建/更新者ID
        $validatedData['create_user_id'] = Session::get('user.id');
        $validatedData['update_user_id'] = Session::get('user.id');

        !$validatedData['click'] && $validatedData['click'] = 0;
        !$validatedData['cate'] && $validatedData['cate'] = 0;
        !$validatedData['sort'] && $validatedData['sort'] = 100000;
        !$validatedData['litpic'] && $validatedData['litpic'] = '/default/litpic.jpg';
        !$validatedData['status'] && $validatedData['status'] = 1;

        try
        {
            $this->create($validatedData);

            return [
                'status'=>true,
                'msg'=>'已添加'
            ];

        } catch(\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status'=>false,
                'msg'=>$e->getCode().': 网络异常，请稍后再试 或 联系管理员',
                'debug'=>$e->getMessage(),
            ];
        }
    }

    public function editData($request,$id)
    {

        $validatedData = $request->validate([
            'title' => 'string',
            'keywords' => 'string',
            'description' => 'string',
            'body' => 'string',
            'cate' => 'nullable|integer',
            'click' => 'nullable|integer',
            'sort' => 'nullable|integer',
            'litpic' => 'nullable|string',
            'status' => 'nullable|integer',
        ]);

        $validatedData['update_user_id'] = Session::get('user.id');// 记录更新者ID

        !$validatedData['litpic'] && $validatedData['litpic'] = '/default/litpic.jpg';
        !$validatedData['cate'] && $validatedData['cate'] = 0;

        try
        {
            $this->where('id','=',$id)->update($validatedData);

            return [
                'status'=>true,
                'msg'=>'数据已更新'
            ];

        } catch(\Exception $e) {
            Log::error($e->getMessage());

            return [
                'status'=>false,
                'msg'=>$e->getCode().': 网络异常，请稍后再试 或 联系管理员',
                'debug'=>$e->getMessage(),
            ];
        }

    }

    public function delList($request)
    {
        // TODO: 检测并过滤非法参数

        try {

            $this->destroy($request->ids);

            return ['status'=>true,'msg'=>'已删除'];

        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return [
                'status'=>false,
                'msg'=>$e->getCode().': 网络异常，请稍后再试 或 联系管理员',
                'debug'=>$e->getMessage(),
            ];

        }
    }

    public function articleApi($request)
    {

        $where = [];

        $page = (int)$request->page-1;
        $limit = (int)$request->limit;

        $_response['status'] = true;

        $request->search && $where[] = ['a.title','like','%'.$request->search.'%'];

        $query = $this->from('article as a')
            ->leftJoin('users as create_u','a.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','a.update_user_id','=','update_u.id')
            ->select(['a.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where($where);

        $cate = (int)$request->cate;

        if ($cate > 0){
            // 获取当前分类下的所有分类
            $cate_lists = ArticleCate::where('pid','=',$cate)->select('id')->get()->toArray();

            $cate_ids = [];
            $cate_ids[] = $cate;

            if ($cate_lists){
                $this->getChildCateIds($cate_lists,$cate_ids);
            }

            $query->whereIn('cate',$cate_ids);

            // 获取当前分类全称
            $current_cate = (new ArticleCate())->find($cate);

            (new ArticleCate())->getOptionName($current_cate);

            $_response['current_cate_name'] = $current_cate->name?:'';

        }

        $count = $query->count('*');

        $list = $query->orderBy('created_at', 'DESC')
            ->offset($page*$limit)->limit($limit)
            ->get()
            ->toArray();

        if ($count > 0){
            foreach ($list as $k => $v)
            {
                $str_content = strip_tags($v['body']);
                $list[$k]['body'] = $str_content;
            }
        }

        $_response['data'] = $list;

        $_response['count'] = $count;

        return $_response;
    }

    public function getChildCateIds($cate_list,&$cate_ids)
    {
        $current_cate_ids = array_map(function ($v){ return $v['id']; },$cate_list);

        $cate_ids = array_merge($cate_ids,$current_cate_ids);

        $new_cate_list = ArticleCate::whereIn('pid',$current_cate_ids)->select('id')->get()->toArray();

        $new_cate_list && $this->getChildCateIds($new_cate_list,$cate_ids);

        return $cate_ids;
    }

}
