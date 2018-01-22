<html>
<head>
    <title>Laravel</title>
    @include('vendor.ueditor.assets')
    {{--<script src="//cdn.bootcss.com/vue/1.0.24/vue.min.js"></script>--}}
</head>
<body>

<form method="post" action="/test">
    <div id='content'>
        <script id="container" name="content" type="text/plain">asdfasdfadsf</script>

    </div>
    <input type="submit" value="提交">
</form>
</body>
<!-- 实例化编辑器 -->

<script type="text/javascript">
    var ue = UE.getEditor('container');
    ue.ready(function () {
        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
    });
</script>

<!-- 编辑器容器 -->

</html>
