<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/popup.css">
    <script src="/layui/layui.js"></script>

</head>
<body>

<div class="popup-box">

    <form class="layui-form" id="xForm">

        @foreach ($role_list as $role)
            <div class="layui-form-item" pane="">
                <div class="layui-input-block">
                    <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" title="{{ $role->name }}" {{ in_array($role->id,$has_role) ?'checked=""':''}} >
                    <span>{{ $role->description }}</span>
                </div>
            </div>
        @endforeach

        <input type="hidden" name="id" value="{{ $id }}">

        <div class="layui-form-item popup-bottom-btn">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="addform">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>

    </form>

</div>

<script>
    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,$ = layui.jquery;

        //监听提交
        form.on('submit(addform)', function(data){

            $.ajax({
                url: "/user/role",
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
                        parent.layui.table.reload("tableReload");
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
