<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

/**
 * 定时清除失效文件
 * 1、delete_image_dir
 * Class ClearFileConsole
 * @package App\Console\Commands
 */
class ClearFileConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private $_path;
    private $_thumbpath;
    private $_key;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_path = "/data/www/faceBookAdmin/public/src/resource/";
        $this->_thumbpath = "/data/www/faceBookAdmin/public/src/resource/smallpic/";
        $this->_key  = 'delete_image_dir';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->main();  //清除抓取文章redis key
//        $this->mvFile();   //移动文件
    }

    /**
     * 调用删除规则主函数
     */
    public function main()
    {
        $list = Redis::Lrange($this->_key, 0, -1);

        if (!(count($list) > 0)) {
            return;
        }

        foreach ($list as $value) {
            if (!empty($value)) {
                $path_branch = $this->makePath($value);
                echo $path = $this->_path . $path_branch.$value;
                echo $thumbpath = $this->_thumbpath.$path_branch.$value;
                echo PHP_EOL;
                if (is_dir($path) && is_dir($thumbpath)) {
                    if ($this->rulesVerify($path) && $this->rulesVerify($thumbpath)) {        //验证文件夹规则
                        $this->delDirAndFile($path);        //删除文件夹
                        $this->delDirAndFile($thumbpath);        //删除文件夹
                        $this->delRedisList($this->_key, $value);  //清除redis key
                    } else {
                        continue;
                    }
                } else {
                    $this->delRedisList($this->_key, $value);  //清除redis key
                    Redis::Lpush('del_file_error', time(), $value);
                    continue;
                }
            } else {
                continue;
            }
        }
    }

    /**
     * 处理删除文件
     */
    public function makePath($time)
    {
        $year = date('Y',$time);
        $month = date('m',$time);
        $day = date('d',$time);
        return $year.'/'.$month.'/'.$day.'/';
    }

    /**
     * 删除服务器资源文件【抓取的文件】
     * @param $path
     * @param bool $delDir
     * @return bool
     */
    public function delDirAndFile($dir, $delDir = FALSE)
    {
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->delDirAndFile($fullpath);      //递归删除
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 规则验证
     */
    public function rulesVerify($path)
    {
        echo $path = trim($path, "/");
        $files = array_reverse(explode('/', $path))[0];
        if ($files == 'resource') {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    /**
     * 删除redis list
     */
    private function delRedisList($ListKey, $key)
    {
        Redis::LRem($ListKey, 1000, $key);
    }


    /**
     * 转移文件
     */
    public function mvFile()
    {
        $row = Article::where('article_status', 1)->get(['aid', 'small_pic'])->toarray();
        foreach ($row as $value) {
            $map = ['aid' => $value['aid']];
            var_dump($map);
            $data = json_decode($value['small_pic'], true);
            $save = [];
            if (count($data) > 1) {
                foreach ($data as $v) {
                    if (!empty($v)) {
                        $pic      = array_reverse(explode('/', $v));
                        $flag     = $pic[1];
                        $filename = $pic[0];
                        if ($flag == 'resource') {
                            var_dump($v);
                            echo $old = "/data/www/faceBookAdmin/public" . $v;
                            echo $new = "/data/www/faceBookAdmin/public/src/resource/smallpic/old/";
                            exec("mv $old $new");
                            echo PHP_EOL;
                            $v = "/src/resource/smallpic/old/" . $filename;
                        }
                    }
                    $save[] = $v;
                }

                $dataSave        = json_encode($save);
                $sa['small_pic'] = $dataSave;
                Article::where('aid', $value['aid'])->update($sa);

            } else {
                continue;
            }
            break;
        }
    }
}
