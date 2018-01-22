@extends('admin.master')
@section('css')
    <link href="/src/easyui/css/easyui.css?v=3.2.0" rel="stylesheet">
    <style type="text/css">
        #fm {
            margin: 0;
            padding: 10px 30px;
        }

        .ftitle {
            font-size: 14px;
            font-weight: bold;
            color: #666;
            padding: 5px 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        .fitem {
            margin-bottom: 5px;
        }

        .fitem label {
            display: inline-block;
            width: 80px;
        }
    </style>
@endsection

@section('content')
    <br/>
    <a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="newNode()">添加</a>
    <a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="editNode()">修改</a>
    <a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="removeNode()">删除</a>

    {{--<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onClick="newNode()">添加新结点</a>--}}
    {{--<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onClick="editNode()">编辑结点</a>--}}
    {{--<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onClick="removeNode()">移除结点</a>--}}
    <br/>
    <br/>
    <!--列表详情-->
    <body class="easyui-layout">
    <table id="dg" class="easyui-treegrid" toolbar='#toolbar'
           data-options="
				url:'/admin/powers/ajaxData',
				rownumbers: true,
				queryParams:{'_token':'{{csrf_token()}}'},
				fit:true,
				fitColumns:true,
				pagination: true,
				pageSize:20,
				pageNumber:1,
				pageList: [20,30],
				idField: 'id',
				treeField: 'title',
				onBeforeLoad: function(row,param){
					if (!row) {	// load top level rows
						param.id = 0;	// set id=0, indicate to load new page rows
					}
				}
			">
        <thead>
        <tr>
            <th field="title" width="40%">权限名称</th>
            <th field="name" align="left" width="30%">url</th>
            <th field="ctime" align="left" width="20%">ctime</th>
        </tr>
        </thead>
    </table>
    <!--控制面板开始-->
    <div id="token1" style="display: none;">{{csrf_token()}}</div>
    <div id="dlg" class="easyui-dialog" title="编辑节点" closed="true" buttons="#dlg-buttons"
         style="width:500px;height:350px;">
        <div class="ftitle">节点信息</div>
        <form id="fm" method="post" novalidate>
            <div class="fitem">
                <label>名称:</label>
                <input name="title" class="easyui-validatebox" required="true">
                <input name="_token" id="token" type="hidden" value="{{csrf_token()}}">
            </div>
            <div class="fitem">
                <label>url地址:</label>
                <input name="name" class="easyui-validatebox" required="true">
            </div>
        </form>
    </div>
    <div id="dlg-buttons" style="margin-top:-20px;">
        <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="saveNode()">保存</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-cancel"
           onClick="javascript:$('#dlg').dialog('close')">取消</a>
    </div>
    </div>
    </body>
@endsection

@section('js')

    <script src="/src/easyui/js/jquery.easyui.min.js"></script>
    <script src="/src/easyui/js/DatePicker/WdatePicker.js"></script>

    <script type="text/javascript">
        var url;
        function newNode() {
            var row = $('#dg').datagrid('getSelected');
            $('#dlg').dialog('open').dialog('setTitle', '添加');
            $('#fm').form('clear');
            var token = $('#token1').html();
            $('#token').val(token);
            if (row) {
                url = '/admin/powers/add?pid=' + row.id+'&ppid='+row.pid;
            }else{
                url = '/admin/powers/add';
            }
        }
        function editNode() {
            var row = $('#dg').datagrid('getSelected');

            if (row) {
                $('#dlg').dialog('open').dialog('setTitle', '编辑');
                $('#fm').form('load', row);
                url = '/admin/powers/edit?id=' + row.id;
            } else {
                $.messager.show({
                    title: '出错啦！！',
                    msg: '请选择一条'
                });
            }
        }

        function saveNode(){
            $('#fm').form('submit', {
                url: url,
                onSubmit: function () {
                    return $(this).form('validate');
                },
                success: function (result) {
                    var result = eval('(' + result + ')');
                    if (result.success) {
                        $('#dlg').dialog('close');		// close the dialog
                        $('#dg').treegrid('reload');	// reload the user data
                        $.messager.show({
                            title: '消息',
                            msg: result.message
                        });
                    } else {
                        $.messager.show({
                            title: '出错啦！！',
                            msg: result.message
                        });
                    }
                }
            });
        }
        function removeNode() {
            var row = $('#dg').datagrid('getSelected');
            var token = '{{csrf_token()}}';
            if (row) {
                $.messager.confirm('Confirm', '确实要删除这条记录么？', function (r) {
                    if (r) {
                        $.post("/admin/powers/removes", {id: row.id, '_token': token}, function (result) {
                                    if (result.success) {
                                        $('#dg').treegrid('reload');
                                    } else {
                                        $.messager.show({				// show error message
                                            title: '出错啦！！',
                                            msg: result.message
                                        });
                                    }
                                }, 'json'
                        )
                        ;
                    }
                });
            }
        }
    </script>
@endsection
