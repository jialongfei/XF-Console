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
                    <span class="info-title">标题： </span>
                    <span class="info-content {{$status?'title-show':'title-hide'}}">{{$title}}</span>
                </div>
            </div>
            <div class="layui-col-xs6">
                <div class="info-item">
                    <span class="info-title">封面图片： </span>
                    <span class="info-litpic"><img src="{{$litpic?:'/default/litpic.jpg'}}" alt="" class="litpic"></span>
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
                    <span class="info-title">状态： </span>
                    <span class="info-content">{{$status == 1?'Show':'Hide'}}</span>
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
            <span class="info-title">图片管理： </span>
            <span class="info-content">
                <button type="button" style="width: 200px;" class="layui-btn layui-btn-xs" id="uppic">上传图片</button>
            </span>

            <div class="article-content item_content">
                <ul>
                    <li>
                        <div class="item" data-id="youku">
                            <img src="/image/2019_11_20/dcd33c6dfc3523a63016c207c98de7bd7169.jpg" />
                            <span class="delCurrentImg"><i class="layui-icon layui-icon-delete"></i></span>
                        </div>
                    </li>

                    <li>
                        <div class="item" data-id="jd">
                            <img src="/image/2019_11_20/dcd33c6dfc3523a63016c207c98de7bd7169.jpg" />
                            <span class="delCurrentImg"><i class="layui-icon layui-icon-delete"></i></span>
                        </div>
                    </li>
                </ul>
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
                        // 返回的 path = res.path
                        // TODO 将返回的path发送给添加照片的接口，本照片的排序值为 当前相册关联的照片中最大排序值+1
                        // TODO 请求成功后，将返回的path拼接后填充到预览图中
                    }
                }
                ,error: function(res){
                    return layer.msg(res.msg);
                }
            });

            $(function() {

                function Pointer(x, y) {
                    this.x = x ;
                    this.y = y ;
                }
                function Position(left, top) {
                    this.left = left ;
                    this.top = top ;
                }
                $(".item_content .item").each(function(i) {
                    this.init = function() {
                        this.box = $(this).parent() ;
                        $(this).attr("index", i).css({
                            position : "absolute",
                            left : this.box.offset().left,
                            top : this.box.offset().top
                        }).appendTo(".item_content") ;
                        this.drag() ;
                    },
                        this.move = function(callback) {
                            $(this).stop(true).animate({
                                left : this.box.offset().left,
                                top : this.box.offset().top
                            }, 500, function() {
                                if(callback) {
                                    callback.call(this) ;
                                }
                            }) ;
                        },
                        this.collisionCheck = function() {
                            var currentItem = this ;
                            var direction = null ;
                            $(this).siblings(".item").each(function() {
                                if(
                                    currentItem.pointer.x > this.box.offset().left &&
                                    currentItem.pointer.y > this.box.offset().top &&
                                    (currentItem.pointer.x < this.box.offset().left + this.box.width()) &&
                                    (currentItem.pointer.y < this.box.offset().top + this.box.height())
                                ) {
                                    if(currentItem.box.offset().top < this.box.offset().top) {
                                        direction = "down" ;
                                    } else if(currentItem.box.offset().top > this.box.offset().top) {
                                        direction = "up" ;
                                    } else {
                                        direction = "normal" ;
                                    }
                                    this.swap(currentItem, direction) ;
                                }
                            }) ;
                        },
                        this.swap = function(currentItem, direction) {
                            if(this.moveing) return false ;
                            var directions = {
                                normal : function() {
                                    var saveBox = this.box ;
                                    this.box = currentItem.box ;
                                    currentItem.box = saveBox ;
                                    this.move() ;
                                    $(this).attr("index", this.box.index()) ;
                                    $(currentItem).attr("index", currentItem.box.index()) ;
                                },
                                down : function() {
                                    var box = this.box ;
                                    var node = this ;
                                    var startIndex = currentItem.box.index() ;
                                    var endIndex = node.box.index(); ;
                                    for(var i = endIndex; i > startIndex ; i--) {
                                        var prevNode = $(".item_content .item[index="+ (i - 1) +"]")[0] ;
                                        node.box = prevNode.box ;
                                        $(node).attr("index", node.box.index()) ;
                                        node.move() ;
                                        node = prevNode ;
                                    }
                                    currentItem.box = box ;
                                    $(currentItem).attr("index", box.index()) ;
                                },
                                up : function() {
                                    var box = this.box ;
                                    var node = this ;
                                    var startIndex = node.box.index() ;
                                    var endIndex = currentItem.box.index(); ;
                                    for(var i = startIndex; i < endIndex; i++) {
                                        var nextNode = $(".item_content .item[index="+ (i + 1) +"]")[0] ;
                                        node.box = nextNode.box ;
                                        $(node).attr("index", node.box.index()) ;
                                        node.move() ;
                                        node = nextNode ;
                                    }
                                    currentItem.box = box ;
                                    $(currentItem).attr("index", box.index()) ;
                                }
                            }
                            directions[direction].call(this) ;
                        },
                        this.drag = function() {
                            var oldPosition = new Position() ;
                            var oldPointer = new Pointer() ;
                            var isDrag = false ;
                            var currentItem = null ;
                            $(this).mousedown(function(e) {
                                e.preventDefault() ;
                                oldPosition.left = $(this).position().left ;
                                oldPosition.top =  $(this).position().top ;
                                oldPointer.x = e.clientX ;
                                oldPointer.y = e.clientY ;
                                isDrag = true ;

                                currentItem = this ;

                            }) ;
                            $(document).mousemove(function(e) {
                                var currentPointer = new Pointer(e.clientX, e.clientY) ;
                                if(!isDrag) return false ;
                                $(currentItem).css({
                                    "opacity" : "0.8",
                                    "z-index" : 999
                                }) ;
                                var left = currentPointer.x - oldPointer.x + oldPosition.left ;
                                var top = currentPointer.y - oldPointer.y + oldPosition.top ;
                                $(currentItem).css({
                                    left : left,
                                    top : top
                                }) ;
                                currentItem.pointer = currentPointer ;

                                currentItem.collisionCheck() ;


                            }) ;
                            $(document).mouseup(function() {
                                if(!isDrag) return false ;
                                isDrag = false ;
                                currentItem.move(function() {
                                    $(this).css({
                                        "opacity" : "1",
                                        "z-index" : 0
                                    }) ;
                                }) ;
                                var new_list = [];// 最新的排序方式
                                // 移动完毕
                                $(".item_content .item").each(function(i) {
                                    new_list[$(this).attr('index')] = $(this).data('id');
                                });
                                // TODO 发送新的排序方式到后台以供更新
                                console.log(new_list);
                            }) ;
                        }
                    this.init() ;
                }) ;
            }) ;

            // TODO 解决冒泡事件
            $(document).on('click','.delCurrentImg',function(event){
                console.log($(this))
                event.stopPropagation();
            })

        });

    </script>

</body>
</html>
