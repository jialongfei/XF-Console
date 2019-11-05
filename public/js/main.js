layui.use(['jquery','element','layer'], function () {
    var $ = layui.$,
        layer = layui.layer,
        element = layui.element;

    $('.layui-logo span').click(function () {
        window.location.href = '/';
    });

    $(document).on('click','#mysetting',function(){
        layer.open({
            type: 2,
            title: '基本资料',
            area: [document.documentElement.clientWidth*0.7 +'px', document.documentElement.clientHeight*0.9 +'px'],
            shadeClose:true,
            content: ['/mysetting', 'yes'], // 若禁止显示iframe中的滚动条将 yes 改为 no
            anim:0,
            id:'mysettingPage'
        });
    })

    $(document).on('click','#resetPwd',function(){
        layer.open({
            type: 2,
            title: '修改密码',
            area: [document.documentElement.clientWidth*0.3 +'px', document.documentElement.clientHeight*0.5 +'px'],
            shadeClose:true,
            content: ['/resetpwd', 'yes'], // 若禁止显示iframe中的滚动条将 yes 改为 no
            anim:0,
            id:'resetPwdPage'
        });
    })

    $(document).ready(function(){
        $('.left-nav-child').each(function(){
            if ($(this).hasClass('layui-this')){
                $(this).parents('.left-nav-box').addClass('layui-nav-itemed')
            }
        });
    });

});
