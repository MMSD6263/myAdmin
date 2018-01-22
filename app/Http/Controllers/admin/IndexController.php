<?php

namespace App\Http\Controllers\admin;

use App\Libs\TransClass;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Repositories\admin\IndexRepository;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     *
     * @return $value
     */
    public function index()
    {
        $nodes = session('admin.powers');

        if (empty($nodes)) {
            return redirect('login');
        }

        $powers = json_decode($nodes, 1);
        return view('admin.index', compact(['powers', 'count']));
    }

    /*
     *右侧主页
     */
    public function index_v1()
    {
        $operate = IndexRepository::index();
        return view('admin.index_v1', compact(['operate']));
    }

    public function ajaxData(Request $request)
    {
        $data = $request->except(['_token']);
        $res  = IndexRepository::ajaxData($data);
        return $res;
    }

    public function tableData(Request $request)
    {
        $data     = $request->except(['_token']);
        $resTable = IndexRepository::tableData($data);
        return $resTable;
    }

    /**
     * 调用系统信息
     */

    public static function systemSetting()
    {
        $list   = Redis::hgetall("SystemSetting");
        $result = [];
        foreach ($list as $value) {
            $result[] = json_decode($value, true);
        }
        return $result;
    }

    /**
     * 测试11
     * @param Request $request
     */
    public function test(Request $request)
    {
        $row = Article::where('article_status', 1)->get(['aid', 'small_pic'])->toarray();
        foreach ($row as $value) {
            $map  = ['aid' => $value['aid']];
            $data = json_decode($value['small_pic'], true);
            if (count($data) > 1) {
                foreach ($data as $v) {
                    if(!empty($v)) {
                        $pic = array_reverse(explode('/', $v));
                        $flag = $pic[1];
                        $filename = $pic[0];
                        if ($flag == 'resource') {
                            var_dump($v);
                           echo $old = "/data/www/faceBookAdmin/public".$v;
                           echo $new = "/data/www/faceBookAdmin/public/src/resource/old/".$filename;
                            exec("mv $old  $new");
                            echo PHP_EOL;
                        }
                    }else{
                        continue;
                    }
                }
            }else{
                continue;
            }
            break;
        }


    }
}