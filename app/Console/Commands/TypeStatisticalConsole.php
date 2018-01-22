<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\TypeLog;
use App\Models\Type;


class TypeStatisticalConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel:type_statistical';


    private $_typeLog;
    private $_type;
    private $_ctime;
    private $_cdate;
    private $key;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_typeLog = new TypeLog();
        $this->_type = new Type();
        $this->_cdate = date('Ymd',time());
        $this->_ctime = strtotime($this->_cdate);

    }


//redisKey:
//type_20170819_tid/
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
        $redisKey = "type_".$this->_cdate."_*";
        $typeLists = Redis::keys($redisKey);
        $condition = [];
        foreach($typeLists as $value){
            $this->key = $value;
            $condition['tid'] = explode('_',$value)[2];
            $condition['ctime'] = $this->_ctime;
            $typeId = $this->_typeLog->where($condition)->value('id');
            if($typeId){
                self::updateTypeLog($typeId);
            }else{
                self::insertTypeLog();
            }
        }
    }

    private function updateTypeLog($id)
    {
        $updateData = [];
        $redisData = Redis::hgetall($this->key);
        $updateData['clicks'] = empty($redisData['clicks']) ? 0 : $redisData['clicks'];
        $updateData['views'] = empty($redisData['views']) ? 0 : $redisData['views'];
        $updateData['share'] = empty($redisData['share']) ? 0 : $redisData['share'];
        $updateData['read_article'] = empty($redisData['read_article']) ? 0 : $redisData['read_article'];
        $updateData['inter_access'] = empty($redisData['inter_access']) ? 0 : $redisData['inter_access'];
        $updateData['histexternal_accessry'] = empty($redisData['histexternal_accessry']) ? 0 : $redisData['histexternal_accessry'];
        $total = $updateData['read_article'] + $updateData['inter_access'] + $updateData['histexternal_accessry'];
        if(intval($updateData['views']) !=0){
            $updateData['click_rate'] = round($updateData['inter_access'] / $updateData['views'],4) * 100;
        }else{
            $updateData['click_rate'] = 0;
        }
        if(intval($total) !=0){
            $updateData['share_rate'] = round($updateData['share'] / $total,4) * 100;
        }else{
            $updateData['share_rate'] = 0;
        }

        if(intval($updateData['read_article']) !=0){
            $updateData['guide_rate'] = round($updateData['histexternal_accessry'] / $updateData['read_article'],4) * 100;
        }else{
            $updateData['guide_rate'] = 0;
        }
        $this->_typeLog->where(['id'=>$id])->update($updateData);

    }

    private function insertTypeLog()
    {
        $insertData = [];
        $redisData = Redis::hgetall($this->key);
        $insertData['ctime'] = $this->_ctime;
        $insertData['tid'] = explode('_',$this->key)[2];
        $insertData['type_name'] = $this->_type->where(['tid'=>explode('_',$this->key)[2]])->value('type_name');
        $insertData['clicks'] = empty($redisData['clicks']) ? 0 : $redisData['clicks'];
        $insertData['views'] = empty($redisData['views']) ? 0 : $redisData['views'];
        $insertData['share'] = empty($redisData['share']) ? 0 : $redisData['share'];
        $insertData['read_article'] = empty($redisData['read_article']) ? 0 : $redisData['read_article'];
        $insertData['inter_access'] = empty($redisData['inter_access']) ? 0 : $redisData['inter_access'];
        $insertData['histexternal_accessry'] = empty($redisData['histexternal_accessry']) ? 0 : $redisData['histexternal_accessry'];
        $total = $insertData['read_article'] + $insertData['inter_access'] + $insertData['histexternal_accessry'];
        if(intval($insertData['views']) !=0){
            $insertData['click_rate'] = round($insertData['inter_access'] / $insertData['views'],4) * 100;
        }else{
            $insertData['click_rate'] = 0;
        }
        if(intval($total) !=0){
            $insertData['share_rate'] = round($insertData['share'] / $total,4) * 100;
        }else{
            $insertData['share_rate'] = 0;
        }

        if(intval($insertData['read_article']) !=0){
            $insertData['guide_rate'] = round($insertData['histexternal_accessry'] / $insertData['read_article'],4) * 100;
        }else{
            $insertData['guide_rate'] = 0;
        }
        $this->_typeLog->insertGetId($insertData);
    }
}
