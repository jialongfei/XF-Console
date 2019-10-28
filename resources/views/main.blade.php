<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>XF - @yield('title')</title>
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo"><span>XF - Console</span></div>
        {{--    top - left nav    --}}
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item"><a href="">系统设置</a></li>
        </ul>
        {{--    top - right nav    --}}
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=1303598545,3755464209&fm=15&gp=0.jpg" class="layui-nav-img">
                    XF
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="">基本资料</a></dd>
                    <dd><a href="">安全设置</a></dd>
                    <dd><a href="">退出登录</a></dd>
                </dl>
            </li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            {{--      left nav      --}}
            <ul class="layui-nav layui-nav-tree" lay-filter="test">
                <li class="layui-nav-item layui-nav-itemed">
                    <a class="" href="javascript:;">用户</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">用户</a></dd>
                        <dd><a href="javascript:;">角色</a></dd>
                        <dd><a href="javascript:;">权限</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">文章</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">分类</a></dd>
                        <dd><a href="javascript:;">文章</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>

    <div class="layui-body">
        @section('content')
            <div style="padding: 15px;"></div>
        @show
    </div>

    <div class="layui-footer">
        @section('footer')
            XF - Console
        @show
    </div>
</div>

<script src="/layui/layui.js"></script>
<script src="/js/main.js"></script>

</body>
</html>
