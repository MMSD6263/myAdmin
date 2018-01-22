<?php
namespace App\Repositories\commonclass;


class Sqlhandle
{
    /*
  * 数据库新增数据
  * */
    public static function increase($table,$content){
        if(!empty($table)){
            if(!empty($content)){
                if($id=$table->insertGetId($content)){
                    $info = array('success'=>true,'id'=>$id,'msg'=>'数据添加成功');
                }else{
                    $info = array('success'=>false,'msg'=>'数据添加失败');
                }
            }else{
                $info = array('success'=>false,'msg'=>'所需保存的数据不能为空');
            }
        }else{
            $info = array('success'=>false,'msg'=>'数据库名需存在');
        }
        return $info;

    }
    /*
     * 数据库数据删除接口
     * */
    public static function leaveout($table,$field,$flog){
        if(!empty($table)){
            if(!empty($field)){
                if(!empty($flog)){
                    if($table->where($field,$flog)->delete()){
                        $info =array('success'=>true,'msg'=>'数据删除成功');
                    }else{
                        $info = array('success'=>false,'msg'=>'数据删除失败');
                    }
                }else{
                    $info = array('success'=>false,'msg'=>'需提供删除的内容');
                }
            }else{
                $info = array('success'=>false,'msg'=>'需提供删除的标识');
            }
        }else{
            $info = array('success'=>false,'msg'=>'需提供数据库名');
        }
        return $info;
    }

    /*
     * 数据库修改数据接口
     * */
    public static function transform($table,$field,$flog,$content){
        if(!empty($table)){
            if(!empty($field)){
                if(!empty($flog)){
                    if(!empty($content)){
                        if($table->where($field,$flog)->update($content)){
                            $info = array('success'=>true,'msg'=>'数据修改成功');
                        }else{
                            $info = array('success'=>false,'msg'=>'数据修改失败');
                        }
                    }else{
                        $info = array('success'=>false,'msg'=>'修改数据内容不能为空');
                    }

                }else{
                    $info =array('success'=>false,'msg'=>'需提供修改的指针');
                }
            }else{
                $info = array('success'=>false,'msg'=>'需提供修改的参数');
            }
        }else{
            $info = array('success'=>false,'msg'=>'需提供数据库名');
        }
        return $info;
    }

    /*
     * 数据库查询数据并数组返回数据
     *
     * */

    public static function Inquiry($table,$field,$flog,$data=['*'])
    {
        if(!empty($table)){
            if(!empty($field)){
                if(!empty($flog)){
                    //$info = $table->where($field,$flog)->get($data)->first()->toArray();
                    $info=false;
                    if($info){
                        $result=array('success'=>true,'info'=>$info);
                    }else{
                        $result = array('success'=>false,'info'=>'没有查询到数据');
                    }
                }else{
                    $result = array('success'=>false,'info'=>'查询参数不能为空');
                }
            }else{
                $result = array('success'=>false,'info'=>'参数不能为空');
            }
        }else{
            $result = array('success'=>false,'info'=>'数据仓库不能为空');
        }

        return $result;
    }


    /*
     * 查询是否数据是否存在
     * */
    public static function  data_exist($table,$field,$flog,$data=['*'])
    {
        if($table->where($field,$flog)->get($data)->find('')){
              $result = true;
        }else{
              $result = false;
        }
        return $result;
    }

}
