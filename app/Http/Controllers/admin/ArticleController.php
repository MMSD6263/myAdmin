<?php

namespace App\Http\Controllers\admin;

use App\Repositories\admin\ArticleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Libs\TransClass;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class ArticleController extends Controller
{
    private $_article;

    public function __construct()
    {
        $this->_article = new ArticleRepository();
    }

    public function index()
    {
        $typeColumn  = $this->_article->getTypeColumn();
        $faceAccount = $this->_article->getAccount();
        return view('admin.article.index', compact(['typeColumn', 'faceAccount']));
    }

    /**
     * lists 数据表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request)
    {
        $pack = $this->_article->Sort();      //获取分类列表
        $user = $this->_article->getUser();   //获取当前用户
        $typeList = [];
        $lists = [];
        foreach($pack as $key=>&$value){
            $typeList[$key]['id'] = $value['tid'];
            $typeList[$key]['name'] = $value['type_name'];
            $typeList[$key]['pid'] = 0;
            $colList = $this->getSubColumn($value['tid']);
//            $jtypeList = [];
//            $ptypeList = [];
//            foreach($colList as $k=>&$v){
//                $ptypeList[$k]['id'] = $v['id'];
//                $ptypeList[$k]['name'] = $v['name'];
//                $ptypeList[$k]['pid'] = $v['pid'];
//                if(isset($v['jtype'])){
//                    foreach($v['jtype'] as $ke=>&$val){
//                        $jtypeList[$ke]['id'] = $val['jid'];
//                        $jtypeList[$ke]['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val['jname'];
//                        $jtypeList[$ke]['pid'] = $val['cid'];
//                    }
//                }
//             $new1 = array_merge($ptypeList,$jtypeList);
//            }
            $new = array_merge($typeList,$colList);
//            $new = array_merge($typeList,$new1);
            unset($typeList);
            array_push($lists,$new);
        }

        $clists = [];
        foreach($lists as $value){
            foreach($value as $sub_value){
                $clists[] = $sub_value;
            }
        }


        return view('admin.article.list', compact(['clists','user']));
    }


    public function getSubColumn($tid)
    {
        $subCat = $this->_article->getColumn($tid);
        $sucCat = [];
//        $testArr = [];
        foreach($subCat as $key=>&$value){
//            $jtype = $this->_article->getJtype($value['cid'],$tid);
//            if(!empty($jtype)){
//                $sucCat[$key]['jtype'] = $jtype;
//            }
            $sucCat[$key]['id'] = $tid.'-'.$value['cid'];
            $sucCat[$key]['name'] = '&nbsp;&nbsp;&nbsp;'.$value['column_name'];
            $sucCat[$key]['pid'] = $tid;
            return $sucCat;
        }
    }
    /**
     * 异步请求
     * @param Request $request
     * @return string
     */
    public function ajaxData(Request $request)
    {
        $data   = $request->except(['_token']);
        $result = $this->_article->ajaxData($data);
        return $result;
    }

    /**
     * 删除文章
     * @param Request $request
     * @return string
     */
    public function articleDelete(Request $request)
    {
        $data   = $request->except(['_token']);
        $result = $this->_article->articleDelete($data);
        return $result;
    }

    /**
     * 添加文章
     * @param Request $request
     * @return string
     */
    public function addArticle(Request $request)
    {
        $data = $request->except(['_token']);

        if ($data['small_pic']) {
            $smallPic     = [];
            $smallPicPath = explode("##", $data['small_pic']);
            foreach ($smallPicPath as $value) {
                if ($value) {
                    $smallPic[] = $value;
                }
            }
            $data['small_pic'] = json_encode($smallPic);
        } else {
            $data['small_pic'] = '';
        }

        $addResult = $this->_article->addArticle($data);
        $addResult = json_decode($addResult, true);

        if ($request) {
            $result = message(true, $addResult['success']);
            Log::info('添加成功123');
        } else {
            $result = message(false, "文章添加失败");
        }


        return $result;
    }


    /**
     * 后台webUploader上传
     * @param Request $request
     * @return string
     */
    public function webUploader(Request $request)
    {
        $ctime = $request['ctime'];
        $file = $request->file('file');
        if ($file->isValid()) {
            //获取原文件名
            $originalName = $file->getClientOriginalName();
            //扩展名
            $ext = $file->getClientOriginalExtension();
            //文件类型
            $type = $file->getClientMimeType();
            //临时绝对路径
            $realPath = $file->getRealPath();

            $filePath = date('Y/m/d',$ctime).'/'.$ctime.'/';
            $filename = 'smallpic/'.$filePath .$ctime . rand() . '.'.$ext;
            $bool = Storage::disk('public')->put($filename, file_get_contents($realPath));
            if ($bool) {
                $path   = '/src/resource/' . $filename;
                $result = message(true, $path);
            } else {
                $result = message(false, '视频上传失败');
            }
        } else {
            $result = message(false, '上传类型不正确');
        }
        return $result;
    }

    /**
     * 修改文章
     * @param Request $request
     * @return array|string
     */
    public function editArticle(Request $request)
    {
        $data   = $request->except(['_token']);
        $result = $this->_article->editArticle($data);
        return $result;
    }

    /**
     * 预览文章
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function preview(Request $request)
    {
        $result = $request->except(['_token']);
        if (!empty($result['tid'])) {
            Session::put('preview', $result);
        } else {
            $result                  = Session::get('preview');
            $result['creation_time'] = time();
            $result['id']            = 1;
            return view('admin.article.preview', compact(['result']));
        }
    }

    /**
     * 修改文章预览
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPreview(Request $request)
    {
        $result = $request->except(['_token']);
        if (!empty($result['type'])) {
            Session::put('preview', $result);
        } else {
            $result                  = Session::get('preview');
            $result['creation_time'] = time();
            $result['id']            = 1;
            $result['small_pic']     = 1;
            return view('admin.article.preview', compact(['result']));
        }
    }

    /**
     * 简体转繁体
     * @param Request $request
     * @return string
     */
    public function trans(Request $request)
    {
        $data       = $request->except(['_token']);
        $go         = new TransClass();
        $content    = $data['content'];
        $title      = $data['title'];
        $profile    = $data['profile'];
        $strContent = $go->c2t($content);
        $strTitle   = $go->c2t($title);
        $strProfile = $go->c2t($profile);
        return json_encode([
            'title'   => $strTitle,
            'content' => $strContent,
            'profile' => $strProfile,
        ]);
    }


    /**
     * 文章修改
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleEdit(Request $request)
    {
        $data        = $request->except(['_token']);
        $articleInfo = $this->_article->articleInfo($data);
        $typeColumn  = $this->_article->getTYpeColumn();
        $aid         = $data['aid'];
        $faceAccount = $this->_article->getAccount();

        //重新组装缩略图

        $small_pic = implode("##", json_decode($articleInfo['small_pic'], true));

        if (!empty($aid)) {
            return view('admin.article.edit', compact(['articleInfo', 'typeColumn', 'aid', 'small_pic', 'faceAccount']));
        } else {
            abort(404);
        }
    }

    public function getImg(Request $request)
    {
        $data = $request->except(['_token']);
        $res  = $this->_article->changeContent($data);
        return $res;
    }


    /*
     * 获取文章详情
     */
    public function getArticleDetail(Request $request)
    {
        $data = $request->except(['_token']);
        $res  = $this->_article->getArticleDetail($data);
        return $res;
    }

    /*
     * 抓取文章
     */
    public function fetchArticle(Request $request)
    {
        $data = $request->except(['_token']);
        $res  = $this->_article->fetchArticle($data);
        return $res;
    }


    public function test()
    {

    }


}