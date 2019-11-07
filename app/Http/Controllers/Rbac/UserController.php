<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Models\Role;
use App\Http\Models\User;
use App\Http\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('User.Index');

        return (new User())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET') return view('User.add');

        return (new User())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = User::find($id)->toArray();

            return view('User.edit',$info);
        }else{
            return (new User())->editData($request,$id);
        }

    }

    public function delete(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        return (new User())->delList($request);
    }

    public function detail(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $info = User::find($id)->toArray();

        if (!$info) error_notice(DATA_ERR);

        return view('User.detail',$info);
    }

    public function login(Request $request)
    {
        if (Session::get('user')) return error_notice(EXIST_LOGIN);

        if ($request->method() == 'GET') return view('User.login');

        return $this->checkLogin($request);
    }

    public function checkLogin($par)
    {
        $name = $par->name;
        $pwd = $par->password;
        $rmb = (int)$par->remember;

        if (!$name || !$pwd) return ['status'=>false,'msg'=>MISS_PAR];

        $user = User::where('email','=',$name)->first();

        if (!$user) return ['status'=>false,'msg'=>USER_NOT_EXIST];

        if (!password_verify($pwd,$user->password)) return ['status'=>false,'msg'=>PWD_ERR];

        if (!$user->status) return ['status'=>false,'msg'=>'登录失败: 账户已冻结.'];

        $rmb && $this->rememberMe($name,$pwd);

        Session::put('user',$user->toArray());

        return ['status'=>true,'msg'=>LOGIN_SUCCESS];
    }

    public function rememberMe($name,$pwd)
    {
        // TODO: 记住密码
    }

    public function logout()
    {
        Session::flush();

        return redirect('/login');
    }

    public function mysetting(Request $request)
    {
        $id = Session::get('user.id');

        $info = User::find($id)->toArray();

        if (!$info) error_notice(USER_NOT_EXIST);

        if ($request->method() == 'GET'){
            return view('User.mysetting',$info);
        }else{
            return (new User())->mySetting($request,$id);
        }

    }

    public function resetpwd(Request $request)
    {

        if ($request->method() == 'GET'){
            return view('User.resetpwd');
        }else{
            if (!$request->password || !$request->check_pwd) return ['status'=>false,'msg'=>MISS_PAR];

            if ($request->password != $request->check_pwd) return ['status'=>false,'msg'=>'两次输入密码不一致.'];

            $add_data['password'] = generate_password($request->password);

            $user = User::find(Session::get('user.id'));

            if (!$user) return ['status'=>false,'msg'=>USER_NOT_EXIST];

            $user->password = generate_password($request->password);

            if ($user->save()){
                return ['status'=>true,'msg'=>UPDATE_SUCCESS];
            }else{
                return ['status'=>false,'msg'=>NETWORK_ERR];
            }

        }

    }

    public function uploadAvatar(Request $request)
    {
        $file = $request->file('avatar');

        return img_upload_one($file);
    }

    public function changerole(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        $role_list = (new Role())->get();

        $has_role_list = (new UserRole())->where('user_id','=',$id)->get()->toArray();

        $has_role_ids = array_map(function ($v){ return $v['role_id']; },$has_role_list);

        if ($request->method() == 'GET')
        {
            return view('User.changerole',['role_list'=>$role_list,'has_role'=>$has_role_ids,'id'=>$id]);
        }else{

            $new_roleids = (array)$request->role_ids;

            (new UserRole())->where('user_id','=',$id)->delete();

            if (!$new_roleids) return ['status'=>true, 'msg'=>'数据已更新'];

            $new_data = [];
            $new_data['user_id'] = $id;
            $new_data['update_user_id'] = Session::get('user.id');

            try
            {

                foreach ($new_roleids as $k => $v){
                    $new_data['role_id'] = $v;
                    (new UserRole())->create($new_data);
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
