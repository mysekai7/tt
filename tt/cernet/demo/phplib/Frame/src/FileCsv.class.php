<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:FileCsv.class.php
- 原作者:Ron Barnett
- 整理者:indraw
- 编写日期:2004/11/4
- 简要描述:常用文本csv文件操作类集。
- 运行环境:php4或以上版本
- 修改记录:2004/11/4，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	$db = new FileCsv("./","data.csv");
*/
/*
	FileCsv($df = '', $dd = '', $as = false, $ck = false)
	read_csv()                                    //从csv文件中以2维数组格式读出数据
	append_csv()                                  //将新数据追加到csv文件
	delete($rownumber, $n = 1)                    //从csv文件中删除数据
	update($rownumber, $aValues)                  //从csv文件中更新数据
	append()                                      //返回将要填加的数据数组行数
	find($aValues)                                //从csv文件中查找数据
	write_csv($append = false, $force = false)    //将数组写入csv文件，可以从新创建或添加。
	writeable()                                   //判断csv文件是否可写入
	row_count()                                   //获取csv文件行数
	rows_to_write()                               //获取要写入csv文件新行数
	package($aData)                               //将数组文件转换成字符串格式以备一次写入-加密格式
	xpackage($aData)                              //将数组文件转换成字符串格式以备一次写入-普通格式
	encrypt(&$txt, $key)                          //将数据编码
	decrypt(&$txt, $key)                          //将数据解码
	krypt($txt, $crypt_key)                       //为数据编码解码做准备
*/

//===================================================================
class FileCsv 
{

	var $show_errors = true;       //是否显示出错信息

	var $data_file = "data.csv";   //csv文件名
	var $dir = "data";             //csv文件路径
	var $db ;                      //取出的csv文件二维数组
	var $new_rows ;                //新追加数据数组
	var $last_update = "";         //文件最后修改时间
	var $written = 0;              //写入的数据行数
	var $assoc = false ;           //是否使用关联数组
	var $crypt_key = false;        //是否加密数据

	/*
	-----------------------------------------------------------
	函数名称:FileCsv($df = '', $dd = '', $as = false, $ck = false) 
	简要描述:初始化csv文件操作类
	输入:mixed (文件名，文件路径，是否为关联数组，是否加密)
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function FileCsv($df = '', $dd = '', $as = false, $ck = false) 
	{
		$this->new_rows = array();
		$this->db = array();

		if ($df != '') {
			$this->data_file = $df;
		} 
		if ($dd != '') {
			$this->dir = $dd;
		} 
		if ($as !== false) {
			$this->assoc = $as;
		} 
		if ($ck !== false) {
			$this->crypt_key = $ck;
		} 
	} 

	/*
	-----------------------------------------------------------
	函数名称:read_csv() 
	简要描述:从csv文件中以2维数组格式读出数据
	输入:void
	输出:boolean (用二维数组填充类属性$this->db)
	修改日志:------
	-----------------------------------------------------------
	*/
	function read_csv() 
	{
		$cf = "$this->dir/$this->data_file";
		$row = 0;
		$this->last_update = @filemtime($cf) or $this->print_error("FileCsv::read_csv: 不能获取文件 $cf 的修改时间\n");
		$fp = @fopen($cf, 'rb') ;
		if (is_resource($fp)) {
			while ($data = fgetcsv ($fp, 1024, ",")) {
				if ($this->crypt_key) {
					for ($n = 0;$n < count($data);$n++) {
						$this->decrypt($data[$n], $this->crypt_key);
					} 
				} 
				if (($this->assoc === true) && ($row == 0)) {
					$num = count ($data);
					$key = $data;
					$row = 1;
				} else {
					$num = count ($data);
					$tmp = array() ;
					for ($n = 0 ;$n < $num ; $n++) {
						if ($this->assoc === true) {
							$k = strtolower($key[$n]) ;
						} else {
							$k = $n;
						} 
						$d = $data[$n] ;
						$tmp[$k] = $d ;
					} 
					$this->db[] = $tmp ;
				} 
			} 
			$this->db = array_change_key_case($this->db, CASE_LOWER);
			return @fclose($fp);
		} else {
			$this->print_error("FileCsv::read_csv: 不能打开文件 $cf\n");
			return false;
		} 
	} 
	/*
	-----------------------------------------------------------
	函数名称:append_csv()
	简要描述:将新数据(二维数组)追加到csv文件($this->new_rows)
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function append_csv() 
	{
		if ($this->rows_to_write() == 0) {
			$this->print_error("FileCsv:: append_csv: 您还没有添加需要追加的数据\n");
		} 
		$tmp = '';
		$this->written = 0;
		for ($n = 0;$n < count($this->new_rows);$n++) {
			$tmp .= $this->package($this->new_rows[$n]);
			$this->written++ ;
		} 
		$cf = "$this->dir/$this->data_file";
		$fp = @fopen($cf, 'ab') ;
		if (is_resource($fp)) {
			if (!@fwrite($fp, $tmp)) {
				@fclose($fp);
				$this->print_error("FileCsv:: append_csv: 不能写入数据到 $cf\n");
				return false;
			} 
			$this->new_rows = array();
			return @fclose($fp);
		} else {
			$this->print_error("FileCsv::append_csv: 不能打开文件:$cf");
			return false;
		} 
	} 
	/*
	-----------------------------------------------------------
	函数名称:delete($rownumber, $n = 1) 
	简要描述:从csv文件中删除数据
	输入:mixed (第几行数据)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function delete($rownumber, $n = 1) 
	{
		$key_index = array_keys(array_keys($this->db), $rownumber);
		array_splice($this->db, $key_index[0], 1);
		if (!$this->db) {
			$this->print_error("FileCsv::delete: 删除数据 $n 行从  $rownumber 失败") . $this->row_count();
			return false;
		} else {
			return true;
		} 
	} 
	/*
	-----------------------------------------------------------
	函数名称:update($rownumber, $aValues) 
	简要描述:从csv文件中更新数据
	输入:mixed (行数，值)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function update($rownumber, $aValues) 
	{
		$aValues = array_change_key_case($aValues, CASE_LOWER);
		while (list($key, $val) = each($aValues)) {
			if (@array_key_exists($key, $this->db[$rownumber])) {
				// echo "found key $key new value=> $val<br />";
				$this->db[$rownumber][$key] = $val;
			} else {
				$this->print_error("FileCsv::update: 在需要更新的 [$rownumber] 行没有找到需要更新的字段 [$key]");
				return false;
			} 
		} // while
		return true;
	} 
	/*
	-----------------------------------------------------------
	函数名称:append()
	简要描述:返回将要填加的数据数组行数
	输入:void
	输出:int
	修改日志:------
	-----------------------------------------------------------
	*/
	function append() 
	{
		$n = $this->rows_to_write();
		foreach($this->new_rows as $new) {
			$this->db[] = $new;
		} 
		$this->new_rows = array();
		return $n;
	} 
	/*
	-----------------------------------------------------------
	函数名称:find($aValues) 
	简要描述:从csv文件中查找数据
	输入:array(key/value)
	输出:array
	修改日志:------
	-----------------------------------------------------------
	*/
	function find($aValues) 
	{
		$afound = false;
		$n = 0;
		$target = count($aValues); 
		// echo "Target = $target <pre>";
		// print_r($aValues);
		// echo "</pre>";
		foreach($this->db as $record) {
			// echo $n."<br />";
			$found = 0;
			reset($aValues);
			while (list($key, $val) = each($aValues)) {
				if ((array_key_exists($key, $record)) && (strcasecmp(trim(onespace($record[$key])), trim(onespace($val))) == 0)) {
					// echo "$key => [$val]	 record[$n] ?= [".$record[$key]."]<br />";
					$found += 1;
				} else {
					$found = 0;
					continue 1;
				} 
			} // while
			if ($found == $target) {
				// echo 'Matched<br />';
				$afound[] = $n;
			} 
			$n++;
		} 
		return $afound;
	} 
	/*
	-----------------------------------------------------------
	函数名称:write_csv($append = false, $force = false)
	简要描述:将数组写入csv文件，可以从新创建或添加。
	输入:$append//是否追加 $force//强制操作
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function write_csv($append = false, $force = false)
	{ 
		//判断文件是否强制操作
		$cf = "$this->dir/$this->data_file";
		if (! $force) {
			if ($this->last_update != filemtime($cf)) {
				$this->print_error("FileCsv::write_csv: $cf 更新失败，因为文件被修改过。");
				return false;
			} 
		} 
		//判断是从新创建还是添加，并判断是联合数组还是普通数组
		$mode = ($append ? 'ab+' : 'wb+');
		if ($this->assoc) {
			$loop = 0;
		} else {
			$loop = 1;
		} 
		//打开文件执行写入操作
		$fp = @fopen($cf, $mode);
		$tmp = '';
		if (is_resource($fp)) {
			foreach($this->db as $row) {
				if (($loop == 0) && (!$append)) {
					$loop = 1;
					$tmp .= $this->package(array_keys($row));
					$tmp .= $this->package($row);
					$this->written++ ;
				} else {
					$test = implode(' ', $row);
					if (trim($test) > '') { //不是空行
						$tmp .= $this->package($row);
						$this->written++ ;
					} 
				} 
			} 
			if ($this->written == 0) { //如果写入行为0
				$tmp .= " ";
			} 
			if (!@fwrite($fp, $tmp)) {
				@fclose($fp);
				$this->print_error("FileCsv::write_csv: 写入 $cf (" . $this->written . " 行数据失败 )");
				return false;
			} 
		} else {
			$this->print_error("FileCsv::write_csv: 不能打开csv文件 $cf 以备操作");
			return false;
		} 
		return @fclose($fp);
	} 
	/*
	-----------------------------------------------------------
	函数名称:writeable()
	简要描述:判断csv文件是否可写入
	输入:void
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function writeable()
	{
		$cf = "$this->dir/$this->data_file";
		if (!is_writeable($cf)) {
			return false;
		} else {
			return true;
		} 
	} 
	/*
	-----------------------------------------------------------
	函数名称:row_count()
	简要描述:获取csv文件行数
	输入:void
	输出:int
	修改日志:------
	-----------------------------------------------------------
	*/
	function row_count()
	{ 
		return count($this->db);
	} 
	/*
	-----------------------------------------------------------
	函数名称:rows_to_write()
	简要描述:获取要写入csv文件新行数
	输入:void
	输出:int
	修改日志:------
	-----------------------------------------------------------
	*/
	function rows_to_write()
	{ 
		return count ($this->new_rows);
	} 
	/*
	-----------------------------------------------------------
	函数名称:package($aData)
	简要描述:将数组文件转换成字符串格式以备一次写入
	输入:array
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function package($aData)
	{ 
		if ($this->crypt_key) {
			while (list($key, $val) = each($aData)) {
				$this->encrypt($aData[$key], $this->crypt_key);
			} // while
		} 
		return '"' . implode ('","', $aData) . '"' . "\n";
	} 
	function xpackage($aData)
	{ 
		return '"' . implode ('","', $aData) . '"' . "\n";
	} 
	/*
	-----------------------------------------------------------
	函数名称:encrypt(&$txt, $key) 
	简要描述:将数据编码
	输入:mixd(需要编码字符串，编码解码key)
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function encrypt(&$txt, $key) 
	{
		srand((double)microtime() * 1000000);
		$encrypt_key = md5(rand(0, 32000));
		$ctr = 0;
		$tmp = "";
		$tx = $txt;
		for ($i = 0;$i < strlen($tx);$i++) {
			if ($ctr == strlen($encrypt_key))
				$ctr = 0;
			$tmp .= substr($encrypt_key, $ctr, 1) . (substr($tx, $i, 1) ^ substr($encrypt_key, $ctr, 1));
			$ctr++;
		} 
		$txt = base64_encode($this->krypt($tmp, $key));
	} 
	/*
	-----------------------------------------------------------
	函数名称:decrypt(&$txt, $key) 
	简要描述:将数据解码
	输入:mixed(需要解码字符串，编码解码key)
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function decrypt(&$txt, $key) 
	{
		$tx = $this->krypt(base64_decode($txt), $key);
		$tmp = "";
		for ($i = 0;$i < strlen($tx);$i++) {
			$md5 = substr($tx, $i, 1);
			$i++;
			$tmp .= (substr($tx, $i, 1) ^ $md5);
		} 
		$txt = $tmp;
	} 
	/*
	-----------------------------------------------------------
	函数名称:krypt($txt, $crypt_key) 
	简要描述:为数据编码解码做准备
	输入:mixed
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function krypt($txt, $crypt_key) 
	{
		$md5 = md5($crypt_key);
		$ctr = 0;
		$tmp = "";
		for ($i = 0;$i < strlen($txt);$i++) {
			if ($ctr == strlen($md5)) $ctr = 0;
			$tmp .= substr($txt, $i, 1) ^ substr($md5, $ctr, 1);
			$ctr++;
		} 
		return $tmp;
	} 

	/*
	-----------------------------------------------------------
	函数名称:print_error($str = "")
	简要描述:显示操作错误信息
	输入:string 
	输出:echo or false
	修改日志:------
	-----------------------------------------------------------
	*/
	function print_error($str = "")
	{
		//设置全局变量$PHPSEA_ERROR..
		global $PHPSEA_ERROR;
		$PHPSEA_ERROR['FileCsv_Error'] = $str;
	
		//判断是否显示错误输出..
		if ( $this->show_errors )
		{
			print "<blockquote><font face=arial size=2 color=ff0000>";
			print "<b>FileCsv Error --</b> ";
			print "[<font color=000077>$str</font>]";
			print "</font></blockquote>";
		}
		else
		{
			return false;	
		}
	}//end func

}//end class
//===================================================================
	
	//去掉多余空格
	function onespace($a) 
	{
		$b = str_replace('  ', ' ', $a);
		return $b;
	} 
	//简单扩展类
	class NewCsv extends FileCsv 
	{
		var $unpacked = '';
		function unpack_csv()
		{
			if (($this->read_csv() === true) && (count($this->db) >= 1)) {
				// echo $this->crypt_key?$this->crypt_key:'';
				foreach ($this->db as $row) {
					$this->unpacked .= $this->xpackage ($row);
				} 
			} 
			return $this->unpacked;
		} 
	} 

//===================================================================
?>