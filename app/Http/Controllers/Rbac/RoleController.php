<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Models\Role;
use Illuminate\Http\Request;

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
}
