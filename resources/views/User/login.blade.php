<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/popup.css">
    <script src="/layui/layui.js"></script>
    <style>
        .login-box{
            max-width: 350px;
            margin: 200px auto;
        }
        .layui-form-item{
            text-align: center;
        }
        .layui-btn{
            width: 280px;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <form class="layui-form" id="xForm">

            <div class="layui-form-item">
                <label class="layui-form-label">登录邮箱</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" lay-verify="required" lay-reqtext="请输入登录邮箱" placeholder="请输入登录邮箱" autocomplete="on" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">登录密码</label>
                <div class="layui-input-inline">
                    <input type="password" name="password" lay-verify="required" lay-reqtext="请输入登录密码" placeholder="请输入登录密码" autocomplete="off" class="layui-input">
                </div>
            </div>

{{--            <div class="layui-form-item">--}}
{{--                <label class="layui-form-label">记住密码</label>--}}
{{--                <div class="layui-input-inline">--}}
{{--                    <input type="checkbox" checked="" name="remember" lay-skin="switch" lay-filter="remember" lay-text="记住密码|不用了">--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="layui-form-item">
                <button type="submit" class="layui-btn" lay-submit="" lay-filter="loginform">登录</button>
            </div>
        </form>
    </div>

<script>
    layui.use(['form', 'layedit', 'laydate', 'upload'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,$ = layui.jquery;

        //监听指定开关
        form.on('switch(status)', function(data){
            if (!this.checked){
                // close
            }else{
                // on
            }
        });

        //监听提交
        form.on('submit(loginform)', function(data){

            if (data.field.remember == 'on'){
                data.field.remember = "1";
            }else{
                data.field.remember = "0";
            }

            $.ajax({
                url: "/login",
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
                        layer.msg(data.msg)
                        window.location.href = "/";
                    }

                }
            });

            return false;
        });

    });
</script>

</body>
</html>
