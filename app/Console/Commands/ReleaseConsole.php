<?php

/**
 * 视频的自动发布
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use Illuminate\Support\Facades\Redis;

class ReleaseConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private $_article;
    private $_time;
    private $_aid;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_article = new Article();
        $this->_time    = time();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sendArticle();
    }
    /**
     * 查询 需要发布的视频 数据
     */
    public function sendArticle()
    {
        $where['article_status'] = '4';    //定时发布
        $list                    = $this->_article->where($where)->where('send_time', '<', $this->_time)->get(['aid','tid','cid','wid']);

        if ($list) {
            foreach ($list->toarray() as $value) {
                if (!empty($value)) {
                    $this->_aid = $value['aid'];
                    $this->updateArticle();  //更新视频 已经发布
                    $this->execSend($value); //发布该视频
                } else {
                    continue;
                }
            }
        } else {
            return false;
        }
    }
    /**
     * 文章定时发送
     */
    public function execSend($data)
    {
        //加入最新文章缓存 摸个栏目latestArticle_2_3
        $latestArticleKey = "latestArticle_" . $data['tid'] . "_" . $data['cid'];
        if(!empty($this->_aid)){
            $this->ListArticleIdPush($latestArticleKey, $this->_aid);
        }
    }
    /**
     * 更新数据为已经发布状态
     */
    public function updateArticle()
    {
        $data['article_status'] = 1;
        if ($this->_aid) {
            $this->_article->where(['aid' => $this->_aid])->update($data);
        }
    }

    /**
     * 加入list 列表
     * @param $ListKey
     * @param $key
     */
    private function ListArticleIdPush($ListKey, $key)
    {
        Redis::LRem($ListKey, 1000, $key);
        Redis::Lpush($ListKey, $key);
        Redis::expire($ListKey, 86400);
    }
}
