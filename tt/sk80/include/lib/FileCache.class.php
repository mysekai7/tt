<?php
/**
 * 文件缓存类
 *
 * @copyright Copyright (c) 2006 - 2008 MYRIS.CN
 * @author 志凡 <dzjzmj@gmail.com>
 * @package cache
 * @version v0.1
 */


/*
$cache = new FileCache();
$data = $cache->get('yourkey');//yourkey是你为每一个要缓存的数据定义的缓存名字
if ($data===false) {
    $data = '从数据库取出的数据或很复杂很耗时的弄出来的数据';
    $cache->set('yourkey',$data,3600);//缓存3600秒
}

//tags example
$cache = new FileCache();
$dataTags = $cache->get('tags');//yourkey是你为每一个要缓存的数据定义的缓存名字
if ($dataTags===false) {
 $sql = "select a.*, count(b.id) as count from gb_tag as a left join gb_tagmap as b on (a.id = b.tid) group by a.id order by count(b.id) desc";
 $dataTags = $oMySQL->get_results($sql);
 $cache->set('tags',$dataTags,3600);//缓存3600秒
}
var_dump($dataTags);
*/

class FileCache {
	/**
	 * @var string $cachePath 缓存文件目录
	 * @access public
	 */
	public $cachePath = './cache/';

	/**
	 * 构造函数
	 * @param string $path 缓存文件目录
	 */
	function __construct($path = NULL) {
		if ($path) {
			$this->cachePath = $path;
		}


    }

	/**
	 * 析构函数
	 */
	function __destruct() {
    	//nothing
    }

	/**
	 * 在cache中设置键为$key的项的值，如果该项不存在，则新建一个项
	 * @param string $key 键值
	 * @param mix $var 值
	 * @param int $expire 到期秒数
	 * @param int $flag 标志位
	 * @return bool 如果成功则返回 TRUE，失败则返回 FALSE。
	 * @access public
	 */
    public function set($key, $var, $expire = 36000, $flag = 0) {
		$value = serialize($var);
		$timeout = time() + $expire;
		$result = safe_file_put_contents($this->cachePath . urlencode($key) .'.cache',
				$timeout . '<<%-==-%>>' . $value);
		return $result;
	}

	/**
	 * 在cache中获取键为$key的项的值
	 * @param string $key 键值
	 * @return string 如果该项不存在，则返回false
	 * @access public
	 */
    public function get($key) {
		$file = $this->cachePath . urlencode($key) .'.cache';
		if (file_exists($file)) {
			$content = safe_file_get_contents($file);
			if ($content===false) {
				return false;
			}
			$tmp = explode('<<%-==-%>>', $content);
			$timeout = $tmp[0];
			$value = $tmp[1];
			if (time()>$timeout) {
				$result = false;
			} else {
				$result = unserialize($value);
			}
		} else {
			$result = false;
		}
		return $result;
	}

	/**
	 * 清空cache中所有项
	 * @return 如果成功则返回 TRUE，失败则返回 FALSE。
	 * @access public
	 */
    public function flush() {
		$fileList = FileSystem::ls($this->cachePath,array(),'asc',true);
		return FileSystem::rm($fileList);
	}

	/**
	 * 删除在cache中键为$key的项的值
	 * @param string $key 键值
	 * @return 如果成功则返回 TRUE，失败则返回 FALSE。
	 * @access public
	 */
    public function delete($key) {
		return FileSystem::rm($this->cachePath . $key .'.cache');
	}
}

if (!function_exists('safe_file_put_contents')) {
	function safe_file_put_contents($filename, $content)
	{
		$fp = fopen($filename, 'wb');
		if ($fp) {
			flock($fp, LOCK_EX);
			fwrite($fp, $content);
			flock($fp, LOCK_UN);
			fclose($fp);
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('safe_file_get_contents')) {
	function safe_file_get_contents($filename)
	{
		$fp = fopen($filename, 'rb');
		if ($fp) {
			flock($fp, LOCK_SH);
			clearstatcache();
			$filesize = filesize($filename);
			if ($filesize > 0) {
				$data = fread($fp, $filesize);
			}
			flock($fp, LOCK_UN);
			fclose($fp);
			return $data;
		} else {
			return false;
		}
	}
}



?>