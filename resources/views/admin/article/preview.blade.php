<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no, email=no">
    <link rel="stylesheet" href="{{url('api/css/essay.css')}}">
    <title></title>
    <style>
        html {
            overflow-x: hidden;
        }
        body {
            background: #F8F7F5;
        }
    </style>
</head>
<body>
<div class="page-cont">
    {{--网页内容开始--}}
    <div class="article">
        {!!$result['content']!!}
    </div>

</div>
<script type="text/javascript" src="{{url('api/js/jquery.min.js')}}"></script>
<script src="{{url('/src/admin/js/plugins/layer/layer.min.js')}}"></script>
<script type="text/javascript">
</script>
{{--视频播放代码--}}
</body>
</html>
