<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class Photo extends Model
{
    protected $table = 'album_photo';

    protected $fillable = ['album_id', 'path', 'click', 'sort', 'status', 'create_user_id', 'update_user_id', 'created_at', 'updated_at'];

    public $timestamps = true;

    public function getLists($id)
    {
        $list = $this->where('album_id','=',$id)->orderBy('sort', 'ASC')->get()->toArray();

        return ['status'=>true, 'data'=> $list];

    }

    public function deleteOne($id)
    {
        try {

            $this->destroy($id);

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

    public function addOne($request)
    {
        $id = (int)$request->id;
        $path = (string)$request->_path;

        if (!$id || !$path) return ['status'=>false, 'msg'=>MISS_PAR];

        $validatedData = [];

        // 记录创建/更新者ID
        $validatedData['create_user_id'] = Session::get('user.id');
        $validatedData['update_user_id'] = Session::get('user.id');
        $validatedData['album_id'] = $id;
        $validatedData['path'] = $path;

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

}
