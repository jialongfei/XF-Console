<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/popup.css">
    <script src="/layui/layui.js"></script>

    <style>
        .pl-30{
            padding-left: 30px;
        }
    </style>

</head>
<body>

<div class="popup-box">

    <form class="layui-form" id="xForm">

        @foreach ($permission_list as $permission)
            <div class="layui-form-item" pane="">
                <div class="layui-input-inline">
                    <input type="checkbox" class="percheckedbox" name="per_ids[]" value="{{ $permission->id }}" title="{{ $permission->name }}" {{ in_array($permission->id,$has_per) ?'checked=""':''}} >
                </div>
                @foreach ($permission->children as $children)
                    <div class="layui-row pl-30">
                        <div class="layui-input-inline">
                            <input type="checkbox" class="percheckedbox" name="per_ids[]" value="{{ $children->id }}" title="{{ $children->name }}" {{ in_array($children->id,$has_per) ?'checked=""':''}} >
                        </div>
                        <div class="layui-row pl-30">
                            @foreach ($children->children as $child)
                                <div class="layui-input-inline">
                                    <input type="checkbox" class="percheckedbox" name="per_ids[]" value="{{ $child->id }}" title="{{ $child->name }}" {{ in_array($child->id,$has_per) ?'checked=""':''}} >
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
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
                url: "/role/permission",
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

        $(document).on('click','.layui-unselect',function(){
            if ($(this).hasClass('layui-form-checked')){

                $(this).parent().parent().prevAll('.layui-input-inline').find('.layui-unselect').addClass('layui-form-checked')
                $(this).parent().parent().prevAll('.layui-input-inline').find('.percheckedbox').prop("checked", true);

                $(this).parent().nextAll('.pl-30').find('.layui-unselect').addClass('layui-form-checked')
                $(this).parent().nextAll('.pl-30').find('.percheckedbox').prop("checked", true);
            }else{

                $(this).parent().parent().prevAll('.layui-input-inline').find('.layui-unselect').removeClass('layui-form-checked')
                $(this).parent().parent().prevAll('.layui-input-inline').find('.percheckedbox').prop("checked", false);

                $(this).parent().nextAll('.pl-30').find('.layui-unselect').removeClass('layui-form-checked')
                $(this).parent().nextAll('.pl-30').find('.percheckedbox').prop("checked", false);
            }
        })

    });
</script>

</body>
</html>
