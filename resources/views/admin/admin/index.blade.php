@extends('admin.master')
@section('css')

    <link href="/src/easyui/css/easyui.css?v=3.2.0" rel="stylesheet">

@endsection

@section('content')
    <table id="dg" class="easyui-datagrid" toolbar='#toolbar'
           data-options="
               url:'{{url("admin/admin/ajaxData")}}',
                rownumbers: true,
                queryParams:{'_token':'{{csrf_token()}}'},
                fit:true,
                singleSelect:true,
                pagination: true,
                pageNumber:1,
                pageSize:20,
                nowrap:false,//列多时  自动折到第二行
                striped:true,//行背景交换
                pageList: [10,20,30,50,100],
                showFooter:true,
                idField: 'id',
               ">
        <thead data-options="frozen:true">
        <tr>
            <th field="username" width="175" halign="center">用户名</th>
            <th field="ctime" width="200" halign="center">创建时间</th>
        </tr>
        </thead>
        <thead>
        <tr>
            <th field="passwd" width="100" align="center" >密码</th>
            <th field="qq" width="100" align="center">qq</th>
            <th field="last_ip" width="100" align="center">IP</th>
            <th field="status" width="100" align="center" formatter="status">状态</th>
        </tr>
        </thead>
    </table>

    <div id="toolbar" style="margin-top: 5px;">
        <table>
            <tr>
                <td>
                    <span>&nbsp;&nbsp;&nbsp;</span>
                    <a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="newadd()">添加</a>
                </td>
                <td>
                    <span>&nbsp;&nbsp;&nbsp;</span>
                    <a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="editUser()">修改</a>
                </td>
                <td>
                    <span>&nbsp;&nbsp;&nbsp;</span>
                    <a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="removeUser()">删除</a>
                </td>
            </tr>
        </table>
    </div>

    {{--控制面板--}}
            <!--控制面板开始-->
    <div id="dlg" class="easyui-dialog" style="width:500px;height:400px;padding:10px 20px"
         closed="true" buttons="#dlg-buttons">
        <form class="form-horizontal m-t" id="fm" method="post" novalidate>
            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>用户管理</h5>
                        </div>
                        <div class="ibox-content">
                            <form class="form-horizontal m-t" id="commentForm">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">角色：</label>
                                    <div class="col-sm-8">
                                        <select  data-options="panelHeight:'auto',editable:false" class="easyui-combobox" name="rid">
                                            @foreach($rolerows as $vo)
                                                <option value="{{$vo['id']}}">{{$vo['name']}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">用户名：</label>
                                    <div class="col-sm-8">
                                        <input name="username" class="easyui-validatebox"
                                               required="true">
                                        <input name="_token" id='verify' type="hidden"
                                               value="{{csrf_token()}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">密码:</label>
                                    <div class="col-sm-8">
                                        <input name="passwd" class="easyui-validatebox"
                                                required="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">qq:</label>
                                    <div class="col-sm-8">
                                        <input name="qq" class="easyui-validatebox"
                                               required="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">状态：</label>
                                    <div class="col-sm-8">
                                        <select name="status" id="type">
                                            <option value="1">正常</option>
                                            <option value="0">关闭</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="dlg-buttons" style="margin-top:-20px;">
                            <a href="#" class="easyui-linkbutton" iconCls="icon-ok"
                               onClick="saveUser()">保存</a>
                            <a href="#" class="easyui-linkbutton" iconCls="icon-cancel"
                               onClick="javascript:$('#dlg').dialog('close')">取消</a>
                        </div>
                    </div>
                </div>
            </div>

            @endsection

            @section('js')

                <script src="/src/easyui/js/jquery.easyui.min.js"></script>
                <script src="/src/easyui/js/DatePicker/WdatePicker.js"></script>

                <script type="text/javascript">

                    function newadd() {
                        $('#dlg').dialog('open').dialog('setTitle', '添加规则');
                        $('#fm').form('clear');
                        var token = '{{csrf_token()}}';
                        $("#verify").val(token);
                        url = "{{url('admin/admin/adminAdd')}}";
                    }

                    function editUser() {
                        var row = $('#dg').datagrid('getSelected');
                        if (row) {
                            $('#dlg').dialog('open').dialog('setTitle', '修改');
                            $('#fm').form('load', row);
                            $('#role').combobox('setValue', row.rid);
                            url = "{{url('admin/admin/adminEdit')}}?id=" + row.id + '&_token=' + '{{csrf_token()}}'
                        }
                    }
                    function saveUser() {
                        $('#fm').form('submit', {
                            url: url,
                            onSubmit: function () {
                                return $(this).form('validate');
                            },
                            success: function (result) {

                                console.log(result);
                                var result = eval('(' + result + ')');
                                if (result.success) {
                                    $('#dlg').dialog('close');		// close the dialog
                                    $.messager.show({
                                        title: 'success',
                                        msg: result.message
                                    });
                                    $('#dg').datagrid('reload');	// reload the user data
                                } else {
                                    $.messager.show({
                                        title: 'Error',
                                        msg: result.message
                                    });
                                }
                            }
                        });
                    }
                    function removeUser() {
                        var row = $('#dg').datagrid('getSelected');
                        if (row) {
                            $.messager.confirm('删除', '确定删除？', function (r) {
                                if (r) {
                                    $.post("{{url('admin/admin/adminDelete')}}", {
                                        id: row.id,
                                        '_token': '{{csrf_token()}}'
                                    }, function (result) {
                                        if (result.success) {
//
                                            $('#dg').datagrid('reload');	// reload the user data
                                        } else {
                                            $.messager.show({// show error message
                                                title: 'Error',
                                                msg: result.message
                                            });
                                        }
                                    }, 'json');
                                }
                            });
                        }
                    }

                    function status(data, row) {
                        if (row.status == '0') {
                            return '<span class="label label-warning">关闭</span>';
                        } else if (row.status == '1') {
                            return '<span class="label label-info">正常</span>';
                        }
                    }

                </script>
@endsection
