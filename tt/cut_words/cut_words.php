<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$file = 'words.txt';

//读文本
$handle = fopen ($file, "rb");
$content = "";
while (!feof($handle)) {
  $content .= fread($handle, 1024);
}
fclose($handle);

$aWords = array();
$aWords =explode("\n", $content);

//分词
$path = './words/';
$total = count($aWords);
$sWords = '';
$j = 0;
$z = 1;
$ext = '.txt';
for($i=0; $i<$total; $i++)
{
    $sWords .= trim($aWords[$i]) . "\n";
    $j++;
    if($j == 1000 || $i == ($total-1)) {
        $name = $path.$z.$ext;
        $sWords = trim($sWords);
        file_put_contents($name, $sWords);
        $sWords = '';
        $z++;
        $j = 0;

        echo $name.'<br />';
    }
}



?>
