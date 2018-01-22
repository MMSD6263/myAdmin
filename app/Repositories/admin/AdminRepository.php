<?php

namespace App\Repositories\admin;

use App\Models\Admin;

/*
 * 素材数据仓库
 */

class AdminRepository
{

    public static function ajaxData($request)
    {

        $page = isset($request['page']) ? intval($request['page']) : 1;
        $rows = isset($request['rows']) ? intval($request['rows']) == 0 ? 20 : intval($request['rows']) : 20;

        $Admin = new Admin();

        $offset = ($page - 1) * $rows;
        $list = $Admin->skip($offset)->take($rows)->get();
        $count = $Admin->count();

        foreach ($list as &$val) {
            $val['ctime'] = date('Y-m-d H:i:s', $val['ctime']);
            $val['actionButton'] = "操作";
        }

        $result = [
            'rows' => $list,
            'total' => $count,
        ];

        return json_encode($result);
    }

    /*
     * 添加数据
     */

    public static function adminAdd($request)
    {
        $Admin = new Admin();
//		$request['ip']  =self::getIP();
        $request['password'] = substr(md5(md5($request['passwd']) . 'to'), -20);
        $request['ctime'] = time();
        if ($Admin->insert($request)) {
            $result = self::message(true, 'success');
        } else {
            $result = self::message(true, 'success');
        }
        return $result;
    }

    /*
     * 修改数据表
     */
    public static function adminEdit($requst)
    {
        $Admin = new Admin();
        if ($requst['id']) {
            $Admin = $Admin->where('id', $requst['id']);
            $result['passoword'] = substr(md5($requst['passwd']), 0, 20);
            if ($Admin->update($requst)) {
                $result = self::message(true, 'success');
            } else {
                $result = self::message(false, 'error');
            }
        } else {
            $result = self::message(false, 'error');
        }

        return $result;
    }
    /*
     * 删除数据表
     */
    public static function adminDelete($request)
    {
        $Admin = new Admin();
        if ($request['id']) {
            $Admin = $Admin->where('id', $request['id']);
            if ($Admin->delete()) {
                $result = self::message(true, 'success');
            } else {
                $result = self::message(false, 'error');
            }
        } else {
            $result = self::message(false, 'id not null');
        }

        return $result;
    }

    public static function message($success = '', $message = '')
    {
        return json_encode(['success' => $success, 'message' => $message]);
    }

    public static function getIP()
    {
        $onlineip = '';

        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }

    public static function userInfo($id)
    {
        if (!empty($id)) {
            $admin = new Admin();
            $admin = $admin->where('admin.id', $id);
            $result = $admin->join('role', 'role.id', '=', 'admin.rid')->first(['role.name', 'admin.*'])->toarray();
            $result['success'] = true;
            $result['message'] = 'success';
        } else {
            $result = array('success' => false, 'message' => 'error');
        }
        return $result;
    }

    public static function edit($request)
    {
        if (!empty($request['id'])) {
            $admin = new Admin();
            $request['last_ip'] = '127.0.0.1';
            $request['last_time'] = time();
                $request['password'] = self::verifyPasswd($request['passwd']);
            if ($admin->where('id', $request['id'])->update($request)) {
                $result = array('success' => false, 'message' => 'error');
            }
        } else {
            $result = array('success' => false, 'message' => 'Id not null');
        }
        return $result;
    }

    public  static function verifyPasswd($data){
        $verify = 'to';
        $passwd = substr(md5(md5($data).$verify),-20);
        return $passwd;
    }


}
