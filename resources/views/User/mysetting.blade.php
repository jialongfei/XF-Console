<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/popup.css">
    <script src="/layui/layui.js"></script>

    <style>
        #preview{
            width: 135px;
            height: 135px;
            margin-bottom: 10px;
        }
    </style>

</head>
<body>

<div class="popup-box">

    <form class="layui-form" id="xForm" enctype="multipart/form-data">

        <div class="layui-form-item">
            <label class="layui-form-label">修改头像</label>
            <div class="layui-input-block">
                <div class="layui-upload-list layui-inline">
                    <img class="layui-upload-img" id="preview" src="{{$avatar}}">
                </div>
                <div class="layui-upload-drag layui-inline" id="avatar">
                    <i class="layui-icon"></i>
                    <p>点击上传，或将文件拖拽到此处</p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-block">
                <input value="{{ $name }}" type="text" name="name" lay-verify="required" lay-reqtext="用户名不能为空" placeholder="请输入用户名" autocomplete="on" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">手机</label>
            <div class="layui-input-block">
                <input value="{{ $phone }}" type="tel" name="phone" placeholder="请输入手机号码" lay-verify="required|phone" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-block">
                <input value="{{ $email }}" type="text" name="email" placeholder="请输入邮箱" lay-verify="required|email" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <textarea name="description" placeholder="该用户的备注信息" class="layui-textarea">{{ $description }}</textarea>
            </div>
        </div>

        <div class="layui-form-item popup-bottom-btn">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="editform">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>

        <input type="hidden" name="avatar" value="{{$avatar}}" id="avatarInput">
        <input type="hidden" name="id" value="{{$id}}">

    </form>

</div>

<script>
    layui.use(['form', 'layedit', 'laydate', 'upload'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate
            ,upload = layui.upload
            ,$ = layui.jquery;

        //图片上传
        var uploadInst = upload.render({
            elem: '#avatar'
            ,url: '/upload/avatar'
            ,field:'avatar'
            ,headers:{
                'X-CSRF-TOKEN':"{{ csrf_token() }}"
            }
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#preview').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                if(!res.status){
                    return layer.msg(res.msg);
                }else{
                    $('#avatarInput').val(res.path)
                }
            }
            ,error: function(res){
                return layer.msg(res.msg);
            }
        });

        //监听提交
        form.on('submit(editform)', function(data){

            $.ajax({
                url: "/mysetting",
                headers:{
                    'X-CSRF-TOKEN':"{{ csrf_token() }}"
                },
                data: data.field,
                type: "POST",
                dataType: "json",
                success: function(data) {

                    if(!data.status){
                        layer.msg(data.msg)
                    }else{
                        parent.layer.msg(data.msg)
                        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                        parent.layer.close(index); //再执行关闭
                    }

                },
                error:function(msg){
                    var json=JSON.parse(msg.responseText);
                    json = json.errors;
                    for ( var item in json) {
                        for ( var i = 0; i < json[item].length; i++) {
                            layer.msg(json[item][i]);
                            return ; //遇到验证错误，就退出
                        }
                    }
                }
            });

            return false;
        });

    });
</script>

</body>
</html>
