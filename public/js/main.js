layui.use(['jquery','element'], function () {
    var $ = layui.$,
        element = layui.element;

    $('.layui-logo span').click(function () {
        window.location.href = '/';
    });

});
