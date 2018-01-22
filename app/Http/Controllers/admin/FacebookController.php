<?php

namespace App\Http\Controllers\admin;

use App\Repositories\admin\FacebookRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Libs\TransClass;

class FacebookController extends Controller
{
    private $_facebook;

    public function __construct()
    {
        $this->_facebook = new FacebookRepository();
    }

    public function index()
    {
        $pack = $this->_facebook->Sort();
        return view('admin/facebook/index',compact(['pack']));
    }
    /*
     * facebook用户数据获取
     * */
    public function ajaxData(Request $request)
    {
        $data = $request->except(['_token']);
        $result = $this->_facebook->AjaxData($data);
        return $result;
    }
    public function UpdateData(Request $request)
    {
        $data = $request->except(['_token']);
        $result = $this->_facebook->UpdateData($data);
        return $result;
    }
    public function SaveData(Request $request)
    {
        $data = $request->except(['_token']);
        $result = $this->_facebook->SaveData($data);
        return $result;
    }
    public function Gain(Request $request)
    {
        $data = $request->except(['_token']);
        $result = $this->_facebook->Gain($data);
        return $result;
    }

    public function userAjaxData(Request $request)
    {
        $data = $request->except(['_token']);
        $result = $this->_facebook->UserAjaxData($data);
        return $result;
    }

    public function InsertCoil(Request $request)
    {
        $data = $request->except(['_token']);
        $result = $this->_facebook->InsertCoil($data);
        return $result;
    }

    public function TopThread(Request $request)
    {
        $data = $request->except(['_token']);
        $result = $this->_facebook->TopThread($data);
        return $result;
    }

//    public function DeleteData(Request $request)
//    {
//        $data = $request->except(['_token']);
//        $result = $this->_facebook->DeleteData($data);
//        return $result;
//    }

}
