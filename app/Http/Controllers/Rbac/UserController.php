<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
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
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $info = User::find($id)->toArray();

        if ($request->method() == 'GET'){

        }else{

        }

        // TODO: 修改个人资料 头像

    }

}
