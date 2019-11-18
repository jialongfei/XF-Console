<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ArticleCate extends Model
{
    protected $table = 'article_cate';

    protected $fillable = ['id', 'name', 'pid', 'position', 'sort', 'status', 'create_user_id', 'update_user_id', 'created_at', 'updated_at'];

    public $timestamps = true;


    public function getOptionTree()
    {
        $cate_list = $this->where('status','=',1)->select('id','pid','name','position')->get();

        foreach ($cate_list as $k => $v)
            ($v->pid !== 0) &&  $this->getOptionName($cate_list[$k]);

        return $cate_list;

    }

    public function getOptionName(&$item,$parent_id = 0)
    {

        $parent = $parent_id?$this->find($parent_id):$this->find($item->pid);

        if (!$parent) return $item;

        $item->name = $parent->name.'-'.$item->name;

        ($parent->pid != 0) && $this->getOptionName($item,$parent->pid);

        return $item;

    }

    public function childCategory() {
        return $this->hasMany(get_class($this), 'pid', 'id')->select('id','pid','name');
    }

    public function getCateTree()
    {
        return $this->childCategory()->with('getCateTree');
    }

//    public function getCateTree($pid = 0)
//    {
//        $cates = $this->where('pid','=',$pid)->get();
//
//        foreach ($cates)
//
//        return $cates;
//    }

    public function getLists($request)
    {
        $where = [];

        $page = (int)$request->page-1;
        $limit = (int)$request->limit;

        $request->search && $where[] = ['c.name','like','%'.$request->search.'%'];

        $list = $this->from('article_cate as c')
            ->leftJoin('users as create_u','c.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','c.update_user_id','=','update_u.id')
            ->select(['c.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where($where)
            ->orderBy('sort', 'ASC')
            ->offset($page*$limit)->limit($limit)
            ->get()
            ->toArray();

        $count = $this->from('article_cate as c')
            ->leftJoin('users as create_u','c.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','c.update_user_id','=','update_u.id')
            ->select(['c.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
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
        $info = $this->from('article_cate as c')
            ->leftJoin('users as create_u','c.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','c.update_user_id','=','update_u.id')
            ->select(['c.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where('c.id','=',$id)
            ->first();

        return $info;
    }

    public function addData($request)
    {
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
