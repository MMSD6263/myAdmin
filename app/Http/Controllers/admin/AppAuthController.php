<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

/**
 * 授权函数
 * Class AppAuthController
 * @package App\Http\Controllers\admin
 */
class AppAuthController extends Controller
{
    private $_appId;
    private $_appSecret;
    private $_token;

    public function __construct($data = [])
    {
        $this->_appId     = $data['appid'];
        $this->_appSecret = $data['app_secret'];
    }

    /**
     * 获取token
     */
    public function getToken()
    {
        $tokenKey = $this->_appId . "|token";
        if (Redis::exists($tokenKey)) {
            $this->_token = Redis::get($tokenKey);
        } else {
            $url    = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->_appId}&secret={$this->_appSecret}";
            $result = curl_get($url);
            if (strstr($result, 'access_token')) {
                $this->_token = json_decode($result, true)['access_token'];
                Redis::set($tokenKey, $this->_token);
                Redis::expire($tokenKey, 3600);
            }
        }
        return $this->_token;
    }
}