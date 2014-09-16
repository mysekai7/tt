<?php

$dir = './txt';
if(!file_exists( $dir ))
	mkdir($dir, 0777);

//extra_products();
extra_companies();

//提取products文件夹
function extra_products()
{
	global $dir;
	//目录: products

	$path = './products';
	$save_dir = $dir.'/products';

	if(!file_exists($save_dir))
	{
		mkdir($save_dir, 0777);
	}

	$file_index = $path.'/index.html';
	if(!file_exists( $file_index )) die("index.html does not exists!\n");

	
	$cat1 = extra_cat_level_1($file_index);
	if($cat1 && is_array($cat1) && count($cat1)>0)
	{
		//保存一级类
		$str = implode("\n", $cat1);
		file_put_contents($save_dir.'/categories.txt', $str);
		echo "save main categories success \n\n";

		foreach($cat1 as $val)
		{
			$val = str_replace(' ', '_', dealwith_kw($val));
			$path1 = $path.'/'.strtolower($val);
			$file_index2 = $path1.'/index.html';
			if(!file_exists( $file_index2 ))
				continue;

			//抽取2级类
			$cat2 = extra_cat_level_2($file_index2);
			if($cat2 && is_array($cat2) && count($cat2)>0)
			{
				$dir_cat = $save_dir.'/'.strtolower($val);
				if(!file_exists($dir_cat))
					mkdir($dir_cat, 0777);

				file_put_contents($dir_cat.'/categories.txt', implode("\n", $cat2));//保存2级分类
				echo "save secondary categories success \n\n";

				foreach($cat2 as $k => $v)
				{
					$path2 = '.'.$k;
					$filename_index = $path2.'/index.html';
					
					if(!file_exists($filename_index))
					{
						continue;
					}
					$words = extra_words_from_page($filename_index, $path2);
					if($words && count($words)>0)
					{
						$filename = trim(preg_replace('/[^a-z0-9]+/i', '_', strtolower($v)), '_').'.txt';
						file_put_contents($dir_cat.'/'.$filename, implode("\n", $words));
						echo "save secondary words success \n\n";
					}
				}
			}
		}
	}
	echo "finish!\n";
}

//通用
function extra_companies()
{
	global $dir;

	$path = './_companies';
	$save_dir = $dir.'/_companies';

	if(!file_exists($save_dir))
	{
		mkdir($save_dir, 0777);
	}

	$file_index = $path.'/index.html';
	if(!file_exists( $file_index )) die("index.html does not exists!\n");

	
	$cat1 = extra_cat_level_1($file_index);
	if($cat1 && is_array($cat1) && count($cat1)>0)
	{
		//保存一级类
		$str = implode("\n", $cat1);
		file_put_contents($save_dir.'/categories.txt', $str);
		echo "save main categories success \n\n";

		foreach($cat1 as $key => $val)
		{
			//$val = str_replace(' ', '_', dealwith_kw($val));
			$path1 = '.'.$key.'/';
			//if(strpos($path1, '&'))
			{
				//$path1 = str_replace('&', '\&', $path1);
			}
			$file_index2 = $path1.'/index.html';
			if(!file_exists( $file_index2 ))
			{
				continue;
			}

			//抽取2级类
			//$cat2 = extra_cat_level_2($file_index2);
			//if($cat2 && is_array($cat2) && count($cat2)>0)
			{
				//$dir_cat = $save_dir.'/'.strtolower($val);
				//if(!file_exists($dir_cat))
					//mkdir($dir_cat, 0777);

				//file_put_contents($dir_cat.'/categories.txt', implode("\n", $cat2));//保存2级分类
				//echo "save secondary categories success \n\n";

				//foreach($cat2 as $k => $v)
				{
					//$path2 = '.'.$k;
					//$filename_index = $path2.'/index.html';
					
					//if(!file_exists($filename_index))
					{
						//continue;
					}
					$words = extra_words_from_page($file_index2, $path1);
					//break;
					if($words && count($words)>0)
					{
						$filename = trim(preg_replace('/[^a-z0-9]+/i', '_', strtolower($val)), '_').'.txt';
						file_put_contents($save_dir.'/'.$filename, implode("\n", $words));
						echo "save secondary words success \n\n";
					}
				}
			}
		}
	}
	echo "finish!\n";

}


//提取一级分类
function extra_cat_level_1($file)
{
	if(!$file && !file_exists($file))
		return false;

	$str = file_get_contents( $file );

	$pattern = '#<li>\s*?<h3><a href="(.*?)">(.*?)</a></h3>#is';

	$return = array();
	if(preg_match_all($pattern, $str, $rows))
	{
		foreach($rows[1] as $key => $val)
		{
			$return[$val] = $rows[2][$key];
		}
	}
	return $return;
}

//提取二级分类
function extra_cat_level_2($file)
{
	if(!$file && !file_exists($file))
		return false;

	$str = file_get_contents( $file );

	$pattern = '#<div id="leftNav".*?>(.*?)</div>#is';

	preg_match($pattern, $str, $row);

	//var_dump($row);

	$pattern = '#<dd.*?>\s*<a href="(.*?)">(.*?)</a>.*?</dd>#is';

	$return = array();
	if(preg_match_all($pattern, $row[0], $rows))
	{
		foreach($rows[1] as $key => $val)
		{
			$return[$val] = $rows[2][$key];
		}
	}
	return $return;
}

//提取分页中的词
function extra_words_from_page($file, $path)
{
	if(!$file && !file_exists($file))
		return false;

	$str = file_get_contents( $file );

	$pattern_page = '#<strong>page \d+ of (\d+)</strong>.*?<a href=\'(.*?)\d.html\'>\d</a>#is';//分页
	preg_match($pattern_page, $str, $r1);

	$total = intval($r1[1]);
	$prefix = $r1[2];
	
	$words = array();
	$words_tmp = array();
	if($total > 0)
	{
		for($i=1; $i<=$total; $i++)
		{
			$r2 = array();
			$rows = array();
			$file_list = $path.$prefix .$i. '.html';
			if(!file_exists($file_list))
				continue;
			$str_words = file_get_contents($file_list);
			preg_match('#<ul class="wrapClear">.*?</ul>#is', $str_words, $r2);

			preg_match_all('#<a.*?>(.*?)</a>#is', $r2[0], $rows);
			$words_tmp = array_merge($words_tmp, $rows[1]);
		}
	}
	if($words_tmp && count($words_tmp)>0)
	{
		$b = array_map('strtolower', $words_tmp);
		$words = array_unique($b);
	}

	return $words;
}

function dealwith_kw($str)
{
	if(!$str) return false;
	$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
	return trim($str);
}


?>