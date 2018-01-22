@extends('admin.master')
@section('css')
    <link href="{{url('/src/bootstrap-table/src/bootstrap-table.css')}}?v=3.22" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-heading">模板消息列表</div>
                    <table id="tb_departments">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12" style="height: 0px;">
                                    <form id="formSearch" class="form-horizontal">
                                        <div class="form-group" style=" margin-top: 27px;">
                                            <div class="col-xs-2">
                                                <input type="text" placeholder="类别名称" style="height: 30px;"
                                                       id="typename" value="">
                                            </div>
                                            <div class="col-xs-1">
                                                <select id="status" style="height: 30px;">
                                                    <option value="">状态</option>
                                                    <option value="0">下线</option>
                                                    <option value="1">上线</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-2">
                                                <input type="text" placeholder="开始日期" style="height: 30px;"
                                                       onfocus="WdatePicker()"
                                                       class=" form-control Wdate" id="start" value="">
                                            </div>
                                            <div class="col-xs-2">
                                                <input type="text" placeholder="结束日期" value="" style="height: 30px;"
                                                       onfocus="WdatePicker()"
                                                       class=" form-control Wdate" id="end">
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" id="btn_query"
                                                        class="btn btn-primary btn-sm"
                                                        onclick="doSearch()">查询
                                                </button>
                                            </div>
                                            <div class="col-sm-1">
                                                <button type="button" id="btn_query"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="addcategory()">添加类别
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
    <!--编辑信息-->
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
                        id="exampleModalLabel">类别编辑</h4>
                </div>
                <div class="modal-body">
                    <form id="smallprogram_form"
                          class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">类别名称</label>
                            <div class="col-sm-8">
                                <input type="text"
                                       id="type_name"
                                       name="type_name"
                                       class="form-control"
                                       placeholder="类别名称...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">类别状态</label>
                            <div class="col-sm-8">
                                <select id="type_status" class="form-control m-b">
                                    <option value="0">下线</option>
                                    <option value="1">上线</option>
                                </select>
                            </div>
                        </div>
                    </form>
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
    <!--编辑信息-->
@endsection
@section('js')
    <script src="{{url('/src/bootstrap-table/src/bootstrap-table.js')}}" type="text/javascript"></script>
    <script src="{{url('/src/bootstrap-table/src/locale/bootstrap-table-zh-CN.js')}}"></script>
    <script src="{{url('/src/easyui/js/DatePicker/WdatePicker.js')}}"></script>
    <script>
        $(function () {
            //1.初始化Table
            var oTable = new TableInit();
            oTable.Init();
            //2.初始化Button的点击事件
            var oButtonInit = new ButtonInit();
            oButtonInit.Init();
        });
        var url;
        var type1 = '';
        function doSearch(type) {
            type1 = type;
            $('#tb_departments').bootstrapTable('refresh', {url: '{{url("admin/category/cateajaxData")}}'});
        }

        var TableInit = function () {
            var oTableInit = new Object();
            //初始化Table
            oTableInit.Init = function () {
                $('#tb_departments').bootstrapTable({
                    url: '{{url("admin/category/cateajaxData")}}',         //请求后台的URL（*）
                    method: 'get',                      //请求方式（*）
                    toolbar: '#toolbar',                //工具按钮用哪个容器
                    striped: true,                      //是否显示行间隔色
                    cache: true,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
                    pagination: true,                   //是否显示分页（*）
                    sortable: true,                     //是否启用排序
                    sortOrder: "asc",                   //排序方式
                    queryParams: queryParams,//传递参数（*）
                    sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
                    pageNumber: 1,                       //初始化加载第一页，默认第一页
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
                    smartDisplay: false,//智能显示分页按钮
                    classes: 'table table-bordered table-responsive table-hover', // Class样式
                    paginationPreText: '上一页',
                    paginationNextText: '下一页',
                    columns: [
                        {
                            field: 'tid',
                            title: '序号',
                            formatter: function (value, row, index){
                                return index+1;
                            }
                        },
                        {
                            field: 'ctime',
                            title: '创建时间',
                            align:'center'
                        },
                        {
                            field:'type_name',
                            title:'类别名称',
                            align:'center'
                        },
                        {
                            field:'type_status',
                            title:'类别状态',
                            align:'center',
                            formatter:function(value,row,index){
                                if(row.type_status==0){
                                    return '下线';
                                }else if(row.type_status==1){
                                    return '上线';
                                }
                            }
                        },
                        {
                            field: 'ff',
                            title: '操作',
                            width: 250,
                            formatter: function (value, row, index) {
                                var content = '<a href="javascript:void(0);" class="btn btn-primary btn-xs" onClick="redact('+row.tid+','+row.type_status+',this)"><i class="glyphicon glyphicon-edit"></i>编辑</a>&nbsp;&nbsp;';
                                var html='';
                                if(row.type_status==0){
                                    html= '<a href="javascript:void(0);" class="btn btn-success btn-xs" onClick="issue('+row.tid+','+row.type_status+')" ><i class="glyphicon glyphicon-export"></i>上线</a>';
                                }else if(row.type_status==1){
                                    html='<a href="javascript:void(0);" class="btn btn-danger btn-xs" onClick="issue('+row.tid+','+row.type_status+')"><i class="glyphicon glyphicon-import">下线</i></a>';
                                }
                                return content+html;
//
                            }
                        }
                    ]
                });
            };
            return oTableInit;
        };

        var token = '{{csrf_token()}}';
         function addcategory()
         {
             $('#type_name').val('');
             $('#type_status option:selected').removeAttr('selected');
             $('#type_status option:first').prop('selected','selected');
             $('#myModal').modal('show');
             url='{{url('admin/category/saveData')}}?_token='+token;
         }
        function issue(id,val){
         $.ajax({
             url:'{{url('admin/category/issue')}}?_token='+token,
             data:{'tid':id,'type_status':val},
             type:'post',
             success:function(data){
                 var result = eval('('+data+')');
                 doSearch();
                 if(result.success){
                     layer.msg(result.msg,{icon:1,time:1000});
                 }else{
                     layer.msg(result.msg,{icon:2,time:1000});
                 }
             }
         });
        }

        // 分页查询参数，是以键值对的形式设置的
        function queryParams(params, type) {
            return {
                type: type1,   //类型
                title: $('#typename').val(),
                start: $('#start').val(),
                end: $('#end').val(),
                status: $('#status').val(),
                limit: params.limit, // 每页显示数量
                offset: params.offset // SQL语句偏移量
            }
        }
        function redact(id,val,obj){
            var type_name = $(obj).parent().parent().find('td').eq(3).text();
            var status = val;
            $('#type_status option[value="'+val+'"]').prop('selected','selected');
            $('#type_name').val(type_name);
             $('#myModal').modal('show');
             url='{{url('admin/category/updateData')}}?tid='+id+'&_token='+token;
        }
        $('#submitOn').click(function(){
            var type_name = $('#type_name').val();
            var type_status = $('#type_status').val();
            if(type_name==''||type_status==''){
                layer.msg('请填写完整的数据',{icon:1,time:1000});
                return;
            }
            $.ajax({
                url:url,
                data:{'type_name':type_name,'type_status':type_status},
                type:'post',
                success:function(data){
                    var result = eval('('+data+')');
                    $('#myModal').modal('hide');
                    if(result.success){
                        layer.msg(result.msg,{icon:1,time:1000});
                    }else{
                        layer.msg(result.msg,{icon:2,time:1000});
                    }
                    doSearch();
                }
            })
        })
    </script>
@endsection
