<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\Role;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function index()
    {
        return view('admin.role.index');
    }


    public function ajaxdata(Request $request)
    {
        $data = Role::index($request->except(['_token']));
        return json_encode($data);
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

    /*
     * 添加数据
     */
    public function subadd()
    {

    }

    /*
     * role 数据修改
     */
    public function subedit(Request $request)
    {
        $result = Role::subUpdate($request->except(['_token']));
        return $result;
    }

    /**
     *添加权限数据
     */
    public function roleAdd(Request $request)
    {
        $result = Role::roleAdd($request->except(['_token']));
        return $result;
    }

    /**
     * 删除角色
     */
    public function remove(Request $request)
    {
        $result = Role::remove($request->except(['_token']));
        echo $result;
    }

}
