<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>FaceBook后台管理系统</title>
    <meta name="keywords" content="小程序 后台管理系统">
    <meta name="description" content="后台管理系统">
    @yield('meta')

    <link href="{{url('/src/admin/css/bootstrap.min.css')}}?v=3.4.0" rel="stylesheet">
    <link href="{{url('/src/admin/css/font-awesome.min.css')}}?v=4.3.0" rel="stylesheet">
    <link href="{{url('/src/admin/css/style.min.css?v=3.2.0')}}" rel="stylesheet">
    {{--    <link href="{{url('/src/admin/css/animate.min.css')}}" rel="stylesheet">--}}

    @yield('css')

</head>
<body class="fixed-sidebar full-height-layout gray-bg">
@yield('content')
<!-- 全局js -->
<script src="{{url('/src/admin/js/jquery.min.js')}}"></script>
<script src="{{url('/src/admin/js/bootstrap.min.js')}}?v=3.4.0"></script>
<script src="{{url('/src/admin/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
<script src="{{url('/src/admin/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{url('/src/admin/js/plugins/layer/layer.min.js')}}"></script>
<!-- 自定义js -->

<script src="{{url('/src/admin/js/hplus.min.js')}}?v=3.2.0"></script>
<script src="{{url('/src/admin/js/contabs.min.js')}}"  type="text/javascript" ></script>

<!-- 第三方插件 -->
{{--<script src="{{url('/src/admin/js/plugins/pace/pace.min.js')}}"></script>--}}
@yield('js')
</body>
</html>