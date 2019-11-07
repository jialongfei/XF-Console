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
            <label class="layui-form-label">权限名称</label>
            <div class="layui-input-block">
                <input value="{{ $info->name }}" type="text" name="name" lay-verify="required" lay-reqtext="权限名称不能为空" placeholder="请输入权限名称" autocomplete="on" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">父级权限</label>
            <div class="layui-input-block">
                <select name="pid" placeholder="父级权限" lay-search="">
                    <option value="">父级权限：可直接选择或搜索选择</option>
                    @foreach ($parent_list as $parent)
                        <option {{ $parent->id == $info->pid ?'selected':'' }} value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @foreach ($parent->children as $children)
                            <option {{ $children->id == $info->pid ?'selected':'' }} value="{{ $children->id }}">　　{{ $children->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">URI</label>
            <div class="layui-input-block">
                <input value="{{ $info->path }}" type="text" name="path" placeholder="对应的URI,如: /user..." class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">排序规则</label>
            <div class="layui-input-block">
                <input value="{{ $info->sort }}" type="text" name="sort" placeholder="排序规则,建议使用10的正整数" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">导航显示</label>
            <div class="layui-input-block">
                <input @if ($info->is_show == 1) checked="" @endif type="checkbox" name="is_show" lay-skin="switch" lay-filter="is_show" lay-text="Show|Hide">
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
        form.on('switch(is_show)', function(data){
            if (!this.checked){
                // 未选中
            }else{
                // 选中
            }
        });

        //监听提交
        form.on('submit(editform)', function(data){

            if (data.field.is_show == 'on'){
                data.field.is_show = "1";
            }else{
                data.field.is_show = "0";
            }

            $.ajax({
                url: "/permission/edit",
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
