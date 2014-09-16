<?php

require_once('/home/www/nine/liuyan/tongji/global.php');
set_time_limit(0);
//抓取
$r = array();
foreach($C->RECORD_ENGINE as $key => $val)//循环引擎
{
    if(empty($val))
    {
        continue;
    }

    foreach($C->RECORD_URLS as $site => $urls)//循环各主站
    {
        if(is_array($urls) && count($urls) == 0)
        {
            continue;
        }

        foreach($urls as $k => $v)//循环要抓取的url
        {
            $url = str_replace('*****', $v, $val);
            $cnt = 0;
            $tmp = FALSE;
            while($cnt < 3 && ($tmp=@get_sources($url))===FALSE)
            {
                $cnt++;
                sleep(4);
            }
            if(!$tmp)
            {
                $r[$key][$site][$v] = 0;
            } else {
                $r[$key][$site][$v] = parse_html($tmp); //$r[引擎类型][主站][搜索url]
            }
            ob_flush();
            flush();
            echo $v." ======== ".$r[$key][$site][$v]."\n";

            sleep(3);//抓取间隔5s
        }
    }
}
//var_dump($r);

//save

$timestamp = strtotime('last day');
$filename = date('Ym', $timestamp).'.txt';
$today = getdate($timestamp);
if($r && is_array($r) && count($r)>0)
{
    foreach($r as $engine => $val)//循环引擎
    {
        foreach($val as $site => $urls)//循环主站
        {
            foreach($urls as $url => $num)//url循环
            {
                $dir = $C->DATA_DIR.'record/'.$engine.'/'.$site.'/'.md5($url).'/';
                if( !file_exists($dir) )
                {
                    mkdir_p($dir);
                }

                //判断是否已经记录过
                if(file_exists($dir.$filename))
                {
                    $res = file($dir.$filename);
                    if(count($res)>0)
                    {
                        $last = explode('#####', array_pop($res));
                        if($last[0] == $today['mday'])
                        {
                            continue;
                        }
                    }
                }
                $content = $today['mday'].'#####'.intval(str_replace(',', '', $num))."\n";

                //记录到文本
                $fp = fopen($dir.$filename, "a");
                if($fp)
                {
                    fwrite($fp, $content);
                    fclose($fp);
                }
                @chmod($dir.$filename, 0777);
            }
        }
    }
}


//获得结果
function parse_html($str)
{
    if(!$str)
    {
        return FALSE;
    }
    $pattern = '|<div id=resultStats>About\s*(.*?)\s*results<nobr>|is';
    preg_match($pattern, $str, $row);
    //var_dump($row);
    return $row[1] ? $row[1] : 0;
}
