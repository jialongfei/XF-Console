layui.use(['jquery','element','layer'], function () {
    var $ = layui.$,
        layer = layui.layer,
        element = layui.element;

    $('.layui-logo span').click(function () {
        window.location.href = '/';
    });

    // 任何需要执行的js特效
    $(document).on('click','#mysetting',function(){
        layer.open({
            type: 2,
            title: '基本资料',
            area: [document.documentElement.clientWidth*0.7 +'px', document.documentElement.clientHeight*0.9 +'px'],
            shadeClose:true,
            content: ['/mysetting'+'?id='+$(this).data('id'), 'yes'], // 若禁止显示iframe中的滚动条将 yes 改为 no
            anim:0,
            id:'mysettingPage'
        });
    })

});
