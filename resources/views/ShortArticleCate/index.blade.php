@extends('main')

@section('title', '短期-分类列表')

@section('content')

    <link rel="stylesheet" href="/css/Index/index.css">

    <div id="tableBox">

        <table class="layui-hide" id="layTable" lay-filter="table"></table>

        <script type="text/html" id="tableTopTools">
            <div class="search-box">
                <div class="layui-inline">
                    <input class="layui-input" name="search" id="search" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload">搜索</button>
            </div>
        </script>

    </div>
@endsection

@section('js')
    <script>

        var table_height = document.documentElement.clientHeight - 125;

        layui.use('table', function(){
            var table = layui.table;

            //方法级渲染
            table.render({
                elem: '#layTable'
                ,height:table_height
                ,url:'/short/article/cate'
                ,method:'post'
                ,headers:{
                    'X-CSRF-TOKEN':"{{ csrf_token() }}"
                }
                ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
                ,toolbar: '#tableTopTools' //开启头部工具栏，并为其绑定左侧模板
                ,defaultToolbar: [
                    {
                        title: '新增'
                        ,layEvent: 'DIY_ADD'
                        ,icon: 'layui-icon-add-1'
                    },
                    {
                        title: '编辑'
                        ,layEvent: 'DIY_EDIT'
                        ,icon: 'layui-icon-edit'
                    },
                    {
                        title: '删除'
                        ,layEvent: 'DIY_DEL'
                        ,icon: 'layui-icon-delete'
                    },
                ]
                ,title: '数据列表'
                ,cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    ,{field:'id', title:'ID', hide: true}
                    ,{field:'name', title:'名称', fixed: 'left', templet: function (res) {
                            return "<span class='detail-btn' data-id='"+res.id+"'>"+res.name+"</span>";
                        }}
                    ,{field:'position', title:'定位'}
                    ,{field:'sort', title:'排序'}
                    ,{field:'status', title:'状态', templet: function (res) {
                            return res.status==1?'Show':'Hide';
                        }}
                    ,{field:'created_at', title:'创建时间'}
                    ,{field:'create_user_name', title:'创建人'}
                    ,{field:'updated_at', title:'修改时间'}
                    ,{field:'update_user_name', title:'修改人'}
                ]]
                ,id: 'tableReload'
                ,limits: [10,20,30,40,50,100] // 可选的页显示数量
                ,limit: 20 //每页默认显示的数量
                ,page: true
            });

            //头工具栏事件
            table.on('toolbar(table)', function(obj){
                var checkStatus = table.checkStatus(obj.config.id);
                switch(obj.event){
                    //自定义头工具栏右侧图标 - 新增
                    case 'DIY_ADD':

                        layer.open({
                            type: 2,
                            title: '新增',
                            area: [document.documentElement.clientWidth*0.7 +'px', document.documentElement.clientHeight*0.9 +'px'],
                            shadeClose:true,
                            content: [window.location.href+'/add', 'yes'], // 若禁止显示iframe中的滚动条将 yes 改为 no
                            anim:0,
                            id:'addPage'
                        });

                        break;
                    // 自定义头工具栏右侧图标 - 编辑
                    case 'DIY_EDIT':
                        var data = checkStatus.data;
                        if (data.length < 1){
                            layer.msg('请选择要操作的数据');
                            return false;
                        }
                        if (data.length > 1){
                            layer.msg('编辑操作仅限单条数据');
                            return false;
                        }

                        layer.open({
                            type: 2,
                            title: '修改',
                            area: [document.documentElement.clientWidth*0.7 +'px', document.documentElement.clientHeight*0.9 +'px'],
                            shadeClose:true,
                            content: [window.location.href+'/edit'+'?id='+data[0].id, 'yes'], // 若禁止显示iframe中的滚动条将 yes 改为 no
                            anim:0,
                            id:'editPage'
                        });

                        break;
                    // 自定义头工具栏右侧图标 - 删除
                    case 'DIY_DEL':
                        var data = checkStatus.data;
                        if (data.length < 1){
                            layer.msg('请选择要操作的数据');
                            return false;
                        }

                        var id_array = new Array();

                        for (var i=0;i<data.length;i++){
                            id_array.push(data[i].id);
                        }

                        // 操作确认框
                        layer.open({
                            type: 1
                            ,title: false //不显示标题栏
                            ,closeBtn: false
                            ,area: '300px;'
                            ,shade: 0.8
                            ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                            ,btn: ['确认删除', '取消操作']
                            ,btnAlign: 'c'
                            ,moveType: 1 //拖拽模式，0或者1
                            ,content: '<div style="text-align: center;"><div style="font-size: 20px; font-weight: 300; line-height: 40px;">操作确认</div><div style="line-height: 80px;color: red;">请确认是否要删除该数据?</div></div>'
                            ,success: function(layero){
                                var btn = layero.find('.layui-layer-btn');
                                btn.find('.layui-layer-btn0').click(function () {

                                    $.ajax({
                                        url: window.location.href+"/del",
                                        headers:{
                                            'X-CSRF-TOKEN':"{{ csrf_token() }}"
                                        },
                                        data: {
                                            ids:id_array
                                        },
                                        type: "POST",
                                        dataType: "json",
                                        success: function(data) {

                                            if(!data.status){
                                                layer.msg(data.msg)
                                            }else{
                                                layer.msg(data.msg)

                                                $('.search-box .layui-btn').click();// 表格重载(模拟search框单击事件)

                                            }

                                        }
                                    });

                                });
                            }
                        });

                        break;
                };
            });

            var $ = layui.$, active = {
                reload: function(){
                    var search = $('#search');

                    //执行重载
                    table.reload('tableReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            search: search.val()
                        }
                    }, 'data');
                }
            };

            $(document).on('click','.search-box .layui-btn',function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            })

            $(document).on('click','.detail-btn',function(){
                layer.open({
                    type: 2,
                    title: '详情',
                    area: [document.documentElement.clientWidth*0.7 +'px', document.documentElement.clientHeight*0.9 +'px'],
                    shadeClose:true,
                    content: [window.location.href+'/detail'+'?id='+$(this).data('id'), 'yes'], // 若禁止显示iframe中的滚动条将 yes 改为 no
                    anim:0,
                    id:'detailPage'
                });
            })

        });

    </script>

@endsection
