<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/popup.css">
    <script src="/layui/layui.js"></script>
    <script src="/xm-select/xm-select.js"></script>

    <style>
        #preview{
            width: 230px;
            height: 135px;
            margin-bottom: 10px;
        }
    </style>

</head>
<body>

<div class="popup-box">

    <form class="layui-form" id="xForm" enctype="multipart/form-data">

        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required" lay-reqtext="请输入标题" placeholder="请输入标题" autocomplete="on" class="layui-input">
            </div>
        </div>

{{--        <div class="layui-form-item">--}}
{{--            <label class="layui-form-label">分类</label>--}}
{{--            <div class="layui-input-block">--}}
{{--                <select name="cate" id="cate" lay-search>--}}
{{--                    <option value="0">无</option>--}}
{{--                    @foreach ($cates as $cate)--}}
{{--                        <option value="{{$cate->id}}">{{$cate->name}}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}

        <input type="hidden" name="cate">

        <div class="layui-form-item">
            <label class="layui-form-label">封面图片</label>
            <div class="layui-input-block">
                <div class="layui-upload-list layui-inline">
                    <img class="layui-upload-img" id="preview">
                </div>
                <div class="layui-upload-drag layui-inline" id="litpic">
                    <i class="layui-icon"></i>
                    <p>点击上传，或将文件拖拽到此处</p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-block">
                <input type="text" name="description" lay-verify="required" lay-reqtext="请输入描述" placeholder="请输入描述" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">点击次数</label>
            <div class="layui-input-block">
                <input type="number" name="click" placeholder="请输入点击次数" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">排序规则</label>
            <div class="layui-input-block">
                <input type="number" name="sort" placeholder="请输入排序规则" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="checkbox" checked="" name="status" lay-skin="switch" lay-filter="status" lay-text="Show|Hide">
            </div>
        </div>

        <div class="layui-form-item popup-bottom-btn">
            <button type="submit" class="layui-btn" lay-submit="" lay-filter="addform">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>

        <input type="hidden" name="litpic" id="litpicInput">

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
            elem: '#litpic'
            ,url: '/upload/img'
            ,field:'image'
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
                    $('#litpicInput').val(res.path)
                }
            }
            ,error: function(res){
                return layer.msg(res.msg);
            }
        });

        //监听指定开关
        form.on('switch(status)', function(data){
            if (!this.checked){
                // close
            }else{
                // open
            }
        });

        //监听提交
        form.on('submit(addform)', function(data){

            if (data.field.status == 'on'){
                data.field.status = "1";
            }else{
                data.field.status = "0";
            }

            $.ajax({
                url: "/album/add",
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
