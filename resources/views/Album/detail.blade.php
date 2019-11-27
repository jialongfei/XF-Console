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
        .info-litpic img{
            width: 230px;
            height: 135px;
            margin-bottom: 10px;
        }
        .item_content ul  {
            list-style:none;
        }
        .item_content ul li {
            width:200px;
            height:120px;
            float:left;
            margin:10px
        }
        .item_content {
            width:740px;
            height:460px;
        }
        .item_content .item {
            width:200px;
            height:120px;
            line-height:120px;
            text-align:center;
            cursor:pointer;
        }
        .item_content .item img {
            width:200px;
            height:120px;
            border-radius:6px;
        }
        .delCurrentImg{
            position: absolute;
            right: -7px;
            top: -5px;
            background-color: white;
            height: 16px;
            width: 16px;
            line-height: 16px;
            border-radius: 8px;
        }
    </style>

</head>
<body>

    <div class="container">

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">封面图片： </span>
                    <span class="info-litpic"><img src="{{$litpic?:'/default/litpic.jpg'}}" alt="" class="litpic"></span>
                </div>
            </div>
        </div>

        <div class="layui-row">
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">标题： </span>
                    <span class="info-content {{$status?'title-show':'title-hide'}}">{{$title}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">状态： </span>
                    <span class="info-content">{{$status == 1?'Show':'Hide'}}</span>
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
                    <span class="info-title">描述： </span>
                    <span class="info-longtext">{{$description}}</span>
                </div>
            </div>
        </div>

    </div>

    <script>

        layui.use(['form', 'layedit', 'laydate', 'upload'], function(){
            var form = layui.form
                ,layer = layui.layer
                ,layedit = layui.layedit
                ,laydate = layui.laydate
                ,upload = layui.upload
                ,$ = layui.jquery;

        });

    </script>

</body>
</html>
