@extends('admin.master')
@section('css')
@endsection
@section('content')
    <body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>用户详细信息 <small></small></h5>
                    </div>
                    <div class="ibox-content">
                        <form method="post" action='{{url("/admin/admin/edit")}}' class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户头像</label>
                                <div class="col-sm-10">
                                   <img style="width:64px;height:64px;" src="{{$data['picpath']}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户名</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$data['username']}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">角色</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$data['name']}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-10">
                                    <input type="password" name="passwd" class="form-control" value="">
                                    <span class="help-block m-b-none">不修改密码该项不用填写</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">qq</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="qq" value="{{$data['qq']}}">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">最后一次登陆的IP</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{$data['last_ip']}}</p>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">最后一次登录的时间</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{date('Y-m-d H:i:s',$data['last_time'])}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户名创建时间</label>
                                <div class="col-sm-10">
                                    <p class="form-control-static">{{date('Y-m-d H:i:s',$data['ctime'])}}</p>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="id" value="{{$data['id']}}">
                            <div class="hr-line-dashed"></div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">保存内容</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
            @endsection
            @section('js')
@endsection
