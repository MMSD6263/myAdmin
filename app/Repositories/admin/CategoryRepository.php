<?php


namespace App\Repositories\admin;
use App\Models\Type;
use App\Models\TypeColumn;
use App\Repositories\commonclass\whereserach;

/*
 * 取出运营总表的数据
 */

class CategoryRepository
{
    private $_type;
    private $_typecolumn;
    public function __construct()
    {
        $this->_type = new Type();
        $this->_typecolumn = new TypeColumn();
    }

    /*
     * 获取分类数据
     */
    public function getType()
    {
        $info = $this->_type->where('type_status','=',1)->get(['tid','type_name'])->toArray();
        return $info;
    }

    public function CateAjaxData($request)
    {
        $limit        = $request['limit'];
        $offset       = $request['offset'];
        if($request['title']){
            $this->_type = whereserach::whereNames($this->_type,'type_name',$request['title'],true);
        }
        if(isset($request['status'])){
            $this->_type = whereserach::whereStatus($this->_type,'type_status',$request['status']);
        }
        if(!empty($request['start'])||!empty($request['end'])){
            $this->_type = whereserach::wherecdate($this->_type,'ctime',$request['start'],$request['end']);
        }
        $count = $this->_type->count();
        $list = $this->_type
            ->offset($offset)
            ->limit($limit)
            ->get(['tid','type_name','type_status','ctime']);
        foreach($list as &$val){
            if($val['ctime']){
                $val['ctime']=date('Y-m-d H:i:s',$val['ctime']);
            }else{
                $val['ctime']='';
            }
        }
        $result = [
            'rows'  => $list,
            'total' => $count,
        ];
        return json_encode($result);
    }


    /*
     * 添加、修改分类
     */

    public function SaveData($request)
    {
        $data['type_name']=$request['type_name'];
        $data['type_status']=$request['type_status'];
        $data['pid'] = 0;
        $data['path']= 0;
        $data['ctime'] = time();
            if($this->_type->insertGetid($data)){
                $result = array('success'=>true,'msg'=>'数据保存成功');
            }else{
                $result = array('success'=>false,'msg'=>'数据保存失败');
            }

        return json_encode($result);
    }

    public function  Modified($request)
    {
        $tid = $request['tid'];
        $data['type_name'] =$request['type_name'];
        $data['type_status']= $request['type_status'];
        $data['ctime'] =time();
        if($this->_type->where('tid',$tid)->update($data)){
            $result = array('success'=>true,'msg'=>'数据更新成功');
        }else{
            $result = array('success'=>false,'msg'=>'数据更新失败');
        }
        return json_encode($result);
    }




    public function UpdateData($request)
    {
        $cid = $request['cid'];
        $data['tid']=$request['tid'];
        $data['column_name']=$request['column_name'];
        $data['column_status']=$request['column_status'];
        $data['sort'] = $request['sort'];
        $data['ctime'] = time();
        if($this->_typecolumn->where('cid',$cid)->update($data)){
            $result = array('success'=>true,'msg'=>'数据保存成功');
        }else{
            $result = array('success'=>false,'msg'=>'数据保存失败');
        }
        return json_encode($result);
    }


    public function Issue($request)
    {
        $tid = $request['tid'];
        if($request['type_status']==0){
            $data['type_status']=1;
        }else if($request['type_status']==1){
            $data['type_status']=0;
        }
        if($this->_type->where('tid',$tid)->update($data)){
            $result = array('success'=>true,'msg'=>'操作成功');
        }else{
            $result = array('success'=>false,'msg'=>'操作失败');
        }
        return json_encode($result);
    }


    public function ChannelajaxData($request)
    {
        $limit        = $request['limit'];
        $offset       = $request['offset'];
        if($request['title']){
            $this->_typecolumn = whereserach::whereNames($this->_typecolumn,'type_name',$request['title'],true);
        }
        if(isset($request['status'])){
            $this->_typecolumn = whereserach::whereid($this->_typecolumn,'tid',$request['status']);
        }
        if(isset($request['state'])){
            $this->_typecolumn = whereserach::whereStatus($this->_typecolumn,'column_status',$request['state']);
        }
        if(!empty($request['start'])||!empty($request['end'])){
            $this->_typecolumn = whereserach::wherecdate($this->_typecolumn,'ctime',$request['start'],$request['end']);
        }
        $count = $this->_typecolumn->count();
        $list = $this->_typecolumn
            ->offset($offset)
            ->limit($limit)
            ->get(['cid','tid','sort','type_name','column_name','column_status','ctime']);
        foreach($list as &$val){
            if($val['ctime']){
                $val['ctime']=date('Y-m-d H:i:s',$val['ctime']);
            }else{
                $val['ctime']='';
            }
        }
        $result = [
            'rows'  => $list,
            'total' => $count,
        ];
        return json_encode($result);
    }



    public function ChannelajaxData1($request)
    {
        $this->_typecolumn = whereserach::whereid($this->_typecolumn,'tid',$request['tid']);
        $count = $this->_typecolumn->count();
        $list = $this->_typecolumn
            ->get(['cid','tid','sort','type_name','column_name','column_status','ctime','column_pic']);
        foreach($list as &$val){
            if($val['ctime']){
                $val['ctime']=date('Y-m-d H:i:s',$val['ctime']);
            }else{
                $val['ctime']='';
            }
            if($val['column_pic']){
                $val['column_pic'] = "<img src='".getenv('VIDEO_DOMAIN').$val['column_pic']."' style='width:50px;height:50px;'>";
            }else{
                $val['column_pic'] = "-";
            }

        }
        $result = [
            'rows'  => $list,
            'total' => $count,
        ];
        return json_encode($result);
    }


    public function Condition($request)
    {
        $cid = $request['cid'];
        if($request['column_status']==0){
            $data['column_status']=1;
        }else if($request['column_status']==1){
             $data['column_status']=0;
        }
        if($this->_typecolumn->where('cid',$cid)->update($data)){
            $result = array('success'=>true,'msg'=>'更新成功');
        }else{
            $result = array('success'=>false,'msg'=>'更新失败');
        }
        return json_encode($result);
    }
    public function SaveChannel($request)
    {
        $data['tid']=$request['tid'];
        $data['column_name']=$request['column_name'];
        $data['column_status']=$request['column_status'];
        $data['sort'] = $request['sort'];
        $data['type_name'] = $request['type_name'];
        $data['ctime'] = time();
        $data['pid'] = 0;
        $data['path'] = 0;
        if(!empty($request['column_pic'])){
            $res = $this->convertThumb($request['column_pic']);
            $res = json_decode($res,true);
            if($res['success']){
                $data['column_pic'] = substr(trim($res['message']),1);
                if($this->_typecolumn->insertGetid($data)){
                    $result = array('success'=>true,'msg'=>'频道分配成功');
                }else{
                    $result = array('success'=>false,'msg'=>'频道分配失败');
                }
                return json_encode($result);
            }else{
                $result = array('success'=>false,'msg'=>'封面图片上传失败！');
                return json_encode($result);
            }
        }else{
            $data['column_pic'] = '';
            if($this->_typecolumn->insertGetid($data)){
                $result = array('success'=>true,'msg'=>'频道分配成功');
            }else{
                $result = array('success'=>false,'msg'=>'频道分配失败');
            }
            return json_encode($result);
        }


    }
    public function Editor($request)
    {
        $cid = $request['cid'];
        $info = $this->_typecolumn->where('cid',$cid)->get(['tid','column_status','sort','column_name','column_pic'])->find('')->toArray();
        if($info['column_pic']){
            $info['column_pic'] = getenv('VIDEO_DOMAIN').$info['column_pic'];
        }
        return $info;

    }

    public function ChannelSave($request)
    {
        $cid = $request['cid'];
        $data['tid'] =$request['tid'];
        $data['sort'] = $request['sort'];
        $data['type_name'] = $request['type_name'];
        $data['column_name'] = $request['column_name'];
        $data['column_status'] = $request['column_status'];
        $data['ctime']=time();

        if($request['column_pic']){
            $str = 'columnImage';
            if(strpos($request['column_pic'],$str)){
                $data['column_pic'] = $request['column_pic'];
            }else{
                $res = $this->convertThumb($request['column_pic']);
                $res = json_decode($res,true);
                if($res['success']){
                    $data['column_pic'] = substr($res['message'],1);
                    if($this->_typecolumn->where('cid',$cid)->update($data)){
                        $result = array('success'=>true,'msg'=>'频道更新成功');
                    }else{
                        $result = array('success'=>false,'msg'=>'频道更新失败');
                    }
                    return json_encode($result);
                }else{
                    $result = array('success'=>false,'msg'=>'图片保存失败！');
                    return json_encode($result);
                }
            }
        }else{
            $data['column_pic'] = '';
            if($this->_typecolumn->where('cid',$cid)->update($data)){
                $result = array('success'=>true,'msg'=>'频道更新成功');
            }else{
                $result = array('success'=>false,'msg'=>'频道更新失败');
            }
            return json_encode($result);
        }

    }


    public function convertThumb($imageResource){
        $base64_image_content = $imageResource;
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type     = $result[2];
            $save_path = "./src/resource/columnImage/";
            if (!file_exists($save_path)) {
                mkdir($save_path, 0700);
            }
            $dist_file = $save_path . time() . ".{$type}";
            if (file_put_contents($dist_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $result = message(true,$dist_file);
            } else {
                $result = message(false, '图片保存失败 -11002');
            }
        } else {
            $result = message(false, '数据匹配失败 -11002');
        }

        return $result;
    }

}
