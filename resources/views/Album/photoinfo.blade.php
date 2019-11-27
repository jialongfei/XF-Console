<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/layui/css/layui.css">
    <script src="/layui/layui.js"></script>

    <style>
        .container{
            padding: 15px 0 0 30px;
        }
        .photo-item{
            display: inline-block;
            margin: 5px;
        }
        .photo-item img{
            width: 200px;
            height: 100px;
        }
        .delCurrentImg{
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            border-radius: 10px;
            position: relative;
            top: 10px;
            left: 190px;
            cursor: pointer;
        }
        .delCurrentImg:hover{
            background-color: #fff;
        }
    </style>

</head>
<body>

    <div class="container">

        <div class="layui-row">
            <div class="info-content" style="text-align: center;">
                <button type="button" style="width: 200px;" class="layui-btn" id="uppic">上传图片</button>
            </div>

            <div class="article-content item_content">
                <ul id="photoBox"></ul>
            </div>
        </div>

        <input type="hidden" id="currentAlbumId" value="{{ $id }}">

    </div>

    <script>

        layui.use(['form', 'layedit', 'laydate', 'upload'], function(){
            var form = layui.form
                ,layer = layui.layer
                ,layedit = layui.layedit
                ,laydate = layui.laydate
                ,upload = layui.upload
                ,$ = layui.jquery;

            // get photo list
            $.ajax({
                url: "/album/photoinfo",
                headers:{
                    'X-CSRF-TOKEN':"{{ csrf_token() }}"
                },
                data: {
                    id:$('#currentAlbumId').val(),
                },
                type: "POST",
                dataType: "json",
                success: function(data) {
                    if(!data.status){
                        layer.msg(data.msg)
                    }else{
                        // success
                        var _new_photo = '';

                        for (var i = 0; i < data.data.length; i++) {
                            _new_photo += '<li class="photo-item" data-id="'+data.data[i].id +'">';
                            _new_photo += '     <div class="delCurrentImg"><i class="layui-icon layui-icon-delete"></i></div>';
                            _new_photo += '     <img src="'+data.data[i].path+'" />';
                            _new_photo += '</li>';
                        }

                        $('#photoBox').html(_new_photo)

                    }
                }
            });

            //图片上传
            var uploadInst = upload.render({
                elem: '#uppic'
                ,url: '/upload/img'
                ,field:'image'
                ,multiple: true
                ,headers:{
                    'X-CSRF-TOKEN':"{{ csrf_token() }}"
                }
                ,before: function(obj){
                    //预读本地文件示例，不支持ie8
                    // obj.preview(function(index, file, result){
                    //     $('#previewBox').append('<img src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img">')
                    // });
                }
                ,done: function(res){
                    if(!res.status){
                        return layer.msg(res.msg);
                    }else{
                        // send create photo request
                        $.ajax({
                            url: "/photoinfo/add",
                            headers:{
                                'X-CSRF-TOKEN':"{{ csrf_token() }}"
                            },
                            data: {
                                id:$('#currentAlbumId').val(),
                                _path:res.path
                            },
                            type: "POST",
                            dataType: "json",
                            success: function(data) {
                                if(!data.status){
                                    layer.msg(data.msg)
                                }else{
                                    layer.msg(data.msg)
                                    // 请求成功
                                    var _new_photo = '';
                                    _new_photo += '<li class="photo-item" data-id="'+res.path+'">';
                                    _new_photo += '     <div class="delCurrentImg"><i class="layui-icon layui-icon-delete"></i></div>';
                                    _new_photo += '     <img src="'+res.path+'" />';
                                    _new_photo += '</li>';
                                    $('#photoBox').append(_new_photo)
                                }
                            }
                        });

                    }
                }
                ,error: function(res){
                    return layer.msg(res.msg);
                }
            });

            // delete
            $(document).on('click','.delCurrentImg',function(event){
                var target_id = $(this).parent().data('id');
                $(this).hide()
                var _this = $(this)

                // send delete request
                $.ajax({
                    url: "/photoinfo/del",
                    headers:{
                        'X-CSRF-TOKEN':"{{ csrf_token() }}"
                    },
                    data: {
                        id:target_id
                    },
                    type: "POST",
                    dataType: "json",
                    success: function(data) {
                        if(!data.status){
                            layer.msg(data.msg)
                            _this.show()
                        }else{
                            _this.parent('.photo-item').remove();
                            layer.msg(data.msg)
                        }
                    }
                });
            })

        });

    </script>

</body>
</html>
