<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Models\ArticleLog;
use App\Models\Article;

class ArticleStatisticalConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel:article_statistical';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $_articleLog;
    private $_article;
    private $_ctime;
    private $_cdate;
    private $key;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_article    = new Article();
        $this->_articleLog = new ArticleLog();
        $this->_cdate      = date('Ymd', time());
        $this->_ctime      = strtotime($this->_cdate);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        $this->main();
    }

    public function main()
    {
        $redisKey = Redis::keys('articleStatistics_' . $this->_cdate . '_*');
        foreach ($redisKey as $value) {
            $this->key    = $value;
            $arr_article  = explode('_', $value);
            $condition    = [
                'cid'        => $arr_article[3],
                'article_id' => $arr_article[5],
//                'wid'        => $arr_article[4],
                'ctime'      => $this->_ctime,
                'tid'        => $arr_article[2],
            ];
            var_dump($condition);
            $articleLogId = $this->_articleLog->where($condition)->first(['id']);
            var_dump($articleLogId);
            if (!empty($articleLogId)) {
                self::updateArticleLog($articleLogId->id);
            } else {
                self::insertArticleLog();
            }
        }
    }

    private function updateArticleLog($articleLogId)
    {
        $updateData           = [];
        $redisData            = Redis::hgetall($this->key);
        $updateData['clicks'] = empty($redisData['clicks']) ? 0 : $redisData['clicks'];
        $updateData['views']  = empty($redisData['views']) ? 0: $redisData['views'];
        $updateData['share']  = empty($redisData['share']) ? 0 : $redisData['share'];

        $updateData['read_article']          = empty($redisData['read_article']) ? 0 : $redisData['read_article'];
        $updateData['inter_access']          = empty($redisData['inter_access']) ? 0 : $redisData['inter_access'];
        $updateData['histexternal_accessry'] = empty($redisData['histexternal_accessry']) ? 0 : $redisData['histexternal_accessry'];
        $total                               = $updateData['read_article'] + $updateData['inter_access'] + $updateData['histexternal_accessry'];

        if (intval($updateData['views']) != 0) {
            $updateData['click_rate'] = round($updateData['inter_access'] / $updateData['views'], 4) * 100;
        } else {
            $updateData['click_rate'] = 0;
        }
        if (intval($total) != 0) {
            $updateData['share_rate'] = round($updateData['share'] / $total, 4) * 100;
        } else {
            $updateData['share_rate'] = 0;
        }

        if (intval($updateData['read_article']) !=0) {
            $updateData['guide_rate'] = round($updateData['histexternal_accessry'] / $updateData['read_article'], 4) * 100;
        } else {
            $updateData['guide_rate'] = 0;
        }

        $this->_articleLog->where(['id' => $articleLogId])->update($updateData);
    }

    private function insertArticleLog()
    {
        $insertData = [];
        $redisData  = Redis::hgetall($this->key);
        $arr        = explode('_', $this->key);

        $insertData['clicks']                = empty($redisData['clicks']) ? 0 : $redisData['clicks'];
        $insertData['views']                 = empty($redisData['views']) ? 0 : $redisData['views'];
        $insertData['share']                 = empty($redisData['share']) ? 0 : $redisData['share'];
        $insertData['inter_access']          = empty($redisData['inter_access']) ? 0 : $redisData['inter_access'];
        $insertData['read_article']          = empty($redisData['read_article']) ? 0 : $redisData['read_article'];
        $insertData['histexternal_accessry'] = empty($redisData['histexternal_accessry']) ? 0 : $redisData['histexternal_accessry'];
        $insertData['tid']                   = $arr[2];
        $insertData['cid']                   = $arr[3];
        $insertData['article_id']            = $arr[5];
        $insertData['title']                 = $this->_article->where(['aid' => $insertData['article_id']])->value('title');
        $insertData['type_name']             = $this->_article->where(['aid' => $insertData['article_id']])->value('type_name');
        $insertData['ctime']                 = $this->_ctime;
        $insertData['wid']                   = empty($redisData['wid']) ? 0 : $redisData['wid'];
        $total                               = $insertData['read_article'] + $insertData['inter_access'] + $insertData['histexternal_accessry'];


        if (intval($insertData['views']) != 0) {
            $insertData['click_rate'] = round($insertData['inter_access'] / $insertData['views'], 4) * 100;
        } else {
            $insertData['click_rate'] = 0;
        }
        if (intval($total) != 0) {
            $insertData['share_rate'] = round($insertData['share'] / $total, 4) * 100;
        } else {
            $insertData['share_rate'] = 0;
        }

        if (intval($insertData['read_article']) !=0) {
            $insertData['guide_rate'] = round($insertData['histexternal_accessry'] / $insertData['read_article'], 4) * 100;
        } else {
            $insertData['guide_rate'] = 0;
        }

        $this->_articleLog->insertGetId($insertData);
    }
}
