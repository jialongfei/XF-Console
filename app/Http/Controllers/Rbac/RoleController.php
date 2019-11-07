<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Models\Permission;
use App\Http\Models\Role;
use App\Http\Models\RolePer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('Role.Index');

        return (new Role())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET') return view('Role.add');

        return (new Role())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = Role::find($id)->toArray();

            return view('Role.edit',$info);
        }else{
            return (new Role())->editData($request,$id);
        }

    }

    public function delete(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        return (new Role())->delList($request);
    }

    public function detail(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $info = Role::find($id)->toArray();

        if (!$info) error_notice(DATA_ERR);

        return view('Role.detail',$info);
    }

    public function changeper(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $permission_list = (new Permission())->getTree();

        $has_role_list = (new RolePer())->where('role_id','=',$id)->get()->toArray();

        $has_per_ids = array_map(function ($v){ return $v['permission_id']; },$has_role_list);

        if ($request->method() == 'GET')
        {
            return view('Role.changeper',['id'=>$id,'permission_list'=>$permission_list,'has_per'=>$has_per_ids]);
        }else{

            $new_roleids = (array)$request->per_ids;

            (new RolePer())->where('role_id','=',$id)->delete();

            if (!$new_roleids) return ['status'=>true, 'msg'=>'数据已更新'];

            $new_data = [];
            $new_data['role_id'] = $id;
            $new_data['update_user_id'] = Session::get('user.id');

            try
            {

                foreach ($new_roleids as $k => $v){
                    $new_data['permission_id'] = $v;
                    (new RolePer())->create($new_data);
                }

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
    }

}
