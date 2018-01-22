<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'node';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function index($request)
    {

        $page = isset($request['page']) ? intval($request['page']) : 0;
        $rows = isset($request['rows']) ? intval($request['rows']) == 0 ? 20 : intval($request['rows']) : 20;

        $pid = isset($request['id']) ? $request['id'] : 0;
        $data = Node::where(['pid' => $pid])->get()->toarray();
        $count = Node::where(['pid' => $pid])->count();

        foreach ($data as &$val) {
            $val['state'] = self::has_child($val['id']) ? 'closed' : 'open';
            $val['ctime'] = date('Y-m-d H:i', $val['ctime']);
        }
        if (empty($request['id'])) {
            $result = array('rows' => $data, 'total' => $count);
        } else {
            $result = $data;
        }
        return $result;
    }


    public static function has_child($id)
    {
        $res = Node::where(['pid' => $id])->count();
        return $res > 0 ? true : false;
    }

    public static function addindex($data)
    {

        $data['ctime'] = time();
        if (!empty($data['pid'])) {
            if ($data['pid']) {
                $data['path'] = $data['ppid'] . '_' . $data['pid'];
            } else {
                $data['path'] = '0_' . $data['pid'];
            }
        }
        unset($data['ppid']);
        if (Node::insert($data)) {
            $result = self::message(true, '200', 'add the success');
        } else {
            $result = self::message(false, '201', 'add the error');
        }

        return $result;
    }

    public static function edit($data)
    {

        $update = Node::where(['id' => $data['id']])->update($data);
        if ($update) {
            $result = self::message(true, '200', 'edit the success');
        } else {
            $result = self::message(false, '201', 'edit the error');
        }

        return $result;
    }

    public static function removes($data)
    {

        if (!empty($data['id'])) {
            if (Node::where(['id' => $data['id']])->delete()) {
                $result = self::message(true, '200', 'delete the success');
            } else {
                $result = self::message(true, '203', 'delete the success');
            }
        } else {
            $result = self::message(true, '204', 'ID NOT NULL');
        }

        return $result;
    }


    private static function get_array($id = 0)
    {
        $result = Node::where(['pid' => $id])->get(['id', 'title as text', 'pid']);
        $arr = array();
        if ($result) {//如果有子类
            foreach ($result as $rows) { //循环记录集
                $rows['children'] = self::get_array($rows['id']);//调用函数，传入参数，继续查询下级
                $rows['state'] = empty($rows['children']) ? 'open' : 'closed';
                $rows["checked"] = true;
                $arr[] = $rows; //组合数组
            }
            return $arr;
        }
    }

    public static function powertree()
    {
        $list = self::get_array(0);
        $str = json_encode($list);
        return $str;
    }



    public static function message($succes = '', $code = '', $message = '')
    {
        return array('success' => $succes, 'code' => $code, 'message' => $message);
    }
}