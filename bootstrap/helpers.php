<?php

/** public notice style
 * @param $msg
 */
function error_notice($msg){
    die('<div style="color: #ff8e8e;text-align: center;margin-top: 200px;font-size: 26px;">'.$msg.'</div>');
}

/**
 * generate hash string password
 * @param $pwd
 * @return false|string
 */
function generate_password($pwd){
    return password_hash($pwd,PASSWORD_DEFAULT);
}

function img_upload_one($file, $disk='avatar'){
    // 1.是否上传成功
    if (! $file->isValid()) {
        return [
            'status'=>false,
            'msg'=>'File upload failed.'
        ];
    }

    // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
    $fileExtension = $file->getClientOriginalExtension();
    if(! in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
        return [
            'status'=>false,
            'msg'=>'File type not allowed.'
        ];
    }

    // 3.判断大小是否符合 2M
    $tmpFile = $file->getRealPath();
    if (filesize($tmpFile) >= 2048000) {
        return [
            'status'=>false,
            'msg'=>'File size out of limit.'
        ];
    }

    // 4.是否是通过http请求表单提交的文件
    if (! is_uploaded_file($tmpFile)) {
        return [
            'status'=>false,
            'msg'=>'Illegal request.'
        ];
    }

    // 5.每天一个文件夹,分开存储, 生成一个随机文件名
    $fileName = date('Y_m_d').'/'.md5(time()) .mt_rand(0,9999).'.'. $fileExtension;
    if (\Illuminate\Support\Facades\Storage::disk($disk)->put($fileName, file_get_contents($tmpFile)) ){
        return [
            'status'=>true,
            'path'=>\Illuminate\Support\Facades\Storage::disk($disk)->url($fileName)
        ];
    }
}
