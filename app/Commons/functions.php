<?php
/**
 * Created by PhpStorm.
 * User: zxf
 * Date: 2017/5/25 0025
 * Time: 下午 9:20
 */
function curl_get($url)
{
    //初始化curl
    $ch = curl_init();
    //设置curl选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    //执行
    $output = curl_exec($ch);
    //关闭
    curl_close($ch);
    return $output;
}

function http_request($url, $data = null)
{

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

function message($success = '', $message = '')
{
    return json_encode(['success' => $success, 'message' => $message]);
}

function aa($request){
    echo '<pre/>';
    print_r($request);
    die();
}

function getTime(){
    echo date('Y-m-d',strtotime('today'));
}

//最近7天
function getRecentSevenDays(){
    $time = strtotime('today') - 7*86400;
    echo date('Y-m-d',$time);
}

//最近30天
function getRecentThirtyDays(){
    $time = strtotime('today') - 30*86400;
    echo date('Y-m-d',$time);
}

//当月
function getCurrentMonth(){
    $date = date('Y-m-01', strtotime(date("Y-m-d")));
    echo $date;
}

//上月
function getLastMonth(){
    $start = date('Y-m-01', strtotime('-1 month'));
    $end = date('Y-m-t', strtotime('-1 month'));
    $res = array($start,$end);
    return ($res);
}


function mkdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) return TRUE;
    if (!mkdirs(dirname($dir), $mode)) return FALSE;
    return @mkdir($dir, $mode);
}
