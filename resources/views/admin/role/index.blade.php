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
<script>
    var url;
    function newUser() {
        $('#w').window('open').window('setTitle', '添加角色');
        nodes = $('#tt').tree('getRoots');
        for (var i = 0; i < nodes.length; i++) {
            rootid = nodes[i].id;
            nodeb = $('#tt').tree('find', rootid);
            $('#tt').tree('check', nodeb.target);

        }
        $('#fm').form('clear');
        var token = '{{csrf_token()}}';
        url = "/admin/role/roleAdd?_token=" + token;
    }

    function in_array(search, array) {
        for (var i in array) {
            if (array[i] == search) {
                return true;
            }
        }
        return false;
    }


    function editUser() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $('#w').dialog('open').dialog('setTitle', '修改角色权限');
            $.get('/admin/role/edit', {id: row.id}, function (data) {
                var obj = eval('(' + data + ')');
                rpower = obj.power.split(',');
                $('#group').combobox('setValue', obj.gid)
                //获取所有根节点把根节点的的选中状态定义为非选择中。
                nodes = $('#tt').tree('getRoots');
                for (var i = 0; i < nodes.length; i++) {
                    rootid = nodes[i].id;
                    nodeb = $('#tt').tree('find', rootid);
                    $('#tt').tree('uncheck', nodeb.target);
                }
                //把所有的有权限记录的子节点选中。
                for (var i in rpower) {
                    id = rpower[i];
                    if (id) {
                        nodeb = $('#tt').tree('find', id);
                        $('#tt').tree('check', nodeb.target);
                    }
                }
            });

            $('#fm').form('load', row);
        }
    }


    function saveUser() {
        $('#fm').form('submit', {
            url: url,
            onSubmit: function () {
                return $(this).form('validate');
            },
            success: function (result) {
                var result = eval('(' + result + ')');
                if (result.success) {
                    $('#dlg').dialog('close');		// close the dialog
                    $('#dg').datagrid('reload');	// reload the user data
                } else {
                    $.messager.show({
                        title: 'Error',
                        msg: result.msg
                    });
                }
            }
        });
    }
    function removeUser() {
        var row = $('#dg').datagrid('getSelected');
        var token= '{{csrf_token()}}';
        if (row) {
            $.messager.confirm('Confirm', '您确实要删除么？', function (r) {
                if (r) {
                    $.post('/admin/role/remove', {id: row.id,'_token':token}, function (result) {
                        if (result.success) {
                            $('#dg').datagrid('reload');	// reload the user data
                        } else {
                            $.messager.show({	// show error message
                                title: 'Error',
                                msg: result.msg
                            });
                        }
                    }, 'json');
                }
            });
        }
    }
</script>
@section('content')
    <br/>
    {{--<a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="newNode()">添加</a>--}}
    {{--<a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="editNode()">修改</a>--}}
    {{--<a href="javascript:void(0)" class="btn btn-primary btn-xs" onClick="removeNode()">删除</a>--}}

    {{--<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onClick="newNode()">添加新结点</a>--}}
    {{--<a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onClick="editNode()">编辑结点</a>--}}
    {{--<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onClick="removeNode()">移除结点</a>--}}
    {{--<br/>--}}
    {{--<br/>--}}
            <!--列表详情-->

    <table id="dg" class="easyui-datagrid" style="width:90%px;height:80%px"
           toolbar="#toolbar"
           fitColumns="true" singleSelect="true"
           data-options="
				url: '/admin/role/ajaxdata',
				rownumbers: true,
			   queryParams:{'_token':'{{csrf_token()}}'},
				fit:true,
				pagination: true,
				pageNumber:1,
				pageSize:20,
				pageList: [20,30],
				idField: 'id',
				onBeforeLoad: function(row,param){
					if (!row) {	// load top level rows
						param.id = 0;	// set id=0, indicate to load new page rows
					}
				}
			"
    >
        <thead>
        <tr>
            <th field="name"> 角色名</th>
            <th field="description">描述</th>
            <th field="powername" width="400">角色权限</th>
            <th field="ctime">创建时间</th>
        </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onClick="newUser()">添加角色</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onClick="editUser()">修改角色</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onClick="removeUser()">删除</a>
    </div>

    <div id="w" class="easyui-window" data-options="closed:true,iconCls:'icon-save'"
         style="width:500px;height:300px;padding:5px;">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'east',split:true" style="width:200px">
                <ul id="tt" class="easyui-tree"
                    url="/admin/powers/powertree?_token={{csrf_token()}}"
                    animate="true"
                    checkbox="true"
                >
                </ul>
            </div>
            <div data-options="region:'center'" style="padding:10px;">
                <form id="fm" method="post">
                    {{--{{csrf_token()}}--}}
                    <div class="fitem">
                        <label>角色名称:</label>
                        <input name="name" class="easyui-validatebox" required="true">
                    </div>
                    <input type="hidden" id="power" name="power" value=""/>
                    <input type="hidden" id="powername" name="powername" value=""/>
                    <input type="hidden" id="token" name="_token" value="{{csrf_token()}}"/>
                    <input type="hidden" id="id" name="id" value=""/>
                    <div class="fitem">
                        <label>描&nbsp;&nbsp述:</label>
                        <textarea name="description" class="easyui-validatebox" required="true"></textarea>
                    </div>
                </form>
            </div>
            <div data-options="region:'south',border:true" style="text-align:right;padding:5px;">
                <div id="dlg-buttons">
                    <a href="#" class="easyui-linkbutton" iconCls="icon-add" onClick="submitAdd(1)">添加</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onClick="submitForm()">修改</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onClick="clearForm()">取消</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="/src/easyui/js/jquery.easyui.min.js"></script>
    <script src="/src/easyui/js/DatePicker/WdatePicker.js"></script>

    <script type="text/javascript">


        //修改

        function submitForm() {
            //取所有被选择的节点及其父节点。
            var url = '/admin/role/subedit';
            nodes = $('#tt').tree('getChecked');
            var power = '', powername = '';
            for (var i = 0; i < nodes.length; i++) {
                if (power != '') power += ',';
                power += nodes[i].id;
                if (powername != '') powername += ',';
                powername += nodes[i].text;
            }
            //$('#groupid').val($('#group').combobox('getValue'));   //给隐藏的表单赋值。
            $('#power').val(power);
            $('#powername').val(powername);
            $('#fm').form('submit', {
                url: url,
                onSubmit: function () {
                    return $(this).form('validate');
                },
                success: function (result) {
                    var result = eval('(' + result + ')');
                    if (result.success) {
                        $('#dlg').dialog('close');		// close the dialog
                        $('#dg').datagrid('reload');	// reload the user data
                        clearForm();
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: result.message
                        });
                    }
                }
            });
        }


        function submitAdd(type) {
            //取所有被选择的节点及其父节点。
            var token = '{{csrf_token()}}';
            $("#token").val(token);
            var url = '/admin/role/roleAdd';
            nodes = $('#tt').tree('getChecked');
            var power = '', powername = '';
            for (var i = 0; i < nodes.length; i++) {
                if (power != '') power += ',';
                power += nodes[i].id;
                if (powername != '') powername += ',';
                powername += nodes[i].text;
            }

            console.log(power);
            console.log(powername);

            $('#power').val(power);
            $('#powername').val(powername);
            $('#fm').form('submit', {
                url: url,
                onSubmit: function () {
                    return $(this).form('validate');
                },
                success: function (result) {
                    var result = eval('(' + result + ')');
                    if (result.success) {
                        $('#dlg').dialog('close');		// close the dialog
                        $('#dg').datagrid('reload');	// reload the user data
                        clearForm();
                    } else {
                        $.messager.show({
                            title: 'Error',
                            msg: result.message
                        });
                    }
                }
            });
        }
        function clearForm() {
            $('#fm').form('clear');
            $('#w').window('close');
        }
    </script>
@endsection
