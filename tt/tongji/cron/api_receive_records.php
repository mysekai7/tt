<?php
//跨域接受域名收录信息

require_once('../global.php');
set_time_limit(0);


$time = isset($_POST['time']) ? $_POST['time'] : '';
$data = isset($_POST['data']) ? $_POST['data'] : '';


if(!$time || !$data)
{
    die('ERROR');
}

$timestamp = intval( $time );
$r = unserialize(base64_decode( $data ));

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
                //$dir = 'record/'.$engine.'/'.$site.'/'.md5($url).'/';
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

echo "success";