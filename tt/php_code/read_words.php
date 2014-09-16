<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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
?>
