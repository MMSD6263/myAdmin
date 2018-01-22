<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\ColumnLog;
use App\Models\TypeColumn;

class ColumnStatisticalConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel:column_statistical';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $_columnLog;
    private $_typeColumn;
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
        $this->_columnLog = new ColumnLog();
        $this->_typeColumn = new TypeColumn();
        $this->_cdate = date('Ymd',time());
        $this->_ctime = strtotime($this->_cdate);

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
        $redisKey = "column_".$this->_cdate.'_*';
        $columnLists = Redis::keys($redisKey);
        $condition = [];
        foreach($columnLists as $value){
            $this->key = $value;
            $arr = explode('_',$value);
            $condition['tid'] = $arr[2];
            $condition['cid'] = $arr[3];
            $condition['ctime'] = $this->_ctime;
            $columnId = $this->_columnLog->where($condition)->value('id');
            if($columnId){
                self::updateColumnLog($columnId);
            }else{
                self::insertColumnLog();
            }
        }
    }

    private function updateColumnLog($columnId)
    {
        $updateData = [];
        $arr = explode('_',$this->key);
        $redisData = Redis::hgetall($this->key);
        $updateData['clicks'] = empty($redisData['clicks']) ? 0 : $redisData['clicks'];
        $updateData['views'] = empty($redisData['views']) ? 0 : $redisData['views'];
        $updateData['share'] = empty($redisData['share']) ? 0 : $redisData['share'];
        $updateData['inter_access'] = empty($redisData['inter_access']) ? 0 : $redisData['inter_access'];
        $updateData['histexternal_accessry'] = empty($redisData['histexternal_accessry']) ? 0 : $redisData['histexternal_accessry'];
        $updateData['read_article'] = empty($redisData['read_article']) ? 0 : $redisData['read_article'];
        $total = $updateData['inter_access'] + $updateData['histexternal_accessry'] + $updateData['read_article'];
        if(intval($updateData['views']) !=0){
            $updateData['click_rate'] = round($updateData['inter_access'] / $updateData['views'],4) * 100;
        }else{
            $updateData['click_rate'] = 0;
        }
        if(intval($total)!=0){
            $updateData['share_rate'] = round($updateData['share'] / $total,4) * 100;
        }else{
            $updateData['share_rate'] = 0;
        }
        if(intval($updateData['read_article']) !=0){
            $updateData['guide_rate'] = round($updateData['histexternal_accessry'] / $updateData['read_article'],4) * 100;
        }else{
            $updateData['guide_rate'] = 0;
        }
        $this->_columnLog->where(['id'=>$columnId])->update($updateData);

    }

    private function insertColumnLog()
    {
        $insertData = [];
        $arr = explode('_',$this->key);
        $redisData = Redis::hgetall($this->key);
        $insertData['clicks'] = empty($redisData['clicks']) ? 0 : $redisData['clicks'];
        $insertData['views'] = empty($redisData['views']) ? 0 : $redisData['views'];
        $insertData['share'] = empty($redisData['share']) ? 0 : $redisData['share'];
        $insertData['inter_access'] = empty($redisData['inter_access']) ? 0 : $redisData['inter_access'];
        $insertData['histexternal_accessry'] = empty($redisData['histexternal_accessry']) ? 0 : $redisData['histexternal_accessry'];
        $insertData['read_article'] = empty($redisData['read_article']) ? 0 : $redisData['read_article'];
        $insertData['ctime'] = $this->_ctime;
        $insertData['tid'] = $arr[2];
        $insertData['cid'] = $arr[3];
        $insertData['type_name'] = $this->_typeColumn->where(['tid'=>$arr[2]])->value('type_name');
        $insertData['column_name'] = $this->_typeColumn->where(['cid'=>$arr[3]])->value('column_name');
        $total = $insertData['inter_access'] + $insertData['histexternal_accessry'] + $insertData['read_article'];
        if(intval($insertData['views']) !=0){
            $insertData['click_rate'] = round($insertData['inter_access'] / $insertData['views'],4) * 100;
        }else{
            $insertData['click_rate'] = 0;
        }
        if(intval($total)!=0){
            $insertData['share_rate'] = round($insertData['share'] / $total,4) * 100;
        }else{
            $insertData['share_rate'] = 0;
        }
        if(intval($insertData['read_article']) !=0){
            $insertData['guide_rate'] = round($insertData['histexternal_accessry'] / $insertData['read_article'],4) * 100;
        }else{
            $insertData['guide_rate'] = 0;
        }
        $this->_columnLog->insertGetId($insertData);

    }


}
