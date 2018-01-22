<?php

namespace App\Repositories\commonclass;


/**
 * 用户仓库
 */
class whereserach
{
    /*
     * 字段参数查询
     */
    public static function whereNames($table, $field, $content, $flag = false)
    {

        if ($field == 'nickname' || $field == 'user.nickname' || $field == 'users.nickname') {
            $content = base64_encode($content);
        }
        if (!empty($content)) {
            if ($flag) {
                $table = $table->where($field, 'like', '%' . trim($content) . '%');
            } else {
                $table = $table->where($field, trim($content));
            }
            return $table;
        }
    }

    /*
     * 状态查询
     */
    public static function whereStatus($table, $field, $content)
    {
        if (isset($content) && $content != '') {
            $table = $table->where($field, $content);
            return $table;
        }
    }


    /*
     * id查询
     */
    public static function whereid($table, $field, $content)
    {
        if (isset($content) && $content != '') {
            $table = $table->where($field, $content);
            return $table;
        }
    }





    /**日期查询
     * @param $table
     * @param $field
     * @param string $start
     * @param string $end
     * @return mixed
     */
    public static function wherecdate($table, $field, $start = '', $end = '')
    {
        if (!empty($start) && empty($end)) {
            $table = $table->where($field, '>=', strtotime($start . "00:00:00"));
            return $table;
        }
        if (!empty($end) && empty($start)) {
            $table = $table->where($field, '<=', strtotime($end . "23:59:59"));
            return $table;

        }
        if (!empty($start) && !empty($end)) {
            $table = $table->whereBetween($field, array(strtotime($start . "00:00:00"), strtotime($end . "23:59:59")));
            return $table;
        }
    }

    public static function createSystemLog($before = array(), $after = array(), $moble)
    {
        $diffarray = array_merge(array_diff($before, array_intersect($after, $before)), array_diff($after, array_intersect($after, $before)));
        $systemlog = new Systemlogs();

        $data['username']      = session('admin.realname');
        $data['uid']           = session('admin.realname');
        $data['diff']          = json_encode($diffarray);
        $data['model']         = $moble;
        $data['after_content'] = json_encode($after);
        $data['content']       = json_encode($before);
        $data['iplocation']    = self::getIP();
        $data['ctime']         = time();
        if ($systemlog->insert($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取ip
     * @return string
     */
    public static function getIP()
    {
        $realip = '';
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

    public static function username()
    {
        return $nodes = session('admin.username');
    }

    public static function getProvince()
    {
        $initProvince = [];
        $province     = array(1 => '北京', 2 => '天津', 3 => '河北', 4 => '山西', 5 => '内蒙古', 6 => '辽宁', 7 => '吉林', 8 => '黑龙江', 9 => '上海', 10 => '江苏', 11 => '浙江', 12 => '安徽', 13 => '福建', 14 => '江西', 15 => '山东', 16 => '河南', 17 => '湖北', 18 => '湖南', 19 => '广东', 20 => '广西', 21 => '海南', 22 => '重庆', 23 => '四川', 24 => '贵州', 25 => '云南', 26 => '西藏', 27 => '陕西', 28 => '甘肃', 29 => '青海', 30 => '宁夏', 31 => '新疆', 32 => '香港', 33 => '澳门', 34 => '台湾');
        foreach ($province as $key => $value) {
            $initProvince[$key]['provinceId']    = $value;
            $initProvince[$key]['provinceValue'] = $value;
        }
        $initProvince1 = array('0' => array('provinceId' => '', 'provinceValue' => '--省份--', 'selected' => 'true'));
        $province      = array_merge($initProvince1, $initProvince);
       return  json_encode($province);
    }
}