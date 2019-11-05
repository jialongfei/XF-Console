<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('Permission.Index');

        return (new Permission())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET') return view('Permission.add');

        return (new Permission())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = Permission::find($id)->toArray();

            return view('Permission.edit',$info);
        }else{
            return (new Permission())->editData($request,$id);
        }

    }

    public function delete(Request $request)
    {
        if ($request->method() == 'GET') return ['status'=>false,'msg'=>REQUEST_ERR];

        return (new Permission())->delList($request);
    }

    public function detail(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);

        $info = Permission::find($id)->toArray();

        if (!$info) error_notice(DATA_ERR);

        return view('Permission.detail',$info);
    }

    public function getNavTree()
    {
        return (new Permission())->getNavTree();
    }
}
