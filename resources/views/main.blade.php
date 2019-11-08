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
{{--            <li class="layui-nav-item"><a href="/">系统设置</a></li>--}}
        </ul>
        {{--    top - right nav    --}}
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="{{\Illuminate\Support\Facades\Session::get('user.avatar') ?:'/avatar/default.jpg'}}" class="layui-nav-img">
                    {{\Illuminate\Support\Facades\Session::get('user.name')}}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" id="mysetting">个人信息</a></dd>
                    <dd><a href="javascript:;" id="resetPwd">登录密码</a></dd>
                    <dd><a href="/logout">退出登录</a></dd>
                </dl>
            </li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            {{--      left nav      --}}
            <ul class="layui-nav layui-nav-tree" lay-filter="test">
                @foreach ($navs as $nav)
                    @if ($nav->pid == 0 && $nav->is_show == 1 && in_array($nav->id,\Illuminate\Support\Facades\Session::get('permissions')))
                        <li class="layui-nav-item left-nav-box">
                            <a class="" href="javascript:;">{{ $nav->name }}</a>
                            <dl class="layui-nav-child">
                            @foreach ($navs as $child)
                                @if ($child->pid == $nav->id && $child->is_show == 1 && in_array($child->id,\Illuminate\Support\Facades\Session::get('permissions')))
                                    <dd class="left-nav-child {{'/'.\Illuminate\Support\Facades\Request::path()==$child->path?'layui-this':''}}"><a href="{{ $child->path }}">{{ $child->name }}</a></dd>
                                @endif
                            @endforeach
                            </dl>
                        </li>
                    @endif
                @endforeach
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
