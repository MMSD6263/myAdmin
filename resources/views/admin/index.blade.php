@extends('admin.master')
@section('content')
    <style>
        .nav-second-level li:last-child {
        boder-bottom:none;
        margin-bottom:0px;

        }
</style>
    <div id="wrapper">
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i>
            </div>
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">

                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <span><img alt="image" class="img-circle" style="width:64px;height:64px;"
                                       src="{{url('src/admin/img/small_admin_logo.jpg')}}"/></span>
                                        {{--src="https://ss0.bdstatic.com/94oJfD_bAAcT8t7mm9GUKT-xh_/timg?image&quality=100&size=b4000_4000&sec=1492075867&di=d6508cd51eee954393d8f66d5bafeeeb&src=http://pic4.nipic.com/20091013/1693084_150359067704_2.jpg"/></span>--}}
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="block m-t-xs"><strong
                                            class="font-bold">{{Session::get('admin.username')}}</strong></span>
                                <span class="text-muted text-xs block">{{Session::get('admin.realname')}}<b
                                            class="caret"></b></span>
                            </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="/admin/admin/userInfo?id={{Session::get('admin.id')}}">个人资料</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="{{url('admin/logout')}}">安全退出</a>
                                </li>
                            </ul>
                        </div>
                        <div class="logo-element">TO
                        </div>
                    </li>
                    @foreach ($powers as $node)
                        <li>
                            <a href="{{url($node['powers'][0]['name'])}}">
                                <i class="fa fa {{$node['powers'][0]['icon']}}"></i>
                                <span class="nav-label">{{$node['powers'][0]['title']}}</span>
                                @if($node['powers'][0]['title']=='审核认证')
                                    <span class="label label-danger pull-right">{{$count['sum']}}</span>
                                @endif
                            </a>
                            @foreach($node['childrens'] as $childrens)
                                <ul class="nav nav-second-level" style="border:1px dashed  #2e5a52">
                                    <li>

                                            <a class="J_menuItem"
                                               href="{{url($childrens['name'])}}">{{$childrens['title']}}
                                            </a>
                                            @if(!empty($childrens['children']))
                                                @foreach($childrens['children'] as $ch)
                                                    <ul class="nav nav-second-level" style="border:1px dashed  #2e5a52">
                                                        <li style="margin-left:15px;">
                                                            <a class="J_menuItem" href="{{url($ch['name'])}}" style="color: #48A7CE;">
                                                                {{$ch['title']}}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endforeach
                                            @endif

                                    </li>
                                </ul>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i
                                    class="fa fa-bars"></i> </a>
                        <form role="search" class="navbar-form-custom" method="post" action="search_results.html">
                            <div class="form-group">
                                {{--<input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">--}}
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown">
                        </li>
                        <li class="dropdown">
                        </li>
                        <li class="dropdown hidden-xs">
                            <a class="right-sidebar-toggle" aria-expanded="false">
                                <i class="fa fa-tasks"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row content-tabs">
                <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
                </button>
                <nav class="page-tabs J_menuTabs">
                    <div class="page-tabs-content">
                        <a href="javascript:;" class="active J_menuTab" data-id="index_v1.html">首页</a>
                    </div>
                </nav>
                <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
                </button>
                <div class="btn-group roll-nav roll-right">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                    </button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li class="J_tabShowActive"><a>定位当前选项卡</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                        </li>
                        <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                        </li>
                    </ul>
                </div>
                <a href="{{url('admin/logout')}}" class="roll-nav roll-right J_tabExit"><i
                            class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="row J_mainContent" id="content-main">
                @if(session('admin.rid')==17||session('admin.rid')==18)
                    <iframe class="J_iframe" name="iframe0" width="100%" height="100%"
                            src="{{url('admin/artificial/index')}}" frameborder="0" data-id="index_v1.html"
                            seamless></iframe>
                @else
                    <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="index_v1" frameborder="0"
                            data-id="index_v1.html" seamless></iframe>
                @endif
            </div>
            {{--<div class="footer">--}}
            {{--<div class="pull-right">&copy; 2014-2015 <a href="#" target="_blank">zihan's--}}
            {{--blog</a>--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
        <!--右侧部分结束-->
    {{--————————————————————————————————————————————————————————————————--}}
    <!--右侧边栏开始-->
        <div id="right-sidebar">
            <div class="sidebar-container">
                <ul class="nav nav-tabs navs-3">
                    <li class="active">
                        <a data-toggle="tab" href="#tab-3">
                            <i class="fa fa-gear"></i>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-3" class="tab-pane active">
                        <div class="sidebar-title">
                            <h3><i class="fa fa-gears"></i> 设置</h3>
                        </div>
                        <div class="setings-item">
                            {{--@foreach($system as $value)--}}
                            <span>
                            上传CDN
                        </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox"
                                           id="example">
                                    <label class="onoffswitch-label" for="example">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            {{--@endforeach--}}
                            <span>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
