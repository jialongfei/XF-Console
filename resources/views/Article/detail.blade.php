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
        .title-show{
            color: #003c00;
        }
        .title-hide{
            color: #9F9F9F;
            text-decoration:line-through
        }
    </style>

</head>
<body>

    <div class="container">

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">标题： </span>
                    <span class="info-content {{$status?'title-show':'title-hide'}}">{{$title}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">标签： </span>
                    <span class="info-content">{{$cate}}</span>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">点击次数： </span>
                    <span class="info-content">{{$click}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">排序： </span>
                    <span class="info-content">{{$sort}}</span>
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

        <div class="layui-row">
            <div class="layui-col-xs12">
                <div class="info-item">
                    <span class="info-title">关键字： </span>
                    <span class="info-longtext">{{$keywords}}</span>
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
            <img src="{{$litpic}}" alt="" class="litpic">
        </div>

        <div class="article-content">
            {!! $body !!}
        </div>

    </div>

</body>
</html>
