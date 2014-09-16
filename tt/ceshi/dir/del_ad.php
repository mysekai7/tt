<?php
error_reporting(E_ALL);
$path = './';
rdir($path);
echo "Finish!\n";

function rdir($path)
{
	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if(is_file($path.$file))
				{
					if($file == 'del_ad.php')
						continue;
					del_ad($path.$file);
				}
				
				if(is_dir($path.$file))
				{
					$path_tmp = $path.$file.'/';
					rdir($path_tmp);
				}
			}
		}
		closedir($handle);
	}
}


function del_ad($file)
{
    $str = file_get_contents($file);
    $str = preg_replace('#<p class="ad">(.*?)</p>#is', '', $str);
    $str = preg_replace('#<div class="taC marginT10">(.*?)</div>#is', '', $str);
	file_put_contents($file, $str);
}


?>
