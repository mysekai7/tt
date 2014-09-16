<?php
$data["username"] = 13812345678;
$data["password"] = "password123";
$data["sendto"] = 13512345678;
$data["message"] = "这是一条测试短信！";

$curl = new Curl_Class();
$result = @$curl->post("http://sms.api.bz/fetion.php", $data);
echo $result; //返回信息默认为UTF-8编码的汉字，如果你的页面编码为gb2312，请使用下行语句输出返回信息。
//echo iconv("UTF-8", "GBK", $result);

//curl类
class Curl_Class
{
    function Curl_Class()
    {
        return true;
    }

    function execute($method, $url, $fields = '', $userAgent = '', $httpHeaders = '', $username = '', $password = '')
    {
        $ch = Curl_Class::create();
        if (false === $ch)
        {
            return false;
        }

        if (is_string($url) && strlen($url))
        {
            $ret = curl_setopt($ch, CURLOPT_URL, $url);
        }
        else
        {
            return false;
        }
        //是否显示头部信息
        curl_setopt($ch, CURLOPT_HEADER, false);
        //
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($username != '')
        {
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        }

        $method = strtolower($method);
        if ('post' == $method)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            if (is_array($fields))
            {
                $sets = array();
                foreach ($fields AS $key => $val)
                {
                    $sets[] = $key . '=' . urlencode($val);
                }
                $fields = implode('&',$sets);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        else if ('put' == $method)
        {
            curl_setopt($ch, CURLOPT_PUT, true);
        }

        //curl_setopt($ch, CURLOPT_PROGRESS, true);
        //curl_setopt($ch, CURLOPT_VERBOSE, true);
        //curl_setopt($ch, CURLOPT_MUTE, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);//设置curl超时秒数

        if (strlen($userAgent))
        {
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        }

        if (is_array($httpHeaders))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }

        $ret = curl_exec($ch);

        if (curl_errno($ch))
        {
            curl_close($ch);
            return array(curl_error($ch), curl_errno($ch));
        }
        else
        {
            curl_close($ch);
            if (!is_string($ret) || !strlen($ret))
            {
                return false;
            }
            return $ret;
        }
    }

    function post($url, $fields, $userAgent = '', $httpHeaders = '', $username = '', $password = '')
    {
        $ret = Curl_Class::execute('POST', $url, $fields, $userAgent, $httpHeaders, $username, $password);
        if (false === $ret)
        {
            return false;
        }

        if (is_array($ret))
        {
            return false;
        }
        return $ret;
    }

    function get($url, $userAgent = '', $httpHeaders = '', $username = '', $password = '')
    {
        $ret = Curl_Class::execute('GET', $url, '', $userAgent, $httpHeaders, $username, $password);
        if (false === $ret)
        {
            return false;
        }

        if (is_array($ret))
        {
            return false;
        }
        return $ret;
    }

    function create()
    {
        $ch = null;
        if (!function_exists('curl_init'))
        {
            return false;
        }
        $ch = curl_init();
        if (!is_resource($ch))
        {
            return false;
        }
        return $ch;
    }

}
?>