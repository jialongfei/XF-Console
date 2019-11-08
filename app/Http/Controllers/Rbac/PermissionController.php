<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Http\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == 'GET') return view('Permission.index');

        return (new Permission())->getLists($request);
    }

    public function add(Request $request)
    {
        if ($request->method() == 'GET')
        {
            // get permission parent tree
            $parent_tree = (new Permission())->getParentTree();

            return view('Permission.add',['parent_list'=>$parent_tree]);
        }

        return (new Permission())->addData($request);
    }

    public function edit(Request $request)
    {
        $id = (int)$request->id;

        if (!$id) error_notice(MISS_PAR);;

        if ($request->method() == 'GET')
        {
            $info = Permission::find($id);

            // get permission parent tree
            $parent_tree = (new Permission())->getParentTree();

            return view('Permission.edit',['info'=>$info,'parent_list'=>$parent_tree]);
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

        $info = (new Permission())->getDetail($id);

        if (!$info) error_notice(DATA_ERR);

        return view('Permission.detail',$info);
    }

    public function getNavTree()
    {
        return (new Permission())->getNavTree();
    }
}
