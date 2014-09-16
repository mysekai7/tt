<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

set_time_limit(0);

$file = './out_2010_01_14/have_list_word.txt';

//读文本
$handle = fopen ($file, "rb");
$content = "";
while (!feof($handle)) {
  $content .= fread($handle, 1024);
}
fclose($handle);

$aWords = array();
$aWords =explode("\n", $content);   //把56W词丢到一个数组中

//分词
$path = './words/';
$total = count($aWords);    //56W词 总数
$sWords = '';
$j = 0; //以1000为分割点标记
$z = 1;


//100词xml头部 中间内容 和结尾
$xml_top_100 = <<<END
<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="gss.xsl"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n
END;
$xml_mid_100 = '';
$xml_btm_100 = '</urlset>';

for($i=0; $i<$total; $i++)
{
    $xml_100_content = '';
    
    //从md5文件读词并生成xml文件
    $current_word = trim($aWords[$i]);  //当前词
    //$current_word_arr = file('./out/'.md5($current_word));

    $handle = fopen ('./out/'.md5($current_word), "rb");
    $contents = "";
    while (!feof($handle)) {
      $contents .= fread($handle, 1024);
    }
    fclose($handle);

    $current_word_arr = array();
    $current_word_arr =explode("\n", $contents);

    $category = str_replace(' ', '-', deal_words($current_word));
    
    //遍历100词数组 生成xml_mid_100内容
    if(is_array($current_word_arr) && count($current_word_arr) > 0) {

        //当前分类本身也加入到分类中
        $xml_mid_100 .= "<url>\n";
        $xml_mid_100 .= "\t<loc>http://c.tootoo.com/". $category ."/</loc>\n";
        $xml_mid_100 .= "\t<changefreq>weekly</changefreq>\n";
        $xml_mid_100 .= "</url>\n";

        //100词
        foreach($current_word_arr as $key => $val) {
            $kw = str_replace(' ', '-', deal_words($val));
            $xml_mid_100 .= "<url>\n";
            $xml_mid_100 .= "\t<loc>http://c.tootoo.com/". $category .'/'. $kw ."/</loc>\n";
            $xml_mid_100 .= "\t<changefreq>weekly</changefreq>\n";
            $xml_mid_100 .= "</url>\n";
        }
    }

    $j++;
    
    //当生成1000个xml文件时 开始打包
    if($j == 200 || $i == ($total-1)) {

        //生成把每个词对应的xml文件放到temp文件夹
        $xml_100_content = $xml_top_100 . $xml_mid_100 . $xml_btm_100;
        //file_put_contents('./temp/'.$current_word.'.xml', $xml_100_content);
        //file_put_contents('./sitemap/'. $z .'.xml', $xml_100_content);
        $filename = "./sitemap/". $z .".xml";
        $fp = fopen($filename, "w");
        fwrite($fp, $xml_100_content);
        fclose($fp);
        $xml_100_content = '';
        $xml_top_100 = '';
        $xml_mid_100 = '';
        $xml_btm_100 = '';
        
        //增加并写gz.xml 当为结尾时生成文件

        //$gz_cmd = "cd temp/ && tar -zcvf ../sitemap/{$z}.xml.tar.gz ./* && rm -rf ./*.xml";
        //system($gz_cmd);
        //system("rm -rf ./*");  //删除生成的xml文件
        
        echo '<br />###'. $z .'.xml';
        $z++;
        $j = 0;
        
        flush();
    }
}

echo '<br>********** over ******************';



//------------------------------------------------------------------------------
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
