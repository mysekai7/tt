<?php

//将字符串转化为时间
$yesterday = date('Ymd',strtotime('last day')); //显示格式20091208

//描述处理,只保留段落之间只存在一个br, 去html标签,实体化,替换多个br为1个
public function dealDesc($str)
{
    return preg_replace("/(<br \/>(\s)+)+/im", '<br />', nl2br(htmlspecialchars(strip_tags(trim( $str )), ENT_COMPAT)));    //保留小写单引号
}

//制作随机数
public function getRandomNum()
{
    $ot = NULL;
    for($j = 0;$j <= 5;$j++)        //随机数字的长度，本例随机数长度为6
    {
      srand((double)microtime()*1000000);
      $randname = rand(!$j ? 1: 0,9);    //产生随机数，不以0为第一个数，有些特殊的地方0开头被系统省略
      $ot .= $randname;
    }
    return $ot;
}

//截取字符串 按英文单词截取
function w_substr( $str , $slen)
{
        $str = del_html( $str );
        $str_len = strlen( $str );
        if ( $str_len < $startdd + 1 ) return "";
        $strw_arr = str_word_count( $str , 2 );
        $abreak = false;
        $startdd = 0;
        if ( $strw_arr )
        {
                foreach( $strw_arr as $key => $var )
                {
                        if ( $key > ( $startdd + $slen ) )
                        {
                                break;
                        }
                        $newend = $key;
                }
                if ( !$newend ) $newend = $str_len;
        }
        if ( ( $startdd + $slen ) >= $str_len ) $newend = $str_len;
        return substr( $str , $startdd , ( $newend - $startdd ) );
}

//把文件夹中所有文件读到一个数组中
$path = './words/';
if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            //echo "$file<br />";
            $k = (int)$file;
            $files[$k] = $file;
            ksort($files);
        }
    }
    closedir($handle);
}
print_r($files);


//处理关键词
function deal_words($str)
{
    preg_match_all ("/([a-z0-9]+)/im",$str, $out, PREG_SET_ORDER);
    foreach ($out as $k=>$v)
    {
        $searchword .= $v[0]." ";
    }
    $searchword = trim($searchword);
    $searchword = strtolower($searchword);
    return $searchword;
}

?>