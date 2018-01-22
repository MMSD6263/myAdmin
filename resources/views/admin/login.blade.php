<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">

    <title>facebook 后台管理</title>
    <link href="{{url('/src/admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('/src/admin/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{url('/src/admin/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{url('/src/admin/css/style.min.css')}}" rel="stylesheet">
    <link href="{{url('/src/admin/css/login.min.css')}}" rel="stylesheet">
    <script>if (window.top !== window.self) {
            window.top.location = window.location;
        }</script>
</head>
<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">
                <div class="logopanel m-b">
                    <h1><span>FACEBOOK 后台管理系统</span></h1>
                </div>
                <div style="height:220px;width:400px;border: 0px solid red;">Facebook background management system</div>
                {{--<div class="m-b">HTTP://IWEIMEI.zCOM </div>--}}

                {{--<strong>还没有账号？ <a href="#">立即注册&raquo;</a></strong>--}}
            </div>
        </div>
        <div class="col-sm-5">
            <form method="post" action="{{url('admin/login')}}">
                {{--{{csrf_token()}}--}}
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <h4 class="no-margins"><h2>登录</h2></h4>
                <p class="m-t-md"></p>
                <input type="text" name="username" class="form-control uname" placeholder="用户名"/>
                <input type="password" name="passwd" class="form-control pword m-b" placeholder="密码"/>
                <a href="">Forgot password？</a>
                <button class="btn btn-success btn-block">登录</button>
            </form>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            &copy; 2016-2022 All Rights Reserved. Facebook
        </div>
    </div>
</div>
</body>
</html>