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
                                                <input type="text" placeholder="频道名称" style="height: 30px;"
                                                       id="columnname" value="">
                                            </div>
                                            <div class="col-xs-1">
                                                <select id="status" style="height: 30px;">
                                                    <option value="">选择类别</option>
                                                    @foreach($pack as $value)
                                                        <option value="{{$value['tid']}}">{{$value['type_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xs-1">
                                                <select id="onoff" style="height: 30px;">
                                                    <option value="">选择状态</option>
                                                    <option value="1">上线</option>
                                                    <option value="0">下线</option>
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
                                                        onclick="addcategory()">添加频道
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
                        id="exampleModalLabel">频道编辑</h4>
                </div>
                <div class="modal-body">
                    <form id="smallprogram_form"
                          class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">频道名称</label>
                            <div class="col-sm-8">
                                <input type="text"
                                       id="column_name"
                                       name="column_name"
                                       class="form-control"
                                       placeholder="频道名称...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">设置优先级</label>
                            <div class="col-sm-8">
                                <input type="text" id="sort" name="sort"  class="form-control" placeholder="设置优先级..." >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">设置类别</label>
                            <div class="col-sm-8">
                                <select id="tid" class="form-control m-b">
                                 @foreach($pack as $value)
                                        <option value="{{$value['tid']}}">{{$value['type_name']}}</option>
                                 @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">频道状态</label>
                            <div class="col-sm-8">
                                <select id="column_status" class="form-control m-b">
                                    <option value="1">上线</option>
                                    <option value="0">下线</option>
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
            $('#tb_departments').bootstrapTable('refresh', {url: '{{url("admin/category/channelajaxData")}}'});
        }

        var TableInit = function () {
            var oTableInit = new Object();
            //初始化Table
            oTableInit.Init = function () {
                $('#tb_departments').bootstrapTable({
                    url: '{{url("admin/category/channelajaxData")}}',         //请求后台的URL（*）
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
                            field: 'cid',
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
                            field:'column_status',
                            title:'频道状态',
                            align:'center',
                            formatter:function(value,row,index){
                                if(row.column_status==0){
                                    return '下线';
                                }else if(row.column_status==1){
                                    return '上线';
                                }
                            }
                        },
                        {
                            field:'column_name',
                            title:'栏目名称',
                            align:'center'
                        },
                        {
                            field: 'ff',
                            title: '操作',
                            width: 250,
                            formatter: function (value, row, index) {
                                var html='';
                                if(row.column_status==0){
                                    return '<a href="javascript:void(0);" class="btn btn-success btn-xs" onClick="condition('+row.cid+','+row.column_status+')" ><i class="glyphicon glyphicon-export"></i>上线</a>';
                                }else if(row.column_status==1){
                                    return '<a href="javascript:void(0);" class="btn btn-danger btn-xs" onClick="condition('+row.cid+','+row.column_status+')"><i class="glyphicon glyphicon-import">下线</i></a>&nbsp;&nbsp;'+'<a href="javascript:void(0);" class="btn btn-primary btn-xs" onClick="editor('+row.cid+')"><i class="glyphicon glyphicon-edit"></i>编辑</a>';
                                }
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
             $('#column_name').val('');
             $('#sort').val('');
             $('#tid option:selected').removeAttr('selected');
             $('#tid option:first').prop('selected','selected');
             $('#tid').removeAttr('disabled');
             $('#column_status option:selected').removeAttr('selected');
             $('#column_status option:selected').prop('selected','selected');
             $('#myModal').modal('show');
             url='{{url('admin/category/savechannel')}}?_token='+token;
         }
        function condition(id,val){
         $.ajax({
             url:'{{url('admin/category/condition')}}?_token='+token,
             data:{'cid':id,'column_status':val},
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
                title:$('#columnname').val(),
                start:$('#start').val(),
                end: $('#end').val(),
                status:$('#status').val(),
                state:$('#onoff').val(),
                limit: params.limit, // 每页显示数量
                offset: params.offset // SQL语句偏移量
            }
        }
        $('#submitOn').click(function(){
            var column_name = $('#column_name').val();
            var tid = $('#tid').val();
            var column_status = $('#column_status').val();
            var sort = $('#sort').val();
            if(column_name==''||tid==''||column_status==''||sort==''){
                layer.msg('请填写完整的数据',{icon:1,time:1000});
                return;
            }
            $.ajax({
                url:url,
                data:{'column_name':column_name,'tid':tid,'column_status':column_status,'sort':sort},
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
        });
        function editor(id){
            $.ajax({
                url:'{{url('admin/category/editor')}}?_token='+token,
                data:{'cid':id},
                type:'post',
                success:function(data){
                 var tid = data.tid;
                 var column_name = data.column_name;
                 var column_status = data.column_status;
                 var sort = data.sort;
                 $('#column_name').val(column_name);
                 $('#sort').val(sort);
                 $("#tid option[value='"+tid+"']").prop('selected','selected');
                 $("#column_status option[value='"+column_status+"']").prop('selected','selected');
                 $('#tid').attr('disabled',true);
                 $('#myModal').modal('show');
                 url='{{url('admin/category/updateData')}}?cid='+id+'&_token='+token;
                }
            })
        }
    </script>
@endsection
