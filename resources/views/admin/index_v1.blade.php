@extends('admin.master')
@section('content')
    <div class="wrapper wrapper-content">

        {{--搜索框--}}
        <div class="row" style="margin-bottom:5px;">
            <div class="col-xs-2">
                <input type="text" placeholder="开始日期" style="height: 25px;" onfocus="WdatePicker()"
                       class=" form-control Wdate search" id="start" value="">
            </div>
            <div class="col-xs-2">
                <input type="text" placeholder="结束日期" style="height: 25px;" onfocus="WdatePicker()"
                       class=" form-control Wdate search" id="end">
            </div>

            <div class="col-xs-1">
                <a class='btn  btn-sm btn-primary  btn-rounded search_data' href='#' onClick="doSearch()">查 询</a>
            </div>
        </div>

        {{--echarts图--}}
        <div class="row">
            <div class="col-sm-12">
                <div id="main" style="height:350px;border:1px solid #fff;padding:10px;">
                </div>
            </div>
        </div>

        {{--数据表--}}
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                    <a class="close-link">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <table  id="tb_departments">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

@section('js')
    <script src="{{asset('src/easyui/echarsjs/esl.js')}}"></script>
    <script src="{{url('/src/easyui/js/DatePicker/WdatePicker.js')}}"></script>
    <script src="{{url('/src/bootstrap-table/src/bootstrap-table.js')}}" type="text/javascript"></script>
    <script src="{{url('/src/bootstrap-table/src/locale/bootstrap-table-zh-CN.js')}}"></script>
    <script>
        var opt;
        $(document).ready(function() {
            opt = {!! $operate !!};
            showEcharts(opt);
            var oTable = new TableInit();
            oTable.Init();
//            var oButtonInit = new ButtonInit();
//            oButtonInit.Init();
        })

        function showEcharts(options){
            var options = options;
            require.config({
                paths: {
                    echarts: '/src/easyui/echarsjs/echarts',
                    'echarts/chart/bar': '/src/easyui/echarsjs/echarts',
                    'echarts/chart/line': '/src/easyui/echarsjs/echarts',
                }
            });

            require([
                    'echarts',
                    'echarts/chart/line',
                    'echarts/chart/bar'
                ],
                function (ex) {
                    var myChart = ex.init(document.getElementById('main'));
                    myChart.showLoading({
                        text: '数据读取中...'
                    });
                    var option = options;
                    myChart.setOption(option);
                    myChart.hideLoading();
                }
            );
        }


        var TableInit = function () {
            var oTableInit = new Object();
            oTableInit.Init = function () {
                $('#tb_departments').bootstrapTable({
                    url: '{{url('admin/tableData')}}',         //请求后台的URL（*）
                    method: 'get',                      //请求方式（*）
                    toolbar: '#toolbar',                //工具按钮用哪个容器
                    striped: true,                      //是否显示行间隔色
                    cache: true,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
                    queryParams: queryParams,           //传递参数（*）
                    uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                    classes: 'table table-bordered table-responsive table-hover', // Class样式
                    columns: [
                        {
                            field: 'uid',
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
                            field:'clicks',
                            title:'点击量',
                            align:'center'
                        },
                        {
                            field:'views',
                            title:'展示量',
                            align:'center'
                        },
                        {
                            field:'share',
                            title:'分享',
                            align:'center'
                        },
                        {
                            field:'inter_access',
                            title:'内部访问',
                            align:'center'
                        },
                        {
                            field:'read_article',
                            title:'阅读原文',
                            align:'center'
                        },
                        {
                            field:'histexternal_accessry',
                            title:'外部访问',
                            align:'center'
                        },
                        {
                            field:'totalStatistics',
                            title:'总量',
                            align:'center'
                        },
                        {
                            field:'click_rate',
                            title:'点击率',
                            align:'center'
                        },
                        {
                            field:'share_rate',
                            title:'分享率',
                            align:'center'
                        },
                        {
                            field:'guide_rate',
                            title:'导流率',
                            align:'center'
                        }
                    ]
                });
            };
            return oTableInit;
        };



        function doSearch()
        {
            var start = $('#start').val();
            var end = $('#end').val();
            $.ajax({
                'url':'{{url("admin/ajaxData")}}?_token={{csrf_token()}}',
                'type':'post',
                'data':{'start':start,'end':end},
                'dataType':'json',
                success:function(res){
                    console.log(res);
                    if(res){
                        showEcharts(res);
                        $('#tb_departments').bootstrapTable('refresh','{{url('admin/tableData')}}');
                    }
                }
            })

        }

        function queryParams(){
            return {
                start:$('#start').val(),
                end:$('#end').val(),
            }
        }




    </script>
@endsection
@endsection