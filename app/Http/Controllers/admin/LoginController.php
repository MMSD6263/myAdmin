<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Node;
use App\Models\Admin;

class LoginController extends Controller
{
    /*
     * 登录界面
     */
    public function index()
    {
        return view('admin.login');
    }

    /*
     * 提交验证
     */
    public function login(Request $request)
    {
        $result = $request->all();
        if ($result) {
            $str = $this->verifyPasswd($result['passwd']);
//            dd($str);
            $admin = new Admin();
            $admin = $admin->where('username', $result['username']);
            $admin = $admin->where('password', $str);
            $res = $admin->first();
            if (!empty($res)) {
                $key['admin'] = $res;
                $key['admin']['logintime'] = time();
                $Role = new Role();
                $power = $Role->where('id', $res['rid'])->first(['powers'])->toarray();
                $key['admin']['powers'] = $power['powers'];
                $Node = new Node();
                $Node = $Node->where('pid', '>', '0');
                $list = $Node->first(['name'])->toarray();

                $key['admin']['nodes'] = $list;
                $data = [
                    'last_time' => time(),
                    'last_ip' => $_SERVER['REMOTE_ADDR'],
                ];
                $admin->where(array('id' => $res['id']))->update($data);
                $request->session()->put('admin', $key['admin']);
                $adminFlag = $request->session()->get('admin');

                if (!empty($adminFlag['id'])) {
                    return redirect('admin/index');
                } else {
                    return redirect('admin/login');
                }
            } else {
                return view('admin.login');
            }

        } else {
            return view('admin.login');
        }
    }

    public function verifyPasswd($data)
    {
        $verify = 'to';
        $passwd = substr(md5(md5($data) . $verify), -20);
        return $passwd;
    }

    /*
     * 退出
     */
    public function logout(Request $request)
    {
        $admin = $request->session()->get('admin');
        if (isset($admin['id'])) {
            $request->session()->put('admin', '');
            return redirect('admin/login');
        } else {
            return redirect('admin/login');
        }
    }

}
