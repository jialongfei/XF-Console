<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Article extends Model
{
    protected $table = 'article';

    protected $fillable = ['title', 'cate', 'body', 'litpic', 'keywords', 'description', 'click', 'ismake', 'create_user_id', 'update_user_id', 'created_at', 'updated_at'];

    public $timestamps = false;

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

        return $info;
    }

    public function addData($request)
    {

        echo "<pre>";
        var_dump($request->all());exit();

        $validatedData = $request->validate([
            'name' => 'string',
            'position' => 'nullable|string',
            'sort' => 'nullable|integer',
            'status' => 'nullable|integer',
        ]);

        // 记录创建/更新者ID
        $validatedData['create_user_id'] = Session::get('user.id');
        $validatedData['update_user_id'] = Session::get('user.id');

        !$validatedData['position'] && $validatedData['position'] = 'left';
        !$validatedData['sort'] && $validatedData['sort'] = 100000;
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

        echo "<pre>";
        var_dump($request->all());exit();

        $old_data = $this->select('name','position','sort','status','id')->find($id)->toArray();

        // 检查接收到的修改数据是否有变化，有则验证并更新，无则忽略
        if (array_diff($old_data,$request->all())){
            $validatedData = $request->validate([
                'name' => 'string',
                'position' => 'nullable|string',
                'sort' => 'nullable|integer',
                'status' => 'nullable|integer',
            ]);

            // 记录更新者ID
            $validatedData['update_user_id'] = Session::get('user.id');

            !$validatedData['position'] && $validatedData['position'] = 'left';
            !$validatedData['sort'] && $validatedData['sort'] = 100000;

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
        }else{
            return [
                'status'=>true,
                'msg'=>'数据无变更'
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

}
