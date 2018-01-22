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
        .adding_adver{
            position: fixed;
            top: 30%;
            right: 10px;
            width: 100px;
            height: 100px;
            background: linear-gradient(to bottom,#2f7fc3,#0e4c7d);
            color: #fff;
            border-radius: 50%;
            cursor:pointer;
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
                <div class="ibox-title" style="min-height:58px;">
                    <h5>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal"><i class="fa fa-download"></i>&nbsp;&nbsp;下载文章</button>&nbsp;&nbsp;
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
                                <input type="text"
                                       id="title"
                                       name="title"
                                       class="form-control"
                                       placeholder="文章标题">
                            </div>
                            <input type="hidden"
                                   name="_token"
                                   value="{{csrf_token()}}">
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">文章内容</label>
                            <div class="col-sm-8">
                                <textarea name="content"
                                          class="content"
                                          id="container"></textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">缩略图</label>
                            <input type="hidden"
                                   name="small_pic"
                                   id="small_pic"
                                   value="">
                            <div class="col-sm-8">
                                <div id="demo">
                                    <div id="as"></div>
                                </div>
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
                                          placeholder="文章简介"></textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">文章状态</label>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select class="form-control m-b"
                                            name="article_status">
                                        <option value="0">未发布</option>
                                        <option value="4"
                                                selected>发布
                                        </option>
                                        <option value="3">草稿箱</option>
                                        <option value="2">回收站</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

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
                                        <option value="{{$value['tid']}}">{{$value['type_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control m-b"
                                        id="cid"
                                        name="cid">
                                    @foreach(json_decode($typeColumn,true)[1][1] as $value)
                                        <option value="{{$value['cid']}}"
                                                selected>{{$value['column_name']}}</option>
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
                                   id="getCon" style="display:none;">下载图片</a>
                                <a class="btn btn-primary"
                                   type="submit"
                                   id="preview">预览文章</a>
                                <a class="btn btn-danger"
                                   type="submit"
                                   id="submitOn">发布文章</a>
                                <button class="btn btn-white"
                                        type="submit">取消
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="adding_adver">
                    添加广告
                </div>
            </div>
        </div>
    </div>




    <!--下载文章的modal开始-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">文章下载</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="message-text" class="control-label">文章来源:</label>
                            <select class="form-control m-b" id="article_resource" name="article_resource">
                                <option value="1">一点资讯</option>
                                <option value="2">今日头条</option>
                                <option value="3">公众号</option>
                                <option value="4">百家号</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">链接URL:</label>
                            <textarea class="form-control" id="article_url"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" onClick="downloadArticles()">确定</button>
                </div>
            </div>
        </div>
    </div>
    <!--下载文章的modal结束-->


@endsection
@section('js')
    <script src="{{url('/src/easyui/js/DatePicker/WdatePicker.js')}}"></script>
    <script src="{{url('/src/admin/js/js-session.js')}}"></script>
    {{--阶段福--}}
    <script src="{{url('/src/admin/plugins/webuploader/diyUpload/js/webuploader.html5only.min.js')}}"></script>
    <script src="{{url('/src/admin/plugins/webuploader/diyUpload/js/diyUpload.js')}}"></script>


    <script type="text/javascript">
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
            var small_pic = $("#small_pic").val();

            if (title == '') {
                layer.msg('文章标题不能为空', {icon: 2});
                return;
            }
            else if (small_pic == '') {
                layer.msg('缩略图不能为空', {icon: 2});
                return;
            }

            else if (tid == '') {
                layer.msg('分类不能为空', {icon: 2});
                return;
            } else if (cid == '') {
                layer.msg('栏目不能为空', {icon: 2});
                return;
            }
            $.ajax({
                type: 'post',
                url: "{{url('admin/article/addArticle')}}?_token={{csrf_token()}}&column_name=" + column_name + "&type_name=" + type_name,
                data: formParam,
                success: function (data) {
                    var result = eval('(' + data + ')');
                    if (result.success) {
                        var content = "文章添加成功";
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
            var small_pic = $("#small_pic").val();
            if (title == '') {
                layer.msg('文章标题不能为空', {icon: 2});
                return;
            } else if (small_pic == '') {
                layer.msg('缩略图不能为空', {icon: 2});
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
        })
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
                data: {'title': title, 'content': content,'profile':profile},
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
        //分类选择
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

        //上传分类图片
        var pic_path = '';
        $('#as').diyUpload({
            url: "{{url('admin/article/webUploader')}}?_token={{csrf_token()}}",
            success: function (data) {
                if(data.success){
                    pic_path +=data.message+"##";
                    $("#small_pic").val(pic_path);
                }else{
                    layer.msg('图片上传失败', {icon: 2});
                }

            },
            error: function (err) {
                console.info(err);
            },
            buttonText: '选择图片',
            chunked: false,
            // 分片大小
            chunkSize: 512 * 1024 * 100,
            //最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
            fileNumLimit: 5,
            fileSizeLimit: 500000 * 1024,
            fileSingleSizeLimit: 50000 * 1024,
            accept: {}
        });


        var ic =1;
        //添加广告
        $('.adding_adver').click(function(){
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
            return  '<p><!--第'+i+'广告-->{advert'+i+'}</p>';
        }



        //图片下载到本地
        $("#getCon").click(function(){
            var cont = ue.getContent();
            var index = layer.msg('下载中...',{icon:6});
            $.ajax({
                url:"{{url('admin/article/getImg')}}?_token={{csrf_token()}}",
                type:'post',
                dataType:'json',
                data:{'content':cont},
                success:function(res){
                    if(res.success){
                        layer.close(index);
                        layer.msg('下载成功',{icon:1,time:2000});
                        ue.setContent(res.message);
                    }
                }
            })
        })


        //文章下载
        function downloadArticles(){
            var articleUrl = $('#article_url').val();
            var article_resource = $("#article_resource").val();
            if(!article_resource){
                layer.msg('请选择文章媒体来源',{icon:2,time:2000});
                return;
            }
//            if(article_resource == 4){
//                layer.msg('sorry,resource disabled,please choose others！',{icon:5});
//                return;
//            }
            if(!articleUrl){
                layer.msg('文章链接不能为空',{icon:2,time:2000});
                return;
            }
            layer.open({
                type: 1,
                title:false,
                area: ['200px', '50px'], //宽高
                content: '<div style="width:200px;height:50px;display:block;text-align:center;vertical-align:center;font-size:16px;">下载中。。。</div>',
            });
            $.ajax({
                'url':'/admin/article/fetchArticle?_token={{csrf_token()}}',
                'data':{'articleUrl':articleUrl,'articleResource':article_resource},
                'type':'post',
                'dataType':'json',
                success:function(res){
                    console.log(res);
                    if(res.success){
                        layer.closeAll();
                        layer.msg(res.message,{'icon':1});
                        window.location.href = '{{url('admin/article/list')}}'
                    }else{
                        layer.closeAll();
                        layer.msg(res.message,{'icon':2});
                    }
                }
            })
        }
    </script>

@endsection
