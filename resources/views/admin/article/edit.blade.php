@extends('admin.master')
@section('css')
    @include('vendor.ueditor.assets')
    <link href="{{url('/src/admin/plugins/webuploader/diyUpload/css/webuploader.css')}}?v=3.4.0.1"
          rel="stylesheet">
    <link href="{{url('/src/admin/plugins/webuploader/diyUpload/css/diyUpload.css')}}?v=3.4.0.1"
          rel="stylesheet">
    <style>
        #demo {
            width: 100%;
            min-height: 150px;
            background: #C0EBEF;
            border: 3px #CCC dashed;
        }

        .adding_adver {
            position: fixed;
            top: 30%;
            right: 10px;
            width: 100px;
            height: 100px;
            background: linear-gradient(to bottom, #2f7fc3, #0e4c7d);
            color: #fff;
            border-radius: 50%;
            cursor: pointer;
            z-index: 999;
            box-shadow: 1px 1px 1px 1px #999;
            font-size: 18px;
            text-align: center;
            line-height: 100px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>
                        <small>编辑文章</small>&nbsp;&nbsp;&nbsp;
                    </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle"
                           data-toggle="dropdown"
                           href="#"
                           tppabs="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#"
                                   tppabs="#">选项1</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="get"
                          class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标题</label>
                            <div class="col-sm-8">
                                <input type="hidden"
                                       name="aid"
                                       value="{{$aid}}">
                                <input type="text"
                                       id="title"
                                       name="title"
                                       class="form-control"
                                       placeholder="文章标题"
                                       value="{{$articleInfo['title']}}"
                                >
                            </div>
                            <input type="hidden"
                                   name="_token"
                                   value="{{csrf_token()}}">
                            <input type="hidden" name="ctime" id="ctime" value="{{$articleInfo['ctime']}}">
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">文章内容</label>
                            <div class="col-sm-8">
                                <textarea name="content"
                                          class="content"
                                          id="container">{{$articleInfo['content']}}</textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">文章简介</label>
                            <div class="col-sm-8">
                                <textarea type="text"
                                          id="profile"
                                          name="profile"
                                          class="form-control"
                                          placeholder="文章简介">{{$articleInfo['profile'] or ''}}</textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">缩略图</label>
                            <input type="hidden"
                                   name="small_pic"
                                   id="small_pic"
                                   value="{{$small_pic or ''}}">
                            <div class="col-sm-8">
                                <div id="demo">
                                    <div id="as"></div>
                                    <div class="parentFileBox">
                                        <ul class="fileBoxUl">
                                            @foreach(json_decode($articleInfo['small_pic'],true) as $key=>$value)
                                                {{--<li class="diyUploadHover editingthumb" onClick="del_small_pic({{$key}})">--}}
                                                <li class="diyUploadHover editingthumb">
                                                    <div class="viewThumb">
                                                        <img src="{{getenv('VIDEO_DOMAIN')}}{{$value}}"></div>
                                                    <div class="diyCancel" id="Cancel{{$key}}"></div>
                                                    <div class="diySuccess"></div>
                                                    <div class="diyFileName" id="FileName{{$key}}">{{$value}}</div>
                                                    <div class="diyBar">
                                                        <div class="diyProgress"></div>
                                                        <div class="diyProgressText">0%</div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">文章状态</label>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select class="form-control m-b"
                                            name="article_status">
                                        <option value="0"
                                                @if($articleInfo['article_status']=='0')
                                                selected
                                                @endif
                                        >未发布
                                        </option>
                                        <option value="4"
                                                @if($articleInfo['article_status']==4)
                                                selected
                                                @endif
                                        >发布
                                        </option>
                                        <option value="3"
                                                @if($articleInfo['article_status']==3)
                                                selected
                                                @endif
                                        >草稿箱
                                        </option>
                                        <option value="2"
                                                @if($articleInfo['article_status']==2)
                                                selected
                                                @endif
                                        >回收站
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">发布账号</label>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select class="form-control m-b"
                                            id="face_account"
                                            name="face_account">
                                        <option value="0">请选择</option>
                                        @foreach($faceAccount as $item)
                                            <option value="{{$item['uid']}}">{{$item['user_account']}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="user_account" id="user_account" value="{{$articleInfo['face_account']}}">
                                </div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">发布日期</label>
                            <div class="col-sm-3">
                                <input type="text"
                                       placeholder="发布日期"
                                       style="height: 35px;"
                                       name="send_time"
                                       onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"
                                       class=" form-control Wdate"
                                       id="send_time"
                                       value="{{date('Y-m-d H:i:s',time())}}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">选择分类栏目</label>
                            <div class="col-sm-3">
                                <select class="form-control m-b"
                                        id="tid"
                                        name="tid">
                                    @foreach(json_decode($typeColumn,true) as $value)
                                        @if($value['tid']==$articleInfo['tid'])
                                            <option value="{{$value['tid']}}"
                                                    selected>{{$value['type_name']}}</option>
                                        @else
                                            <option value="{{$value['tid']}}">{{$value['type_name']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control m-b"
                                        id="cid"
                                        name="cid">
                                    @foreach(json_decode($typeColumn,true) as $value)
                                        @if($value['tid']==$articleInfo['tid'])
                                            @foreach($value[$value['tid']] as $cVal)
                                                @if($cVal['cid']==$articleInfo['cid'])
                                                    <option value="{{$cVal['cid']}}"
                                                            selected>{{$cVal['column_name']}}</option>
                                                @else
                                                    <option value="{{$cVal['cid']}}"
                                                    >{{$cVal['column_name']}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <input type="hidden"
                               id="uid"
                               name="uid"
                               class="form-control"
                               value="{{session('admin.id')}}">
                        <div class="hr-line-dashed"></div>
                        <div class="form-group"
                             id="submitShow">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-info"
                                   type="submit"
                                   id="trans">简转繁</a>
                                <a class="btn btn-success"
                                   type="submit"
                                   id="getCon"
                                   style="display:none;">下载图片</a>
                                <a class="btn btn-primary"
                                   type="submit"
                                   id="preview">预览文章</a>
                                <a class="btn btn-danger"
                                   type="submit"
                                   id="submitOn">保存</a>
                                <button class="btn btn-white"
                                        type="submit">取消
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="adding_adver" id="adding_adver1">
                    添加广告
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{url('/src/easyui/js/DatePicker/WdatePicker.js')}}"></script>
    <script src="{{url('/src/admin/js/js-session.js')}}"></script>
    {{--阶段福--}}
    <script src="{{url('/src/admin/plugins/webuploader/diyUpload/js/webuploader.html5only.min.js')}}"></script>
    <script src="{{url('/src/admin/plugins/webuploader/diyUpload/js/diyUpload.js')}}"></script>
    <script src="{{url('/src/admin/js/demo.js')}}"></script>


    <script type="text/javascript">


        var ctime = $("#ctime").val();

        //富文本使用
        $(function () {
            var cbg = $("#container p[data-background]:hidden");
            if (cbg.length > 0)
                $("#container").attr("style", cbg.attr("data-background"));
        })

        var ue = new UE.ui.Editor({initialFrameHeight: 500});
        ue.render("container");
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });


        $("#face_account").on('change',function(){
            var user_account = $("#user_account").val();
            var face_account =  $("#face_account").val();
            if(user_account.indexOf(face_account)> -1){
                layer.msg('该账号已经分配，请重新选择其他账号！',{icon:2,time:2000});
                return;
            }
        });


        /**
         * 提交文章
         */
        $("#submitOn").click(function () {

            var cidObj = $("#cid");
            var cid = cidObj.val();
            var column_name = cidObj.find("option:selected").text();

            var tidObj = $("#tid");
            var tid = tidObj.val();
            var type_name = tidObj.find("option:selected").text();

            var formParam = $("form").serialize();
            var title = $("#title").val();
            if (title == '') {
                layer.msg('文章标题不能为空', {icon: 2});
                return;
            } else if (tid == '') {
                layer.msg('分类不能为空', {icon: 2});
                return;
            } else if (cid == '') {
                layer.msg('栏目不能为空', {icon: 2});
                return;
            }
            $.ajax({
                type: 'post',
                url: "{{url('admin/article/editArticle')}}?_token={{csrf_token()}}&column_name=" + column_name + "&type_name=" + type_name,
                data: formParam,
                success: function (data) {
                    var result = eval('(' + data + ')');
                    if (result.success) {
                        var content = "文章修改成功";
                        layer.alert(content, {
                            skin: 'layui-layer-lan'
                            , closeBtn: 0
                            , anim: 4 //动画类型
                        });
                    } else {
                        layer.msg(result.messasge, {icon: 2});
                    }
                },
                error: function (a, b, c) {
                    alert(c);
                }
            });
        });
        /**
         *模板消息预览
         */
        $("#preview").click(function () {
            var token = '{{csrf_token()}}';
            var title = $("#title").val();
            var uid = $("#uid").val();
            if (title == '') {
                layer.msg('文章标题不能为空', {icon: 2});
                return;
            }
            $.ajax({
                type: 'post',
                url: "{{url('admin/article/preview')}}?_token=" + token,
                data: $("form").serialize(),
                success: function (data) {
                    layer.open({
                        type: 2,
                        title: '很多时候，我们想最大化看，比如像这个页面。',
                        shadeClose: true,
                        shade: false,
                        maxmin: true, //开启最大化最小化按钮
                        area: ['375px', '667px'],
                        content: "{{url('admin/article/preview')}}?_token=" + token
                    });
                },
                error: function (a, b, c) {
                    alert(c);
                }
            });
        });
        /**
         *简繁转换
         */
        $("#trans").click(function () {
            var token = '{{csrf_token()}}';
            var title = $("#title").val();
            var content = ue.getContent();
            var profile = $("#profile").val();
            if (title == '') {
                layer.msg('文章标题不能为空', {icon: 2});
                return;
            } else if (content == '') {
                layer.msg('内容不能为空', {icon: 2});
                return;
            }
            $.ajax({
                type: 'post',
                url: "{{url('admin/article/trans')}}?_token=" + token,
                data: {'title': title, 'content': content, 'profile': profile},
                success: function (data) {
                    var result = eval('(' + data + ')');
                    $("#title").val(result.title);
                    $("#profile").val(result.profile);
                    ue.setContent(result.content);
                },
                error: function (a, b, c) {
                    alert(c);
                }
            });
        })
        /**
         * 文章分类
         */
        $('#tid').change(function () {
            var tid = $(this).val();
            var typeColumn = '{!! $typeColumn !!}';
            typeColumn = eval('(' + typeColumn + ')');
            var column = typeColumn[tid][tid];
            var cidColumn = $("#cid");
            var str = '';
            cidColumn.empty();
            column.forEach(function (e) {
                str += '<option value="' + e.cid + '">' + e.column_name + '</option>';
            });
            cidColumn.append(str);
        });


        var pic_path = '';
        $('#as').diyUpload({
            url: "{{url('admin/article/webUploader')}}?_token={{csrf_token()}}&ctime="+ctime,
            success: function (data) {
                if (data.success) {
                    var small = $('#small_pic');
                    pic_path =small.val();

                    if(pic_path){
                        pic_path += '##'+data.message;
                    }else{
                        pic_path += data.message + "##";
                    }
                    small.val(pic_path);
                } else {
                    layer.msg('图片上传失败', {icon: 2});
                }
            },
            error: function (err) {
                console.info(err);
            },
            buttonText: '上传视频',
            chunked: false,
            // 分片大小
            chunkSize: 512 * 1024 * 100,
            //最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
            fileNumLimit: 5,
            fileSizeLimit: 500000 * 1024,
            fileSingleSizeLimit: 50000 * 1024,
            accept: {}
        });


        var ic = 1;
        //添加广告
        $('#adding_adver1').click(function () {
            var html = ue.getContent();
            var str = makeAd(ic);
            ue.execCommand('inserthtml', str);
            ic++;
        });
        /**
         * 制造第几条广告
         * @param i
         */
        function makeAd(i) {
            return '<p><!--第' + i + '广告-->{advert' + i + '}</p>';
        }

        $("#getCon").click(function () {
            var cont = ue.getContent();
            var index = layer.msg('下载中...', {icon: 6, time: 0});
            $.ajax({
                url: "{{url('admin/article/getImg')}}?_token={{csrf_token()}}",
                type: 'post',
                dataType: 'json',
                data: {'content': cont},
                success: function (res) {
                    if (res.success) {
                        layer.close(index);
                        layer.msg('下载成功', {icon: 1, time: 2000});
                        ue.setContent(res.message);
                    }
                }
            })
        })

        //刪除缩略图

        {{--function del_small_pic(key) {--}}
            {{--layer.confirm('确定要删除该图片么，请谨慎操作，一旦删除，则无法恢复！！！', {--}}
                {{--title: '提示！！！',--}}
                {{--btn: ['确定', '取消'] //按钮--}}
            {{--}, function () {--}}
                {{--var small = $("#small_pic");--}}
                {{--var small_pic = small.val();--}}
                {{--//定义一数组--}}
                {{--var small_arr = [];--}}
                {{--small_arr = small_pic.split("##");--}}
                {{--//获取当前操作的字符串--}}
                {{--var current_pic = $('#Cancel' + key).siblings('#FileName' + key).html();--}}
                {{--removeByValue(small_arr, current_pic);--}}
                {{--var small_str = small_arr.join('##');--}}
                {{--small.val(small_str);--}}
                {{--$('#Cancel' + key).parents('.editingthumb').remove();--}}
                {{--var ajaxData = {--}}
                    {{--'_token': '{{csrf_token()}}',--}}
                    {{--'pic': current_pic--}}
                {{--};--}}
                {{--$.ajax({--}}
                    {{--url: '{{url("admin/article/delSmallPic")}}',--}}
                    {{--data: ajaxData,--}}
                    {{--type: 'post',--}}
                    {{--dataType: 'json',--}}
                    {{--success: function (res) {--}}
                        {{--console.log(res);--}}
                        {{--layer.closeAll();--}}
                    {{--}--}}
                {{--})--}}
            {{--}, function () {--}}
                {{--layer.closeAll();--}}
            {{--});--}}
        {{--}--}}
        $('.editingthumb').on('click', '.diyCancel', function () {
                $(this).parents('.editingthumb').remove();
                var small = $("#small_pic");
                var small_pic = small.val();
                //定义一数组
                var small_arr = [];
                //字符分割
                small_arr = small_pic.split("##");
                console.log(small_arr);
                //获取当前操作的字符串
                var current_pic = $(this).siblings('.diyFileName').html();
                console.log(current_pic);
                removeByValue(small_arr, current_pic);

                var small_str = small_arr.join('##');
                small.val(small_str);

                {{--var ajaxData = {--}}
                    {{--'_token': '{{csrf_token()}}',--}}
                    {{--'pic':current_pic--}}
                {{--};--}}
                {{--$.ajax({--}}
                    {{--url:'{{url("admin/article/delSmallPic")}}',--}}
                    {{--data:ajaxData,--}}
                    {{--type:'post',--}}
                    {{--dataType:'json',--}}
                    {{--success:function(res){--}}
                        {{--console.log(res);--}}
                    {{--}--}}
                {{--})--}}
        });

        /**
         * 删除数组摸个元素
         * @param arr
         * @param val
         */
        function removeByValue(arr, val) {
            for (var i = 0; i < arr.length; i++) {
                if (arr[i] == val) {
                    arr.splice(i, 1);
                    break;
                }
            }
        }

        /*
            返回文章列表页
         */
        {{--function goBackList()--}}
        {{--{--}}
            {{--setCookie();--}}
            {{--window.location.href="{{url('admin/article/list')}}";--}}
        {{--}--}}

        function setCookie()
        {
            var Days = 1;
            var exp = new Date();
            exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
            document.cookie = 'flag' + "=" +1 + ";expires=" + exp.toGMTString() + "; path=/admin/";
        }




    </script>
@endsection
