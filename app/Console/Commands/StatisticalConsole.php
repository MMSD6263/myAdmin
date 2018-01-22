<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ArticleLog;
use App\Models\Operate;

class StatisticalConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel:statistical';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $_articleLog;
    private $_operate;
    private $_ctime;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_articleLog = new ArticleLog();
        $this->_operate = new Operate();
        $this->_ctime = strtotime(date("Ymd",time()));

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
        $data = $this->_articleLog->where(['ctime'=>$this->_ctime])->first(
            array(
                DB::raw('SUM(share) as share'),
                DB::raw('SUM(read_article) as read_article'),
                DB::raw('SUM(inter_access) as inter_access'),
                DB::raw('SUM(histexternal_accessry) as histexternal_accessry'),
                DB::raw('SUM(views) as views'),
                DB::raw('SUM(clicks) as clicks'),
            )
        )->toArray();

        $total = $data['read_article'] + $data['inter_access'] + $data['histexternal_accessry'];
        if(intval($data['views']) !=0){
            $data['click_rate'] = round($data['inter_access'] / $data['views'],4) * 100;
        }else{
            $data['click_rate'] = '0';
        }

        if(intval($total) !=0){
            $data['share_rate'] = round($data['share'] / $total, 4) * 100;
        }else{
            $data['share_rate'] = 0;
        }

        if(intval($data['read_article']) !=0){
            $data['guide_rate'] = round($data['histexternal_accessry'] / $data['read_article'], 4) * 100;
        }else{
            $data['guide_rate'] = 0;
        }

        $data['ctime'] = $this->_ctime;

        $operateId = $this->_operate->where(['ctime'=>$this->_ctime])->value('id');

        if($operateId){
            self::updateOperate($data,$operateId);
        }else{
            self::insertOperate($data);
        }

    }

    private function updateOperate($data,$id)
    {
        $this->_operate->where(['id'=>$id])->update($data);
    }


    private function insertOperate($data)
    {
        $this->_operate->insertGetId($data);
    }


}
