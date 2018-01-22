@extends('admin.master')
@section('css')
    <link href="{{url('/src/bootstrap-table/src/bootstrap-table.css')}}?v=3.22"
          rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-heading">文章列表</div>
                    <table id="tb_departments">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12"
                                     style="height: 0px;">
                                    <form id="formSearch"
                                          class="form-horizontal">
                                        <div class="form-group"
                                             style=" margin-top: 27px;">
                                            <div class="col-xs-1">
                                                <input type="text"
                                                       placeholder="文章标题"
                                                       style="height: 30px;"
                                                       id="title"
                                                       value="">
                                            </div>
                                            <div class="col-xs-1">
                                                <select id="typename"
                                                        style="height: 30px;">
                                                    <option value="">选择分类</option>
                                                    @foreach($clists as $value)
                                                        @if($value['pid'] == 0)
                                                            <option value="{{$value['id']}}"><i>{{$value['name']}}</i></option>
                                                        @elseif($value['pid'] != 0)
                                                            <option value="{{$value['id']}}">{{$value['name']}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xs-1">
                                                <select id="status"
                                                        style="height: 30px;">
                                                    <option value="">选择状态</option>
                                                    <option value="0">未发布</option>
                                                    <option value="1">已发布</option>
                                                    <option value="2">已删除</option>
                                                    <option value="4">待发布</option>
                                                    <option value="3">草稿箱</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-1">
                                                <select id="username"
                                                        style="height: 30px;">
                                                    <option value="">选择作者</option>
                                                    @foreach($user as $value)
                                                        <option value="{{$value->username}}">{{$value->username}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xs-2">
                                                <input type="text"
                                                       placeholder="开始日期"
                                                       style="height: 30px;"
                                                       onfocus="WdatePicker()"
                                                       class=" form-control Wdate"
                                                       id="start"
                                                       value="">
                                            </div>
                                            <div class="col-xs-2">
                                                <input type="text"
                                                       placeholder="结束日期"
                                                       value=""
                                                       style="height: 30px;"
                                                       onfocus="WdatePicker()"
                                                       class=" form-control Wdate"
                                                       id="end">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button"
                                                        id="btn_query"
                                                        class="btn btn-primary btn-sm"
                                                        onclick="doSearch()">查询
                                                </button>
                                            </div>
                                            <div class="col-sm-3">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button"
                                                        id="btn_query"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="addArticle()">添加文章
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--查看内容-->
    <div class="modal fade"
         id="myModal"
         role="dialog"
         aria-labelledby="exampleModalLabel">
        <div class="modal-dialog"
             role="document">
            <div class="modal-content"
                 style="width:130%;">
                <div class="modal-header">
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="exampleModalLabel">频道编辑</h4>
                </div>
                <div class="modal-body">
                    <textarea cols="92"
                              rows="16"
                              readonly="readonly"
                              id="contentpreview"
                              value="">

                    </textarea>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-default"
                            data-dismiss="modal">取消
                    </button>
                    <button type="button"
                            class="btn btn-primary"
                            id="submitOn">确定
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{url('/src/bootstrap-table/src/bootstrap-table.js')}}"
            type="text/javascript"></script>
    <script src="{{url('/src/bootstrap-table/src/locale/bootstrap-table-zh-CN.js')}}"></script>
    <script src="{{url('/src/easyui/js/DatePicker/WdatePicker.js')}}"></script>
    <script src="{{url('/js/jquery.zclip.js')}}"></script>
    <script>
        var off;
        var li;
        $(function () {
            //1.初始化Table
            var oTable = new TableInit();
            oTable.Init();
        });
        var type1 = '';
        function doSearch(type) {
            type1 = type;
            $('#tb_departments').bootstrapTable('refresh', {url: '{{url("admin/article/ajaxData")}}'});
        }

        var pageNumber = '';  //定义加载的时候是第几页

        @if(!empty(1))  //修改是才会返回到当前页面
                @if(!empty($_COOKIE['pageNumber']))
                         pageNumber = '{{$_COOKIE['pageNumber']}}';
                 @endif
        @endif

        if (pageNumber == ''){
            pageNumber = 1;
        }

        var TableInit = function () {
            var oTableInit = new Object();
            //初始化Table
            oTableInit.Init = function () {
                $('#tb_departments').bootstrapTable({
                    url: '{{url("admin/article/ajaxData")}}',         //请求后台的URL（*）
                    method: 'get',                      //请求方式（*）
                    toolbar: '#toolbar',                //工具按钮用哪个容器
                    striped: true,                      //是否显示行间隔色
                    cache: true,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
                    pagination: true,                   //是否显示分页（*）
                    sortable: true,                     //是否启用排序
                    sortOrder: "asc",                   //排序方式
                    queryParams: queryParams,//传递参数（*）
                    sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
                    pageNumber: pageNumber,             //初始化加载第一页，默认第一页
                    pageSize: 20,                       //每页的记录行数（*）
                    pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
                    search: false,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
                    strictSearch: false,                  //搜索表格
                    showColumns: true,                  //是否显示所有的列
                    showRefresh: true,                  //是否显示刷新按钮
                    minimumCountColumns: 1,             //最少允许的列数
                    clickToSelect: true,                //是否启用点击选中行
                    uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                    showToggle: true,                    //是否显示详细视图和列表视图的切换按钮
                    cardView: false,                    //是否显示详细视图
                    detailView: false,                   //是否显示父子表
                    showFooter: false,                    //显示表格数据
                    smartDisplay: true,                //智能显示分页按钮
                    classes: 'table table-bordered table-responsive table-hover', // Class样式
                    paginationPreText: '上一页',
                    paginationNextText: '下一页',
                    paginationShowPageGo: true,
                    columns: [
                        {
                            field: 'aid',
                            title: '序号',
                            formatter: function (value, row, index) {
                                return index + 1;
                            }
                        },
                        {
                            field: 'aid',
                            title: '文章id',
                            align: 'center'
                        },
                        {
                            field: 'ctime',
                            title: '创建时间',
                            align: 'center'
                        },
                        {
                            field: 'tid',
                            title: '分类Id',
                            align: 'center'
                        },
                        {
                            field: 'type_name',
                            title: '分类名称',
                            align: 'center'
                        },
                        {
                            field: 'cid',
                            title: '类目ID',
                            align: 'center'
                        },
                        {
                            field: 'face_account',
                            title: '推广账号',
                            align: 'center'
                        },
                        {
                            field: 'column_name',
                            title: '栏目名称',
                            align: 'center'
                        },
                        {
                            field: 'title',
                            title: '文章标题',
                            align: 'center'
                        },
                        {
                            field: 'article_status',
                            title: '文章状态',
                            align: 'center',
                            formatter: function (value, row, index) {
                                if (row.article_status == 0) {
                                    return '未发布';
                                } else if (row.article_status == 1) {
                                    return '已发布';
                                } else if (row.article_status == 2) {
                                    return '已删除';
                                } else if (row.article_status == 4) {
                                    return '等待发送';
                                } else if (row.article_status == 3) {
                                    return '草稿箱';
                                }
                            }
                        },
                        {
                            field: 'author',
                            title: '作者'
                        },
                        {
                            field: 'send_time',
                            title: '发送时间',
                            align: 'center'
                        },
                        {
                            field: 'ff',
                            title: '操作',
                            width: 250,
                            formatter: function (value, row, index) {
//                                if(row.article_status==0){
                                return '<a href="javascript:void(0);" class="btn btn-success btn-xs" onClick="showLook(' + row.aid + ')" >预览</a>&nbsp;&nbsp;' +
                                    '<a href="javascript:void(0);" class="btn btn-danger btn-xs" onClick="resource_edit(' + row.aid + ')">修改</a>&nbsp;&nbsp;' +
                                    '<a href="javascript:void(0);" class="btn btn-danger btn-xs" onClick="articleUrl(' + row.aid + ')">复制链接</a>&nbsp;&nbsp;' +
                                    '<a href="javascript:void(0);" class="btn btn-danger btn-xs" onClick="resource_delete(' + row.aid + ')">删除</a>&nbsp;&nbsp;';
                            }
                        }
                    ]
                });
            };
            return oTableInit;
        };

        var token = '{{csrf_token()}}';
        function point_see(id) {
            $.ajax({
                url: '{{url('admin/article/pointSee')}}?_token=' + token,
                data: {'id': id},
                type: 'post',
                success: function (data) {
                    var result = eval('(' + data + ')');
                    var content = result.content;
                    $('#contentpreview').val(content);
                    $('#myModal').modal('show');
                }
            })
        }

        /**
         *删除文章resource
         */
        function resource_delete(id) {
            layer.confirm('您确定删除吗', {
                btn: ['确定', '取消'] //按钮
            }, function () {
                $.ajax({
                    url: "{{url('admin/article/articleDelete')}}",
                    type: "post",
                    data: {"id": id, "_token": token},
                    success: function (data) {
                        var result = eval('(' + data + ')');
                        if (result.success) {
                            doSearch();
                            layer.msg('删除成功', {icon: 1});
                        } else {
                            layer.msg('删除失败', {icon: 2});
                        }
                    },
                    error: function (e) {
                        alert("错误！！");
                        window.clearInterval(timer);
                    }
                });
            }, function () {

            });
        }


        // 分页查询参数，是以键值对的形式设置的
        function queryParams(params, type) {
            var limitS  = params.limit;
            var offsetS = params.offset;
            var pageNum  = Number(offsetS/limitS)+1;
            set_cookie(pageNum,1);  //存入到cookie
            return {
                type: type1,   //类型
                title: $('#title').val(),
                start: $('#start').val(),
                end: $('#end').val(),
                typename: $('#typename').val(),
                status: $('#status').val(),
                username: $('#username').val(),
                limit: params.limit, // 每页显示数量
                offset: params.offset // SQL语句偏移量
            }
        }
//        $table.bootstrapTable("getOptions").pageNumber



        function resource_edit(id) {
            //存取当前的页面数
            window.location.href = '{{url('admin/article/articleEdit')}}?aid=' + id;
        }
        function addArticle() {
            window.location.href = '{{url('admin/article/index')}}';
        }


        function showLook(id) {
            $.ajax({
                'url': '{{url("admin/article/getArticleDetail")}}?_token={{csrf_token()}}&aid=' + id,
                'dataType': 'json',
                success: function (res) {
                    if (res.success) {
                        var web_url = res.data.web_url;
                        var cid = res.data.cid;
                        var web_link = 'http://' + web_url + '/api/index/details?aid=' + id + '&cid=' + cid;
                        layer.open({
                            type: 2,
                            title: '文章效果预览',
                            shadeClose: true,
                            shade: false,
                            maxmin: true, //开启最大化最小化按钮
                            area: ['374px', '667px'],
                            content: web_link,
                        });
                    }
                }
            });
        }
        /**
         * 文章url
         * @param id
         */
        function articleUrl(id, type) {
            $.ajax({
                'url': '{{url("admin/article/getArticleDetail")}}?_token={{csrf_token()}}&aid=' + id,
                'dataType': 'json',
                success: function (res) {
                    if (res.success) {
                        var web_url = res.data.web_url;
                        var cid = res.data.cid;
                        var content = 'http://' + web_url + '/api/index/details?aid=' + id + '&cid=' + cid;
//                        layer.alert(content, {
//                            skin: 'layui-layer-lan'
//                            , closeBtn: 0
//                            , anim: 4 //动画类型
//                        });

                        layer.open({
                            content: content,
                            title: '网址链接',
                            btn: ['复制'],
                            yes: function (index, layero) {
                                var copyBtn = $('.layui-layer-btn0');
                                var txt = $('.layui-layer-content').html();
                                copy_url(copyBtn, txt, index);
                            }

                        });
                    }
                }
            });
        }

        function copy_url(obj, txt, index) {
            var txt = txt.replace('amp;', '', txt);
            $(obj).zclip({
                path: "{{url('/js/ZeroClipboard.swf')}}",
                copy: function () {
                    return txt;
                },
                afterCopy: function () {
                    layer.msg("复制成功", {icon: 1, time: 1000});
                    layer.close(index);
                }
            });
        }

        function set_cookie(pageNumber,day) {
            var Days = day;
            var exp = new Date();
            exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
            document.cookie = 'pageNumber' + "=" + escape(pageNumber) + ";expires=" + exp.toGMTString() + "; path=/admin/";
        }

    </script>
@endsection
