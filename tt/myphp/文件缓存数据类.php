<?php
/**
 * 文件缓存类
 *
 * @copyright Copyright (c) 2006 - 2008 MYRIS.CN
 * @author 志凡 <dzjzmj@gmail.com>
 * @package cache
 * @version v0.1
 */
class FileCache {
	/**
	 * @var string $cachePath 缓存文件目录
	 * @access public
	 */
	public $cachePath = './';

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


//例子
$cache = new FileCache();
$data = $cache->get('yourkey');//yourkey是你为每一个要缓存的数据定义的缓存名字
if ($data===false) {
    $data = '从数据库取出的数据或很复杂很耗时的弄出来的数据';
    $cache->set('yourkey',$data,3600);//缓存3600秒
}

// use your $data
/*
看代码

例子解释

一开始你从缓存中取数据(get)如果数据有缓存就直接使用缓存中的数据了。

如果缓存过期或没有，那重新取数据（数据库或其它），然后保存到缓存中。再使用你的数据。


我们可以这样想，这个页面第一次被访问的时候，是取不到缓存数据的所以$data是false,这时按正常逻辑取数据。

过几份种再访问，这时你从缓存中可以取到数据，直接使用，跳过逻辑取数据。这样就省了很多时间哟。

过了1小时几份钟你再访问，这时候缓存过期了，返回false,那么重新取一次再缓存。

这样一直反复。可以看取，1小时才会逻辑取一次数据。这样就达到了缓存的目的。

明白否？ 不明白留言！

FileSystem在这里
http://coderhome.net/code/index.php?id=147
*/
?>