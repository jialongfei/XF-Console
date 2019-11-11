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

        <div class="layui-form-item">
            <label class="layui-form-label">分类名称</label>
            <div class="layui-input-block">
                <input type="text" value="{{$info->name}}" name="name" lay-verify="required" lay-reqtext="分类名称不能为空" placeholder="请输入分类名称" autocomplete="on" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">定位</label>
            <div class="layui-input-block">
                <select name="position" id="position">
                    <option value="left" @if ($info->position == 'left') selected @endif>left</option>
                    <option value="top" @if ($info->position == 'top') selected @endif>top</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">排序规则</label>
            <div class="layui-input-block">
                <input type="text" value="{{$info->sort}}" name="sort" placeholder="排序规则" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input @if ($info->status == 1) checked="" @endif type="checkbox" name="status" lay-skin="switch" lay-filter="status" lay-text="Show|Hide">
            </div>
        </div>

        <div class="layui-form-item popup-bottom-btn">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="editform">立即提交</button>
        </div>

        <input type="hidden" name="id" value="{{$info->id}}">

    </form>

</div>

<script>
    layui.use(['form'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,$ = layui.jquery;

        //监听指定开关
        form.on('switch(status)', function(data){
            if (!this.checked){
                // 未选中
            }else{
                // 选中
            }
        });

        //监听提交
        form.on('submit(editform)', function(data){

            if (data.field.status == 'on'){
                data.field.status = "1";
            }else{
                data.field.status = "0";
            }

            $.ajax({
                url: "/article/cate/edit",
                headers:{
                    'X-CSRF-TOKEN':"{{ csrf_token() }}"
                },
                data: data.field,
                type: "POST",
                dataType: "json",
                success: function(data) {

                    if(!data.status){
                        parent.layer.msg(data.msg)
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
