<?php

namespace App\Http\Controllers\admin;

use App\Models\Node;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PowerController extends Controller
{
    /*
     * 功能模块首页
     */
    public function index()
    {
        return view('admin.power.index');
    }

    public function ajaxData(Request $request)
    {
        $data = Node::index($request->except(['_token']));
        echo json_encode($data);
    }

    /*
     * 添加数据
     */
    public function add(Request $request)
    {
        $result = Node::addindex($request->except(['_token']));
        return $result;
    }

    /*
     * 删除数据
     */
    public function edit(Request $request)
    {
        $result = Node::edit($request->except(['_token']));
        return json_encode($result);
    }

    /*
     * 删除数据
     */
    public function removes(Request $request)
    {
        $result = Node::removes($request->except(['_token']));
        return json_encode($result);
    }

    public function powertree()
    {
        $result = Node::powertree();
        return $result;
    }

}
