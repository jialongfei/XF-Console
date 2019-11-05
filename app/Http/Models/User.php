<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'phone', 'avatar', 'password', 'description', 'create_user_id', 'update_user_id', 'status', 'created_at', 'updated_at'];

    public $timestamps = true;


    public function getLists($request)
    {
        $where = [];

        $page = (int)$request->page-1;
        $limit = (int)$request->limit;

        $request->search && $where[] = ['u.name','like','%'.$request->search.'%'];

        $list = $this->from('users as u')
            ->leftJoin('users as create_u','u.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','u.update_user_id','=','update_u.id')
            ->select(['u.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where($where)
            ->orderBy('id', 'DESC')
            ->offset($page*$limit)->limit($limit)
            ->get()
            ->toArray();

        $count = $this->from('users as u')
            ->leftJoin('users as create_u','u.create_user_id','=','create_u.id')
            ->leftJoin('users as update_u','u.update_user_id','=','update_u.id')
            ->select(['u.*', 'create_u.name as create_user_name', 'update_u.name as update_user_name'])
            ->where($where)
            ->orderBy('id', 'DESC')
            ->count('*');

        return [
            'code'=>0,
            'count'=>$count,
            'msg'=>'success!',
            'data'=> $list
        ];
    }

    public function addData($request)
    {
        $validatedData = $request->validate([
            'name' => 'string',
            "phone"=>[
                'required',
                'regex:/^1[3456789][0-9]{9}$/',
                function($attribute, $value, $fail){
                    if($this->where(['phone'=>$value])->count('*')){
                        return $fail('The '.$attribute.' already exist.');
                    }
                }
            ],
            "email"=>[
                'required',
                function($attribute, $value, $fail){
                    if($this->where(['email'=>$value])->count('*')){
                        return $fail('The '.$attribute.' already exist.');
                    }
                }
            ],
            'password' => 'required',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $validatedData['password'] = generate_password($request->password);

        // 记录创建/更新者ID
        $validatedData['create_user_id'] = Session::get('user.id');
        $validatedData['update_user_id'] = Session::get('user.id');

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

        $old_data = $this->select('name','phone','email','description','id','status')->find($id)->toArray();

        if($this->where('phone','=',$request->phone)->where('id','!=',$id)->count('*')){
            return [
                'status'=>false,
                'msg'=>'The Phone already exist.',
                'debug'=>$request->all(),
            ];
        }

        if($this->where('email','=',$request->email)->where('id','!=',$id)->count('*')){
            return [
                'status'=>false,
                'msg'=>'The Email already exist.',
                'debug'=>$request->all(),
            ];
        }

        // 检查接收到的修改数据是否有变化，有则验证并更新，无则忽略
        if (array_diff($old_data,$request->all())){
            $validatedData = $request->validate([
                'name' => 'string',
                "phone"=>[
                    'required',
                    'regex:/^1[3456789][0-9]{9}$/',
                ],
                "email"=>[
                    'required',
                ],
                'description' => 'nullable|string',
                'status' => 'nullable|string',
            ]);

            // 记录更新者ID
            $validatedData['update_user_id'] = Session::get('user.id');

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

    public function mySetting($request,$id)
    {
        $old_data = $this->select('avatar','name','phone','email','description','id')->find($id)->toArray();

        if($this->where('phone','=',$request->phone)->where('id','!=',$id)->count('*')){
            return [
                'status'=>false,
                'msg'=>'The Phone already exist.',
                'debug'=>$request->all(),
            ];
        }

        if($this->where('email','=',$request->email)->where('id','!=',$id)->count('*')){
            return [
                'status'=>false,
                'msg'=>'The Email already exist.',
                'debug'=>$request->all(),
            ];
        }

        // 检查接收到的修改数据是否有变化，有则验证并更新，无则忽略
        if (array_diff($old_data,$request->all())){
            $validatedData = $request->validate([
                'name' => 'string',
                "phone"=>[
                    'required',
                    'regex:/^1[3456789][0-9]{9}$/',
                ],
                "email"=>[
                    'required',
                ],
                'description' => 'nullable|string',
                'avatar' => 'nullable|string',
            ]);

            // 记录更新者ID
            $validatedData['update_user_id'] = Session::get('user.id');

            try
            {
                $this->where('id','=',$id)->update($validatedData);

                Session::flush();

                Session::put('user',$this->find($id));

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

}
