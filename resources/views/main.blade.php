<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>XF - @yield('title')</title>
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/main.css">
    <script src="/layui/layui.js"></script>
    <script src="/js/main.js"></script>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo"><span>XF - Console</span></div>
        {{--    top - left nav    --}}
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item"><a href="/">系统设置</a></li>
        </ul>
        {{--    top - right nav    --}}
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="{{\Illuminate\Support\Facades\Session::get('user.avatar') ?:'/avatar/default.jpg'}}" class="layui-nav-img">
                    {{\Illuminate\Support\Facades\Session::get('user.name')}}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" id="mysetting" data-id="{{\Illuminate\Support\Facades\Session::get('user.id')}}">个人信息</a></dd>
                    <dd><a href="">登录密码</a></dd>
                    <dd><a href="/logout">退出登录</a></dd>
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
                        <dd><a href="/user">用户</a></dd>
                        <dd><a href="/role">角色</a></dd>
                        <dd><a href="/permission">权限</a></dd>
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

@section('js')

@show

</body>
</html>
