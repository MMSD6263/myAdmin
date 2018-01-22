<?php

namespace App\Models;

use App\Models\Node;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * @param $data
     * @return array
     */
    public static function index($data)
    {

        $page = isset($request['page']) ? intval($request['page']) : 0;
        $rows = isset($request['rows']) ? intval($request['rows']) == 0 ? 20 : intval($request['rows']) : 20;

        $data = Role::get()->toarray();
        $count = Role::count();

        foreach ($data as &$val) {
            $val['ctime'] = date('Y-m-d H:i:s', $val['ctime']);
        }

        $result = array('rows' => $data, 'total' => $count);
        return $result;
    }

    /**
     * @param $request
     */
    public static function subUpdate($request)
    {

        $data['description'] =
        $data['name'] = trim($request['name']);
        $data['power'] = trim($request['power']);
        $data['powername'] = trim($request['powername']);
        $data['id'] = $request['id'];
        $powers = self::nodearr($request['power']);

        $setPowers = json_encode($powers);
        $setPower = trim(str_replace(' ', '', $setPowers));
        $data['powers'] = iconv('utf-8', 'gb2312', $setPower);
        //更新数据
//        aa($data);
        $updateFlag = Role::where(['id' => $data['id']])->update($data);

        if ($updateFlag) {
            $result = array('success' => true, 'message' => 'success');
        } else {
            $result = array('success' => false, 'message' => 'error');
        }

        return json_encode($result);
    }

    public static function nodearr($power = '')
    {
        if (!empty($power)) {
            $ids = self::getids($power);
        }

        $result = Node::whereIn('id', $ids)->get(['name', 'title', 'path', 'pid', 'id', 'icon'])->toarray();
        foreach ($result as $key => &$value) {
            $value['abspath'] = trim($value['path'] . '_' . $value['id']);
            $cnode = explode('_',$value['path']);
            if ($value['name'] == '/') {
                $data[$value['id']]['powers'][] = $value;
            } else if($cnode[0] == 0){
                //判断是否有3级子节点
                $res = self::hasChildren($value['id']);
//                $res = self::hasChildren(10285);
                if($res['hasChildren']){
                    $value['children'] = $res['children'];
                }else{
                    $value['children'] = [];
                }
                $data[$value['pid']]['childrens'][] = $value;
            }
        }
        return $data;
    }

    public static function getids($ids = '')
    {

        if (!empty($ids)) {
            $arr = explode(',', $ids);
            $map['id'] = array('in', $ids);
            $tmp = [];
            $result = Node::whereIn('id', $arr)->get(['pid'])->toarray();

            foreach ($result as $value) {
                $tmp[] = $value['pid'];
            }

            $base = array_unique($tmp);
            $diff = array_diff($base, $arr);
            sort($diff);
            if ($diff[0] == 0) {
                array_shift($diff);
            }
            $idsarray = array_merge($diff, $arr);
            return $idsarray;
        }
    }

    /**
     * 添加角色
     * @param $data
     */
    public static function roleAdd($data)
    {
        $Role = new Role();
        $data['ctime'] = time();
        $powers = self::nodearr($data['power']);
        $data['powers'] = json_encode($powers);
        if (empty($data['name'])) {
            $result = array(
                'success' => false,
                'msg' => '角色为必填内容!',
            );
        } elseif ($Role->insert($data)) {
            $result = array(
                'success' => true,
                'msg' => '添加成功!',
            );
        } else {
            $result = array(
                'success' => false,
                'msg' => 'error',
            );
        }
        echo json_encode($result);
    }

    /**
     * 删除角色
     */
    public static  function remove($request){
        $Role = new Role();
        if($Role->where('id',$request['id'])->delete()){
            $result = array(
                'success'=>true,
                'msg'=>'删除成功!',
            );
        }else{
            $result = array(
                'success'=>false,
                'msg'=>'删除失败',
            );
        }
        echo json_encode($result);
    }

     public static function hasChildren($pid)
     {
        $children = [];
        $result = Node::where(['pid'=>$pid])->get(['id','pid','name','title','path','icon']);
        if(!empty($result)){
            $result = $result->toArray();
            foreach($result as $key=>&$val){
                $val['abspath'] = '0_'.$val['path'].'_'.$val['id'];
                $children[$key] = $val;
            }
            $data['hasChildren'] = true;
            $data['children'] = $children;
        }else{
            $data['hasChildren'] = false;
        }
        return $data;
     }
}
