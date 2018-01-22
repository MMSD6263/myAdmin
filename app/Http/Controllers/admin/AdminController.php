<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Repositories\admin\AdminRepository;
use App\Models\Role;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    public function index()
    {

        //获取角色ID
        $role     = new Role();
        $rolerows = $role->get()->toarray();
        return view('admin.admin.index',compact(['rolerows']));
    }

    public function ajaxData(Request $request)
    {
        $data = AdminRepository::ajaxData($request->except(['_token']));
        return $data;
    }

    public function adminAdd(Request $request)
    {
        $data = AdminRepository::adminAdd($request->except(['_token']));
        return $data;
    }

    public function adminEdit(Request $request)
    {
        $data   = AdminRepository::adminEdit($request->except(['_token']));
        return $data;
    }

    public function adminDelete(Request $request)
    {
        $data   = AdminRepository::adminDelete($request->except(['_token']));
        return $data;
    }

    public function userInfo()
    {
        $id     = $_GET['id'];
        $data = AdminRepository::userInfo($id);
        return view('admin.admin.userInfo',compact(['data']));
    }

    public function edit(Request $request)
    {
        AdminRepository::edit($request->except(['_token']));
        return redirect('admin/index');
    }
}
