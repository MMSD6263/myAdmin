<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SqlListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }
    /**
     * Handle the event.
     *
     * @param  QueryExecuted  $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        $username = session('admin.username');
        $sql = str_replace("?", "'%s'", $event->sql);
        $log = vsprintf($sql, $event->bindings);
        $log = $username.'[' . date('Y-m-d H:i:s') . '] '.$log ."\r\n";
        $filePath = storage_path('logs/'.date('Ymd').'sql.log');

        if(!file_exists($filePath)){
            $tmp = fopen($filePath,'w+');    //创建文件
            chmod($filePath,0777);    //可读写
            fclose($tmp);
        }

        file_put_contents($filePath, $log, FILE_APPEND);
    }
}
