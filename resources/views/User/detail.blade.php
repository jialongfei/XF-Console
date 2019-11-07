<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/layui/css/layui.css">
    <script src="/layui/layui.js"></script>

    <style>
        .info-item{
            line-height: 40px;
        }
        .info-title{
            color: #afafaf;
        }
        .info-content{

        }
        .info-longtext{
            word-break: break-all;
            white-space: normal;
        }
        .container{
            padding: 15px 0 0 30px;
        }
        .info-img{
            text-align: center;
        }
        .info-img img{
            width: 100px;
            height: 100px;
            border-radius:50px;
        }
    </style>

</head>
<body>

    <div class="container">

        <div class="layui-row">
            <div class="layui-col-xs12 info-img">
                <img src="{{$avatar}}">
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">用户名： </span>
                    <span class="info-content">{{$name}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">状态： </span>
                    <span class="info-content">{{$status == 1?'启用':'禁用'}}</span>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">手机： </span>
                    <span class="info-content">{{$phone}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">邮箱： </span>
                    <span class="info-content">{{$email}}</span>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs12">
                <div class="info-item">
                    <span class="info-title">当前角色： </span>
                    <span class="info-longtext">{{$role_names}}</span>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs12">
                <div class="info-item">
                    <span class="info-title">描述： </span>
                    <span class="info-longtext">{{$description}}</span>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">创建人： </span>
                    <span class="info-content">{{$create_user_name}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">创建时间： </span>
                    <span class="info-content">{{$created_at}}</span>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">更新人： </span>
                    <span class="info-content">{{$update_user_name}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">更新时间： </span>
                    <span class="info-content">{{$updated_at}}</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
