<?php

namespace App\Repositories\admin;

use App\Models\Admin;
use App\Repositories\commonclass\whereserach;
use App\Models\Article;
use App\Models\Type;
use App\Models\Jtype;
use Illuminate\Support\Facades\Redis;
use App\Models\TypeColumn;
use App\Models\User;
use phpQuery;
use Illuminate\Support\Facades\Session;

/**
 * 文章仓库
 * Class ArticleRepository
 * @package App\Repositories\admin
 */
class ArticleRepository
{
    private $_article;
    private $_articleKey;
    private $_type;
    private $_user;
    private $_sctime;
    private $_path = "/data/www/faceBookAdmin/public";


    public function __construct()
    {
        $this->_article = new Article();
        $this->_type    = new Type();
        $this->_user    = new User();
    }

    /**
     * 正常状态下类别
     * @return mixed
     */
    public function Sort()
    {
        $info = $this->_type->where('type_status', '=', 1)->get(['tid', 'type_name'])->toArray();
        return $info;
    }

    /**
     * 获取用户列表
     */
    public function getUser()
    {
        $list = Admin::where(['status'=>1])->get(['id','rid','username']);
        return $list;
    }

    /**
     * 数据异步请求
     * @param $request
     * @return string
     */
    public function ajaxData($request)
    {
        $limit  = $request['limit'];
        $offset = $request['offset'];

        if (!empty($request['title'])) {
            $this->_article = whereserach::whereNames($this->_article, 'title', $request['title'], true);
        }

        if (!empty($request['start']) || !empty($request['end'])) {
            $this->_article = whereserach::wherecdate($this->_article, 'ctime', $request['start'], $request['end']);
        }
        if (isset($request['typename'])) {
            if(!empty($request['typename'])){
                if(strpos(trim($request['typename']),'-')){
                    $cid = explode('-',$request['typename'])[1];
                    $this->_article = whereserach::whereid($this->_article, 'cid', $cid);
                }else{
                    $this->_article = whereserach::whereid($this->_article, 'tid', $request['typename']);
                }
            }
        }
        
        if (isset($request['status'])) {
            $this->_article = whereserach::whereStatus($this->_article, 'article_status', $request['status']);
        }

        if (isset($request['username'])) {
            $this->_article = whereserach::whereStatus($this->_article, 'author', $request['username']);
        }

        $count = $this->_article->count();
        $list  = $this->_article
            ->orderBy('ctime', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($list as &$val) {
            $val['ctime']     = date('Y-m-d H:i:s', $val['ctime']);
            $val['send_time'] = date('Y-m-d H:i:s', $val['send_time']);
            if ($val['face_account']) {
                $uidArr     = explode(',', $val['face_account']);
                $accountArr = $this->_user->whereIn('uid', $uidArr)->get(['uid', 'user_account'])->toArray();
                $userArr    = [];
                foreach ($accountArr as $value) {
                    $userArr [] = $value['user_account'];
                }
                $val['face_account'] = implode($userArr, ',');
            }
        }


        $result = [
            'rows'  => $list,
            'total' => $count,
        ];

        return json_encode($result);
    }

    /**
     * 添加文章列表
     * @param $request
     */
    public function addArticle($request)
    {
        if ($request['tid']) {
            $data['title']          = empty($request['title']) ? '' : $request['title'];
            $data['content']        = $request['content'];
            $data['tid']            = $request['tid'];
            $data['type_name']      = empty($request['type_name']) ? '' : $request['type_name'];
            $data['cid']            = $request['cid'];
            $data['column_name']    = empty($request['column_name']) ? '' : $request['column_name'];
            $data['wid']            = 1;
            $data['article_status'] = empty($request['article_status']) ? 0 : $request['article_status'];
            $data['uid']            = $request['uid'];
            $data['author']         = session('admin.username');
            $data['send_time']      = strtotime($request['send_time']);
            $data['small_pic']      = $request['small_pic'];
            $data['profile']        = $request['profile'];
            $data['ctime']          = time();
            $data['face_account']   = empty($request['face_account']) ? '' : $request['face_account'];

            //数据入库
            if ($id = $this->_article->insertGetId($data)) {
//                if ($request['article_status'] == 1) {
//                    //加入最新文章缓存 摸个栏目latestArticle_2_3
//                    $latestArticleKey = "latestArticle_" . $data['tid'] . "_" . $data['cid'];
//                    $this->ListArticleIdPush($latestArticleKey, $id);
//                    $this->_articleKey = 'article|' . $data['tid'] . '|' . $id;
//                    foreach ($data as $k => $v) {
//                        Redis::hset($this->_articleKey, $k, $v);
//                    }
//                    Redis::expire($this->_articleKey, 86400);
//                }
                $result = self::message($id, '文章保存成功');
            } else {
                $result = self::message(false, '文章保存失败');
            }
        } else {
            $result = self::message(false, 'ID error -92004');
        }
        return $result;
    }

    /**
     * 删除文章
     * @param $request
     * @return string
     */
    public function articleDelete($request)
    {
        if ($request['id']) {
            $articleInfo = $this->_article->where(['aid' => $request['id']])->first(['aid', 'cid', 'wid', 'tid', 'ctime']);
            if ($articleInfo) {
                if ($this->_article->where(['aid' => $request['id']])->delete()) {
                    $data = $articleInfo->toarray();
                    $key  = $latestArticleKey = "latestArticle_" . $data['tid'] . "_" . $data['cid'];;
                    $this->ListArticleIdDel($key, $request['id']);
                    $result = self::message(true, 'the delete success');
                    $delKey = 'delete_image_dir';
                    Redis::LPUSH($delKey, $articleInfo->toArray()['ctime']);
                } else {
                    $result = self::message(false, 'the delete error -1001');
                }
            } else {
                $result = self::message(false, 'delete error -1003');
            }
        } else {
            $result = self::message(false, 'delete error -1004');
        }

        return $result;
    }



    public static function makeSmallPic($pic)
    {
        if (!empty($pic)) {
            $result = json_encode(explode('##', $pic));
        } else {
            $result = [];
        }
        return $result;
    }

    /**
     * 文章列表存入redis
     * @param $ListKey
     * @param $key
     */
    private function ListArticleIdPush($ListKey, $key)
    {
        Redis::LRem($ListKey, 1000, $key);
        Redis::Lpush($ListKey, $key);
        Redis::expire($ListKey, 86400);
    }

    /**
     * 文章列表删除redis
     * @param $ListKey
     * @param $key
     */
    private function ListArticleIdDel($ListKey, $key)
    {
        Redis::LRem($ListKey, 1000, $key);
    }

    public function getTypeColumn()
    {
        $result = [];
        $list   = Type::where('type_status', 1)
            ->get(['tid', 'type_name']);

        foreach ($list as $type){
            $typeColumn = TypeColumn::where('column_status', 1)
                ->where('tid', $type->tid)
                ->get(['cid', 'column_name'])
                ->toarray();

            $result[$type->tid] = [
                'tid'       => $type->tid,
                'type_name' => $type->type_name,
                $type->tid  => $typeColumn,
            ];
        }

        return json_encode($result);
    }

    /**
     * 获取文章详情
     */
    public function articleInfo($request)
    {
        if (!empty($request['aid'])) {
            if ($list = $this->_article->where("aid", '=', $request['aid'])->first()->toarray()) {
                $result = $list;


                //将文章图片存入全局
                $pattern = '/src=\"(.*?)\"/';
                preg_match_all($pattern,$list['content'],$imageArr);
                $images = [];
                if(!empty($imageArr[1])){
                    foreach($imageArr[1] as $value){
                        array_push($images,substr(trim($value),stripos(trim($value),'/')+1));
                    }
                    Session::put('old_content_images',$images);
                }

                //将缩略图存入全局
                $smallpic = [];
                if(!empty(json_decode($list['small_pic'],true))){
                    foreach(json_decode($list['small_pic'],true) as $val){
                        if(!empty($val)){
                            array_push($smallpic,substr(trim($val),stripos(trim($val),'/')+1));
                        }
                    }
                    Session::put('old_small_pic',$smallpic);
                }

            } else {
                $result = self::message(false, 'error -6996');
            }
        } else {
            $result = self::message(false, 'error -69966');
        }

        return $result;
    }


    /**
     * 修改文章
     * @param $request
     * @return array|string
     */
    public function editArticle($request)
    {
        //删除文章中已修改的图片
        $contents = $request['content'];
        $preg = '/src=\"(.*?)"/';
        preg_match_all($preg,$contents,$arr);
        if(!empty($arr)){
            $new_content_images = $arr[1];
        }
        $old_content_images = [];
        if(!empty(Session::get('old_content_images'))){
            foreach(Session::get('old_content_images') as &$value){
                $old_content_images[] = 'http:/'.$value;
            }
            $del_content_images = array_diff($old_content_images,$new_content_images);

            if(!empty($del_content_images)){
                foreach($del_content_images as $val){
                    $mvfile = '/data/www/faceBookAdmin/public/src/resource/'.substr($val,strpos($val,'resource')+9);
                    if(file_exists($mvfile)){
                        unlink($mvfile);
                    }
                }
            }
        }


        //删除服务器上的缩略图
        $small_pics = $request['small_pic'];
        $new_small_pics = [];
        $old_small_pics = [];

        foreach(explode('##',$small_pics) as $v1){
            $new_small_pics[] = ltrim($v1,'/');
        }
        foreach(Session::get('old_small_pic') as $v2){
            $old_small_pics[] = ltrim($v2,'/');
        }

        $del_small_pics = array_diff($old_small_pics,$new_small_pics);
        
        if(!empty($del_small_pics)){
            foreach($del_small_pics as $v){
                if(file_exists('/data/www/faceBookAdmin/public/'.$v)){
                    unlink('/data/www/faceBookAdmin/public/'.$v);
                }
            }
        }

        $result = [];
        if (!empty($request)) {
            $data['title']          = empty($request['title']) ? '' : $request['title'];
            $data['content']        = $request['content'];
            $data['tid']            = $request['tid'];
            $data['type_name']      = empty($request['type_name']) ? '' : $request['type_name'];
            $data['cid']            = $request['cid'];
            $data['column_name']    = empty($request['column_name']) ? '' : $request['column_name'];
            $data['wid']            = 1;
            $data['article_status'] = empty($request['article_status']) ? 0 : $request['article_status'];
            $data['uid']            = $request['uid'];
            $data['author']         = session('admin.username');
            $data['send_time']      = strtotime($request['send_time']);
            $data['profile']        = $request['profile'];
            $data['small_pic']      = $this->makeSmallPic($request['small_pic']);

            if (!empty($request['user_account']) && !empty($request['face_account'])) {
                $data['face_account'] = $request['user_account'] . ',' . $request['face_account'];
            } else if (empty($request['user_account']) && !empty($request['face_account'])) {
                $data['face_account'] = $request['face_account'];
            }


            if ($this->_article->where(["aid" => $request['aid']])->update($data)) {
                //更新文章缓存
                if ($request['aid']) {
                    $this->_articleKey = 'article|' . $data['tid'] . '|' . $request['aid'];
                    Redis::del($this->_articleKey);
                }
//                if ($request['article_status'] == 4) {
//                    //加入最新文章缓存 摸个栏目
//                    $latestArticleKey = "latestArticle_" . $data['tid'] . "_" . $data['cid'];
//                    $this->ListArticleIdPush($latestArticleKey, $request['aid']);
//                }


                $result = self::message(true, 'update success');
            } else {
                $result = self::message(false, 'update error -1003');
            }
        }

        return $result;

    }






    /**
     * 抛出信息
     * @param string $success
     * @param string $message
     * @return string
     */
    public static function message($success = '', $message = '')
    {
        return json_encode(['success' => $success, 'message' => $message]);
    }

    /**
     * 下载文章图片到本地，并更改文章图片链接为本地连接
     */

    public function changeContent($request)
    {
        $content  = $request['content'];
        $doc      = phpQuery::newDocumentHTML($content);
        $contents = $doc->find("img");
        $dataSrc  = [];
        $src      = [];
        $dataDist = [];
        foreach ($contents as $val) {
            $dataSrc[]  = pq($val)->attr('data-src');
            $src[]      = pq($val)->attr('src');
            $dataDist[] = substr(self::getimg(pq($val)->attr('data-src')), 1);
        }

        $newContent = str_replace($dataSrc, $dataDist, $content);
        $newContent = str_replace($src, $dataDist, $newContent);
        return self::message('true', $newContent);
    }

    /*
     * 下载图片到本地
     */
    private function getimg($url, $type = '')
    {
        $ch         = curl_init();
        $httpheader = array(
            'Host'            => 'mmbiz.qpic.cn',
            'Connection'      => 'keep-alive',
            'Pragma'          => 'no-cache',
            'Cache-Control'   => 'no-cache',
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,/;q=0.8',
            'User-Agent'      => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
            'Accept-Encoding' => 'gzip, deflate, sdch',
            'Accept-Language' => 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4',
        );
        $options    = array(
            CURLOPT_HTTPHEADER     => $httpheader,
            CURLOPT_URL            => $url,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
//        $this->_sctime    = time();
        $urlname = $this->_sctime . rand();
        $datePre = date('Y', time()) . '/' . date('m', time()) . '/' . date('d', time()) . '/';
//        $dat     = date('Y-m-d', $time);
        mkdirs('./src/resource/' . $datePre . $this->_sctime);
        if (!$type) {
            $imgurl = './src/resource/' . $datePre . $this->_sctime . '/' . $urlname . '.png';
        } else {
            $imgurl = './src/resource/' . $datePre . $this->_sctime . '/' . $urlname . '.' . $type;
        }
        file_put_contents($imgurl, $result);
        return $imgurl;
    }


    /*
     * 获取文章详情数据
     */
    public function getArticleDetail($request)
    {
        $detailArr = $this->_article->where(['aid' => $request['aid']])->first(['aid', 'cid', 'tid'])->toArray();
        $web_url   = $this->_type->where(['tid' => $detailArr['tid']])->value('domain');
//        $wid                  = explode(',', $detailArr['wid'])[0];
//        $web_url              = Website::where(['wid' => $wid])->value('web_url');
        $detailArr['web_url'] = $web_url;
        return json_encode(['success' => true, 'data' => $detailArr]);
    }

    /*
     * 抓取文章
     */
    public function fetchArticle($request)
    {
        $articleUrl      = $request['articleUrl'];
        $articleResource = $request['articleResource'];
        phpQuery::newDocumentFile($articleUrl);

        $this->_sctime = time();
        //一点资讯
        if ($articleResource == 1) {
            $original_id = trim(explode('/', $articleUrl)[4]);
            $aid         = $this->_article->where(['original_id' => $original_id])->value('aid');
            if ($aid) {
                return self::message(false, '该文章已经下载，请重新下载其他文章！');
            } else {
                $contents = pq('body')->html();
                //1.文章标题
                $title          = pq($contents)->find('h2')->text();
                $articleContent = pq('.content-bd');
                $images         = pq($articleContent)->find("img");
                $imageArr       = [];

                foreach ($images as $image) {
                    $imgurl   = pq($image)->attr("src");//获取每张图片地址
                    $dist_img = $this->getimg($imgurl);
                    $localImg = getenv('VIDEO_DOMAIN') . substr(trim($dist_img), 1);
                    pq($image)->attr("src", trim($localImg));
                    $imageArr[] = substr(trim($dist_img), 1);
                }
                $links = pq($articleContent)->find("a");
                foreach ($links as $link) {
                    pq($link)->attr("href", "");
                }

                //2.文章内容
                $articleContent = $articleContent->html();

                //3.文章缩略图  将4张缩略图移动到其他的文件夹下面，并更改数据库路径

//                $small_pic = json_encode(array_splice($imageArr, 0, 4));
                $small_pic = array_splice($imageArr,0,4);
                $small_pic = $this->moveSmallPic($small_pic);

                $addData['title']          = $title;
                $addData['content']        = $articleContent;
                $addData['small_pic']      = json_encode($small_pic);
                $addData['ctime']          = $this->_sctime;
                $addData['article_status'] = 0;
                $addData['original_id']    = $original_id;

                if ($this->_article->insertGetId($addData)) {
                    return self::message(true, '成功下载文章');
                }
            }


            //今日头条
        } else if ($articleResource == 2) {
            $original_id = substr(trim(explode('/', $articleUrl)[3]), 1);
            $aid         = $this->_article->where(['original_id' => $original_id])->value('aid');
            if ($aid) {
                return self::message(false, '该文章已经下载，请重新下载其他文章！');
            } else {
                //1.文章标题
                $title          = pq('title')->text();
                $articleContent = pq('.article-content');
                $images         = pq($articleContent)->find("img");
                $imageArr       = [];

                foreach ($images as $image) {

                    $imgurl = pq($image)->attr("src");//获取每张图片地址
                    $dist_img = $this->getimg($imgurl);
                    $localImg = getenv('VIDEO_DOMAIN') . substr(trim($dist_img), 1);
                    pq($image)->attr("src", trim($localImg));
                    $imageArr[] = substr(trim($dist_img), 1);
                }
                $links = pq($articleContent)->find("a");
                foreach ($links as $link) {
                    pq($link)->attr("href", "");
                }
                //2.文章内容
                $articleContent = $articleContent->html();
                //3.文章缩略图
//                $small_pic = json_encode(array_splice($imageArr, 0, 4));
                $small_pic = array_splice($imageArr,0,4);
                $small_pic = $this->moveSmallPic($small_pic);

                $addData['title']          = $title;
                $addData['content']        = $articleContent;
                $addData['small_pic']      = json_encode($small_pic);
                $addData['ctime']          = $this->_sctime;
                $addData['article_status'] = 0;
                $addData['original_id']    = $original_id;

                if ($this->_article->insertGetId($addData)) {
                    return self::message(true, '成功下载文章');
                }
            }


            //微信公众号
        } else if ($articleResource == 3) {
            $original_id = trim(explode('/', $articleUrl)[4]);
            $aid         = $this->_article->where(['original_id' => $original_id])->value('aid');
            if ($aid) {
                return self::message(false, '该文章已经下载，请重新下载其他文章！');
            } else {
                $title          = pq('title')->text();
                $title          = iconv('utf-8', 'ISO-8859-1//TRANSLIT', $title);
                $articleContent = pq('#js_content');
                $images         = pq($articleContent)->find("img");
                $imageArr       = [];
                foreach ($images as $image) {
                    $imgurl   = pq($image)->attr("data-src");//获取每张图片地址
                    $type     = pq($image)->attr("data-type");
                    $dist_img = $this->getimg($imgurl, $type);
                    $localImg = getenv('VIDEO_DOMAIN') . substr(trim($dist_img), 1);
                    pq($image)->attr("data-src", trim($localImg));
                    pq($image)->attr("src", trim($localImg));
                    $imageArr[] = substr(trim($dist_img), 1);
                }
                $links = pq($articleContent)->find("a");
                foreach ($links as $link) {
                    pq($link)->attr("href", "");
                }
                //2.文章内容
                $articleContent = $articleContent->html();
                $articleContent = iconv('utf-8', 'ISO-8859-1', $articleContent);
                //3.文章缩略图
//                $small_pic = json_encode(array_splice($imageArr, 0, 4));

                $small_pic = array_splice($imageArr,0,4);
                $small_pic = $this->moveSmallPic($small_pic);


                $addData['title']          = $title;
                $addData['content']        = $articleContent;
                $addData['small_pic']      = json_encode($small_pic);
                $addData['ctime']          = $this->_sctime;
                $addData['article_status'] = 0;
                $addData['original_id']    = $original_id;

                if ($this->_article->insertGetId($addData)) {
                    return self::message(true, '成功下载文章');
                }
            }

            //百家号
        } else if ($articleResource == 4) {
            $listsArr  = $this->getLists($articleUrl)['items'];
            $idArr     = [];
            $aidArr    = [];
            $listCount = count($listsArr);
            foreach ($listsArr as $article) {
                $aid = $this->_article->where(['original_id' => $article['id']])->value('aid');
                if ($aid) {
                    array_push($aidArr, $aid);
                    continue;
                }
                $articleUrl = $article['url'];
                phpQuery::newDocumentFile($articleUrl);
                $title          = pq('title')->text();
                $articleContent = pq('#article');
                $images         = pq($articleContent)->find("img");
                $imageArr       = [];
                foreach ($images as $image) {
                    $imgurl   = pq($image)->attr("src");//获取每张图片地址
                    $type     = substr($imgurl, (strripos($imgurl, '.') + 1));
                    $res      = $this->fetch_bbs_image($imgurl);
                    $dist_img = './src/resource/' . date('Y-m-d', time()) . '/' . time() . mt_rand() . '.' . $type;
                    file_put_contents($dist_img, $res);
                    $imageArr[] = substr(trim($dist_img), 1);
                    $localImg   = getenv('VIDEO_DOMAIN') . substr($dist_img, 1);
                    pq($image)->attr("src", trim($localImg));
                }
                $links = pq($articleContent)->find("a");
                foreach ($links as $link) {
                    pq($link)->attr("href", "");
                }
                //2.文章内容
                $articleContent = $articleContent->html();
                //3.文章缩略图
//                $small_pic                 = json_encode(array_splice($imageArr, 1, 4));

                $small_pic = array_splice($imageArr,0,4);
                $small_pic = $this->moveSmallPic($small_pic);

                $addData['title']          = $title;
                $addData['content']        = $articleContent;
                $addData['small_pic']      = json_encode($small_pic);
                $addData['ctime']          = $this->_sctime;
                $addData['article_status'] = 0;
                $addData['original_id']    = $article['id'];
                $insertId                  = $this->_article->insertGetId($addData);
                array_push($idArr, $insertId);
            }

            $len = count($idArr) + count($aidArr);
            if ($len == $listCount) {
                if (count($aidArr)) {
                    return self::message(true, '成功下载' . count($idArr) . '篇文章,id为' . implode(',', $aidArr) . '的文章已经下载。');
                } else {
                    return self::message(true, '成功下载' . $listCount . '篇文章');
                }
            }
        }
    }


    /*
     * 百家号抓取文章列表数据
     */
    public function getLists($url)
    {
        $ch         = curl_init();
        $httpheader = array(
            'Host'            => 'mmbiz.qpic.cn',
            'Connection'      => 'keep-alive',
            'Pragma'          => 'no-cache',
            'Cache-Control'   => 'no-cache',
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,/;q=0.8',
            'User-Agent'      => 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36',
            'Accept-Encoding' => 'gzip, deflate, sdch',
        );
        $options    = array(
            CURLOPT_HTTPHEADER     => $httpheader,
            CURLOPT_URL            => $url,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => true,
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    /*
     * 百家号抓取数据
     */
    function fetch_bbs_image($url)
    {
        $curl = curl_init($url); //初始化
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        //将结果输出到一个字符串中，而不是直接输出到浏览器
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        //最重要的一步，手动指定Referer
        curl_setopt($curl, CURLOPT_REFERER, $url);
        $re = curl_exec($curl); //执行
        if (curl_errno($curl)) {
            return NULL;
        }
        curl_close($curl);
        return $re;
    }


    /*
     * 获取账号信息
     */

    function getAccount()
    {
        $accountLists = $this->_user->get(['uid', 'user_account']);
        if (!empty($accountLists)) {
            return $accountLists->toArray();
        }
    }


    /**
     * 获取栏目数据
     */
    public function getColumn($tid)
    {
        $condition = [];
        $condition['column_status'] = 1;
        $condition['tid'] = $tid;
        $lists = TypeColumn::where($condition)->get(['cid','column_name'])->toArray();
        return $lists;

    }


    public function getJtype($cid,$tid)
    {
        $condition = [];
        $condition['cid'] = $cid;
        $lists = Jtype::where($condition)->get(['jid','jname','cid'])->toArray();
        foreach($lists as &$value){
            $value['jid'] = $tid.'-'.$cid.'-'.$value['jid'];
        }
        return $lists;
    }


    public function test()
    {
        $url = 'https://v.qq.com/x/cover/bxt4sqjz46y2wyk/w00243aawws.html';
        //获取vid
        $urlArr = explode('/',$url);
        $vidStr = array_pop($urlArr);
        $vid = substr($vidStr,0,strpos($vidStr,'.'));

        //获取title
        $docContent = file_get_contents($url);
        $title_preg = '/<title>(.*?)<\/title>/';
        preg_match($title_preg,$docContent,$arr);
        $title = $arr[1];

    }

    /**
     * 将缩略图存放到独立的文件夹里面
     */
    public function moveSmallPic($picArr)
    {
        $smallPic = [];
        foreach($picArr as $value){
            if(!empty($value)){
                $dir = './src/resource/smallpic'.substr(trim($value),13,22);
                mkdirs($dir);
                $file = './src/resource/smallpic'.substr(trim($value),13);
                file_put_contents($file,file_get_contents($this->_path.$value));
                array_push($smallPic,substr($file,1));
            }
        }
        return $smallPic;
    }


}