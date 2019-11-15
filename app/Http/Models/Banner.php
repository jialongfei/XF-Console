<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Banner extends Model
{
    protected $table = 'banner';

    protected $fillable = ['position', 'img_path', 'link', 'sort', 'status', 'create_user_id', 'update_user_id', 'created_at', 'updated_at'];

    public $timestamps = true;

    public function getLists($request)
    {
        $where = [];

        $page = (int)$request->page-1;
        $limit = (int)$request->limit;

        $request->search && $where[] = ['b.img_path','like','%'.$request->search.'%'];

        $list = $this->from('banner as b')
            ->leftJoin('users as create_u','b.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','b.update_user_id','=','update_u.id')
            ->select(['b.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where($where)
            ->orderBy('sort', 'ASC')
            ->offset($page*$limit)->limit($limit)
            ->get()
            ->toArray();

        $count = $this->from('banner as b')
            ->leftJoin('users as create_u','b.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','b.update_user_id','=','update_u.id')
            ->select(['b.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where($where)
            ->orderBy('sort', 'ASC')
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
        $info = $this->from('banner as b')
            ->leftJoin('users as create_u','b.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','b.update_user_id','=','update_u.id')
            ->select(['b.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where('b.id','=',$id)
            ->first();

        return $info;
    }

    public function addData($request)
    {
        $validatedData = $request->validate([
            'position' => 'string',
            'img_path' => 'nullable|string',
            'link' => 'nullable|string',
            'sort' => 'nullable|integer',
            'status' => 'nullable|integer',
        ]);

        // 记录创建/更新者ID
        $validatedData['create_user_id'] = Session::get('user.id');
        $validatedData['update_user_id'] = Session::get('user.id');

        !$validatedData['img_path'] && $validatedData['icon'] = '/banner/default.jpg';
        !$validatedData['link'] && $validatedData['link'] = 'javascript:;';
        !$validatedData['sort'] && $validatedData['sort'] = 100000;
        !$validatedData['status'] && $validatedData['status'] = 0;

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

        $old_data = $this->select('position','img_path','link','sort','status','id')->find($id)->toArray();

        // 检查接收到的修改数据是否有变化，有则验证并更新，无则忽略
        if (array_diff($old_data,$request->all()) || array_diff($request->all(),$old_data)){
            $validatedData = $request->validate([
                'position' => 'string',
                'img_path' => 'nullable|string',
                'link' => 'nullable|string',
                'sort' => 'nullable|integer',
                'status' => 'nullable|integer',
            ]);

            // 记录更新者ID
            $validatedData['update_user_id'] = Session::get('user.id');

            !$validatedData['img_path'] && $validatedData['icon'] = '/banner/default.jpg';
            !$validatedData['link'] && $validatedData['link'] = 'javascript:;';
            !$validatedData['sort'] && $validatedData['sort'] = 100000;
            !$validatedData['status'] && $validatedData['status'] = 0;

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
