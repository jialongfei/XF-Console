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
            <div class="layui-col-xs12">
                <div class="info-item">
                    <span class="info-title">权限名称： </span>
                    <span class="info-content">{{$name}}</span>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">父级权限： </span>
                    <span class="info-content">{{$parent_name?:'-'}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">URI： </span>
                    <span class="info-content">{{$path}}</span>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">排序规则： </span>
                    <span class="info-content">{{$sort}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">导航显示： </span>
                    <span class="info-content">{{$is_show?'Show':'Hide'}}</span>
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
