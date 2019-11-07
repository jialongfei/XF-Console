<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Permission extends Model
{
    protected $table = 'permission';

    protected $fillable = ['name', 'pid', 'path', 'sort', 'is_show', 'create_user_id', 'update_user_id', 'created_at', 'updated_at'];

    public $timestamps = true;

    public function getNavs()
    {
        return $this->select('id','name','pid','path','sort','is_show')->orderBy('sort', 'ASC')->get();
    }

    public function getTree()
    {
        $parents = $this->where('pid','=',0)->orderBy('sort', 'ASC')->get();

        foreach ($parents as $k => $v){
            $child = $this->where('pid','=',$v['id'])->orderBy('sort', 'ASC')->get();

            foreach ($child as $c_k => $c_v){
                $child[$c_k]['children'] = $this->where('pid','=',$c_v['id'])->orderBy('sort', 'ASC')->get();
            }

            $parents[$k]['children'] = $child;

        }

        return $parents;
    }

    public function getParentTree()
    {
        $parents = $this->where('pid','=',0)->orderBy('sort', 'ASC')->get();

        foreach ($parents as $k => $v){
            $child = $this->where('pid','=',$v['id'])->orderBy('sort', 'ASC')->get();
            $parents[$k]['children'] = $child;
        }

        return $parents;
    }

    public function getLists($request)
    {
        $where = [];

        $page = (int)$request->page-1;
        $limit = (int)$request->limit;

        $request->search && $where[] = ['p.name','like','%'.$request->search.'%'];

        $list = $this->from('permission as p')
            ->leftJoin('permission as parent_p','p.pid','=','parent_p.id')
            ->leftJoin('users as create_u','p.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','p.update_user_id','=','update_u.id')
            ->select(['p.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name','parent_p.name as parent_name'])
            ->where($where)
            ->orderBy('sort', 'ASC')
            ->offset($page*$limit)->limit($limit)
            ->get()
            ->toArray();

        $count = $this->from('permission as p')
            ->leftJoin('users as create_u','p.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','p.update_user_id','=','update_u.id')
            ->select(['p.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
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
        $info = $this->from('permission as p')
            ->leftJoin('permission as parent_p','p.pid','=','parent_p.id')
            ->leftJoin('users as create_u','p.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','p.update_user_id','=','update_u.id')
            ->select(['p.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name','parent_p.name as parent_name'])
            ->where('p.id','=',$id)
            ->first();

        return $info;
    }

    public function addData($request)
    {
        $validatedData = $request->validate([
            'name' => 'string',
            'pid' => 'nullable|integer',
            'path' => 'nullable|string',
            'sort' => 'nullable|integer',
            'is_show' => 'nullable|integer',
        ]);

        // 记录创建/更新者ID
        $validatedData['create_user_id'] = Session::get('user.id');
        $validatedData['update_user_id'] = Session::get('user.id');

        !$validatedData['pid'] && $validatedData['pid'] = 0;
        !$validatedData['path'] && $validatedData['path'] = 'javascript:;';
        !$validatedData['sort'] && $validatedData['sort'] = 100000;
        !$validatedData['is_show'] && $validatedData['is_show'] = 0;

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

        $old_data = $this->select('name','pid','path','sort','is_show','id')->find($id)->toArray();

        // 检查接收到的修改数据是否有变化，有则验证并更新，无则忽略
        if (array_diff($old_data,$request->all())){
            $validatedData = $request->validate([
                'name' => 'string',
                'pid' => 'nullable|integer',
                'path' => 'nullable|string',
                'sort' => 'nullable|integer',
                'is_show' => 'nullable|integer',
            ]);

            // 记录更新者ID
            $validatedData['update_user_id'] = Session::get('user.id');

            !$validatedData['pid'] && $validatedData['pid'] = 0;
            !$validatedData['path'] && $validatedData['path'] = 'javascript:;';
            !$validatedData['sort'] && $validatedData['sort'] = 100000;
            !$validatedData['is_show'] && $validatedData['is_show'] = 0;

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

            // 删除关联数据
            (new RolePer())->whereIn('permission_id', $request->ids)->delete();

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
