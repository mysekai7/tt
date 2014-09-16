<?php
/**
* IP 地理位置查询类
* 增加分块查询算法
*
* @author 马秉尧
* @version 1.5
* @copyright 2005 CoolCode.CN
* @modified by david (blog: blog.iyi.cn , bbs: www.fenghuanghua.com)
*/

class IpLocation
 {
	/**
     * QQWry.Dat 文件指针
     *
     * @var resource
     */
	var $fp;

	/**
     * 第一条 IP 记录的偏移地址
     *
     * @var int
     */
	var $firstip;

	/**
     * 最后一条 IP 记录的偏移地址
     *
     * @var int
     */
	var $lastip;

	/**
     * IP 记录的总条数（不包含版本信息记录）
     *
     * @var int
     */
	var $totalip;


	/**
     * 返回读取的长整型数
     *
     * @access private
     * @return int
     */
	function getlong() {
		// 将读取的 little-endian 编码的 4 个字节转化为长整型数
		$result = unpack('Vlong', fread($this->fp, 4));
		return $result['long'];
	}

	/**
     * 返回读取的 3 个字节的长整型数
     *
     * @access private
     * @return int
     */
	function getlong3() {
		// 将读取的 little-endian 编码的 3 个字节转化为长整型数
		$result = unpack('Vlong', fread($this->fp, 3).chr(0));
		return $result['long'];
	}

	/**
     * 返回压缩后可进行比较的 IP 地址
     *
     * @access private
     * @param string $ip
     * @return string
     */
	function packip($ip) {
		// 将 IP 地址转化为长整型数，如果在 PHP5 中， IP 地址错误，则返回 False，
		// 这时 intval 将 Flase 转化为整数 -1，之后压缩成 big-endian 编码的字符串
		return pack('N', intval($ip));
	}

	/**
     * 返回读取的字符串
     *
     * @access private
     * @param string $data
     * @return string
     */
	function getstring($data = "") {
		while (ord($char = fread($this->fp, 1)) > 0) {        // 字符串按照 C 格式保存，以 \0 结束
			$data .= $char;             // 将读取的字符连接到给定字符串之后
		}
		return $data;
	}

	/**
     * 返回地区信息
     *
     * @access private
     * @return string
     */
	function getarea() {
		$byte = fread($this->fp, 1);    // 标志字节
		switch (ord($byte)) {
			case 0:                     // 没有区域信息
				$area = "";
			break;

			case 1:
			case 2:                     // 标志字节为 1 或 2，表示区域信息被重定向
				fseek($this->fp, $this->getlong3());
				$area = $this->getstring();
			break;

			default:                    // 否则，表示区域信息没有被重定向
				$area = $this->getstring($byte);
			break;
		}
		return $area;
	}

	/**
     * 将索引分成指定的块
     *
     * @access public
     * @param int $count
     * @return array
     */
	function separate($count) {
		if (!$this->fp)
		{
			return null;	// 如果数据文件没有被正确打开，则直接返回空
		}

		$l = 0;                         // 搜索的下边界
		$u = $this->totalip;            // 搜索的上边界
		$step = floor($u / $count);
		for($i = 0; $i < $count; $i++) {
			fseek($this->fp, $this->firstip + $i * $step * 7);
			$separator[$i] = strrev(fread($this->fp, 4));     // 获取中间记录的开始 IP 地址
			// strrev 函数在这里的作用是将 little-endian 的压缩 IP 地址转化为 big-endian 的格式
		}

		return $separator;
	}

	/**
     * 根据所给 IP 地址或域名返回所在地区信息
     *
     * @access public
     * @param string $ip, array $separator
     * @return array
     */
	function getlocation($ip, $separator = 0) {
		if (!$this->fp) return null;            // 如果数据文件没有被正确打开，则直接返回空
		$location['ip'] = ip2long($ip);
		$ip = $this->packip($location['ip']);   // 将输入的 IP 地址转化为可比较的 IP 地址
		// 不合法的 IP 地址会被转化为 255.255.255.255


		$l = 0;                         // 搜索的下边界
		$u = $this->totalip;            // 搜索的上边界
		$findip = $this->lastip;        // 如果没有找到就返回最后一条 IP 记录（QQWry.Dat 的版本信息）
		if($separator) {
			$count = count($separator);
			$step = floor($u/$count);
			for($i=0; $i<$count-1; $j = floor(($count + $i) / 2)) {	// 二分查找所在区间
				if($i>1000)break;
				if($ip<$separator[$j]) {
					if($ip>$separator[$j-1]) {
						$l = 1;
						$i = $j;
						$u = $j * $step;
						break;
					}else{
						$count = $j;
					}
				}else{
					$i = $j;
				}
			}
			if($l) {
				$l = ($i-1)*$step;
			}else{
				$l = $i * $step;
			}
		}

		while ($l <= $u) {              // 当上边界小于下边界时，查找失败
			$i = floor(($l + $u) / 2);  // 计算近似中间记录
			fseek($this->fp, $this->firstip + $i * 7);
			$beginip = strrev(fread($this->fp, 4));     // 获取中间记录的开始 IP 地址
			// strrev 函数在这里的作用是将 little-endian 的压缩 IP 地址转化为 big-endian 的格式
			// 以便用于比较，后面相同。
			if ($ip < $beginip) {       // 用户的 IP 小于中间记录的开始 IP 地址时
				$u = $i - 1;            // 将搜索的上边界修改为中间记录减一
			}else{
				fseek($this->fp, $this->getlong3());
				$endip = strrev(fread($this->fp, 4));   // 获取中间记录的结束 IP 地址
				if ($ip > $endip) {     // 用户的 IP 大于中间记录的结束 IP 地址时
					$l = $i + 1;        // 将搜索的下边界修改为中间记录加一
				} else {                  // 用户的 IP 在中间记录的 IP 范围内时
					$findip = $this->firstip + $i * 7;
					break;              // 则表示找到结果，退出循环
				}
			}
		}

		// 获取查找到的 IP 地理位置信息
		fseek($this->fp, $findip);
		$location['start'] = long2ip($this->getlong());   // 用户 IP 所在范围的开始地址
		$offset = $this->getlong3();
		fseek($this->fp, $offset);
		$location['end'] = long2ip($this->getlong());     // 用户 IP 所在范围的结束地址
		$byte = fread($this->fp, 1);    // 标志字节
		switch (ord($byte)) {
			case 1:                     // 标志字节为 1，表示国家和区域信息都被同时重定向
				$countryOffset = $this->getlong3();         // 重定向地址
				fseek($this->fp, $countryOffset);
				$byte = fread($this->fp, 1);    // 标志字节
				switch (ord($byte)) {
					case 2:             // 标志字节为 2，表示国家信息又被重定向
						fseek($this->fp, $this->getlong3());
						$location['country'] = $this->getstring();
						fseek($this->fp, $countryOffset + 4);
						$location['area'] = $this->getarea();
					break;

					default:            // 否则，表示国家信息没有被重定向
						$location['country'] = $this->getstring($byte);
						$location['area'] = $this->getarea();
					break;
				}
			break;

			case 2:                     // 标志字节为 2，表示国家信息被重定向
				fseek($this->fp, $this->getlong3());
				$location['country'] = $this->getstring();
				fseek($this->fp, $offset + 8);
				$location['area'] = $this->getarea();
			break;

			default:                    // 否则，表示国家信息没有被重定向
				$location['country'] = $this->getstring($byte);
				$location['area'] = $this->getarea();
			break;
		}

		$location['area'] = trim(mb_convert_encoding($location['area'], 'UTF-8', 'GBK'));

		if ($location['area'] == "纯真网络" || $location['area'] == "CZ88.NET") {  // CZ88.NET 表示没有有效信息
			$location['area'] = "";
		}

		$location['country'] = trim(mb_convert_encoding($location['country'], 'UTF-8', 'GBK'));

		$location['country'] = str_ireplace('CZ88.NET', '', $location['country']);
		$location['country'] = str_ireplace('CZ88', '', $location['country']);
		$location['country'] = str_replace('纯真网络', '', $location['country']);

		return $location;
	}

	/**
     * 构造函数，打开 QQWry.Dat 文件并初始化类中的信息
     *
     * @param string $filename
     * @return IpLocation
     */
	function IpLocation($filename = "QQWry.Dat") {
		$this->fp = 0;
		if (($this->fp = @fopen($filename, 'rb')) !== false) {
			$this->firstip = $this->getlong();
			$this->lastip = $this->getlong();
			$this->totalip = ($this->lastip - $this->firstip) / 7;
			// 注册析构函数，使其在程序执行结束时执行
			register_shutdown_function(array(&$this, '_IpLocation'));
		}
	}

	/**
     * 析构函数，用于在页面执行结束后自动关闭打开的文件。
     *
     */
	function _IpLocation() {
		if ($this->fp) {
			fclose($this->fp);
		}

		$this->fp = 0;
	}
}
?>