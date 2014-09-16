<?php
/*
---------------------------------------------------------------------
- ÏîÄ¿: DNS phpsea
- °æ±¾: 1.5
- ÎÄ¼şÃû:FileZip.class.php
- Ô­×÷Õß:Devin Doucette
- ÕûÀíÕß:indraw
- ±àĞ´ÈÕÆÚ:2004/11/4
- ¼òÒªÃèÊö:TAR/GZIP/BZIP2/ZIP ÎÄ¼ş²Ù×÷Àà¼¯
- ÔËĞĞ»·¾³:php4»òÒÔÉÏ
- ĞŞ¸Ä¼ÇÂ¼:2004/11/4£¬indraw£¬³ÌĞò´´Á¢
---------------------------------------------------------------------
*/

/*
	$test = new gzip_file("test.tgz");
	$test->set_options(array('basedir'=>"../",'overwrite'=>1,'level'=>1));
	$test->add_files("db");
	$test->exclude_files("db/*.swf");
	$test->store_files("db/*.txt");
	$test->create_archive();

	$test = new gzip_file("test.tgz");
	$test->set_options(array('overwrite'=>1));
	$test->extract_files();
*/

/*
	set_options($options)    //ÉèÖÃÑ¹Ëõ²ÎÊı
	create_archive()         //Ö´ĞĞÑ¹Ëõ²Ù×÷
	add_data($data)          //ÏòÑ¹Ëõ°üÖĞĞ´Êı¾İ
	make_list()              //»ñÈ¡ËùÓĞ½«ÒªÑ¹ËõµÄÎÄ¼ş
	add_files($list)         //Ìí¼ÓÎÄ¼şÃû³Æ
	exclude_files($list)     //ÉèÖÃÑ¹ËõÊ±¹ıÂËµÄÎÄ¼şÀàĞÍ
	store_files($list)       //ÉèÖÃÄÄĞ©ÎÄ¼ş²»Ñ¹Ëõ£¬Ö»ÊÇ´ò°ü´æ´¢
	list_files($list)        //»ñÈ¡ÎÄ¼şÈ«Â·¾¶
	parse_dir($dirname)      //¸ñÊ½»¯Ä¿Â¼
	sort_files($a,$b)        //ÎÄ¼şÀàĞÍ
	download_file()          //ÏÂÔØÑ¹Ëõ°ü

	archive($name)           //ºËĞÄÀà
	tar_file($name)          //tarÀà
	gzip_file($name)         //gzipÀà
	bzip_file($name)         //bzipÀà
	zip_file($name)          //zipÀà
*/

//===================================================================
class archive
{
	var $show_errors = false;			//ÊÇ·ñÏÔÊ¾³ö´íĞÅÏ¢

	function archive($name)
	{
		$this->options = array(
			'basedir'=>".",				//Ñ¹Ëõ°üÂ·¾¶
			'name'=>$name,				//Ñ¹Ëõ°üÃû
			'prepend'=>"",				//Ô¤´¦ÀíÂ·¾¶
			'inmemory'=>0,				//ÊÇ·ñ½«Ñ¹Ëõ°ü·ÅÄÚ´æÖĞ
			'overwrite'=>0,				//Èç¹ûÑ¹Ëõ°ü´æÔÚÊÇ·ñ¸²¸Ç
			'recurse'=>1,				//ÊÇ·ñ²ÉÓÃµİ¹éÄ¿Â¼Ñ¹Ëõ
			'storepaths'=>1,			//ÊÇ·ñÑ¹ËõÄ¿Â¼½á¹¹
			'level'=>3,					//Ñ¹ËõÂÊ
			'method'=>1,				//???(1ÎÄ¼şĞ¡£¬0ÎÄ¼ş´ó)
			'sfx'=>"",					//???
			'type'=>"",					//ÀàĞÍ
			'comment'=>""				//×¢ÊÍ
		);
		$this->files = array();
		$this->exclude = array();
		$this->storeonly = array();
		$this->error = array();
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:set_options($options)
	¼òÒªÃèÊö:ÉèÖÃÑ¹Ëõ²ÎÊı
	ÊäÈë:array (¾ßÌåÉèÖÃ²é¿´ÉÏÃæ$this->options)
	Êä³ö:void
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function set_options($options)
	{
		foreach($options as $key => $value)
		{
			$this->options[$key] = $value;
		}
		if(!empty($this->options['basedir']))
		{
			$this->options['basedir'] = str_replace("\\","/",$this->options['basedir']);
			$this->options['basedir'] = preg_replace("/\/+/","/",$this->options['basedir']);
			$this->options['basedir'] = preg_replace("/\/$/","",$this->options['basedir']);
		}
		if(!empty($this->options['name']))
		{
			$this->options['name'] = str_replace("\\","/",$this->options['name']);
			$this->options['name'] = preg_replace("/\/+/","/",$this->options['name']);
		}
		if(!empty($this->options['prepend']))
		{
			$this->options['prepend'] = str_replace("\\","/",$this->options['prepend']);
			$this->options['prepend'] = preg_replace("/^(\.*\/+)+/","",$this->options['prepend']);
			$this->options['prepend'] = preg_replace("/\/+/","/",$this->options['prepend']);
			$this->options['prepend'] = preg_replace("/\/$/","",$this->options['prepend']) . "/";
		}
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:create_archive()
	¼òÒªÃèÊö:Ö´ĞĞÑ¹Ëõ²Ù×÷
	ÊäÈë:void
	Êä³ö:boolean
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function create_archive()
	{
		$this->make_list();

		if($this->options['inmemory'] == 0)
		{
			$pwd = getcwd();
			chdir($this->options['basedir']);
			if($this->options['overwrite'] == 0 && file_exists($this->options['name'] . ($this->options['type'] == "gzip" || $this->options['type'] == "bzip"? ".tmp" : "")))
			{
				$this->print_error("FileZip::create_archive: Ñ¹Ëõ°ü {$this->options['name']} ÒÑ¾­´æÔÚ.");
				chdir($pwd);
				return 0;
			}
			else if($this->archive = @fopen($this->options['name'] . ($this->options['type'] == "gzip" || $this->options['type'] == "bzip"? ".tmp" : ""),"wb+"))
			{
				chdir($pwd);
			}
			else
			{
				$this->print_error("FileZip::create_archive: ²»ÄÜ´ò¿ªÑ¹Ëõ°ü {$this->options['name']} ½øĞĞĞ´²Ù×÷.");
				chdir($pwd);
				return 0;
			}
		}
		else
		{
			$this->archive = "";
		}

		switch($this->options['type'])
		{
		case "zip":
			if(!$this->create_zip())
			{
				$this->print_error("FileZip::create_archive: ²»ÄÜÉú³ÉÑ¹Ëõ°ü zip ÎÄ¼ş.");
				return 0;
			}
			break;
		case "bzip":
			if(!$this->create_tar())
			{
				$this->print_error("FileZip::create_archive: ²»ÄÜÉú³ÉÑ¹Ëõ°ü tar ÎÄ¼ş.");
				return 0;
			}
			if(!$this->create_bzip())
			{
				$this->print_error("FileZip::create_archive: ²»ÄÜÉú³ÉÑ¹Ëõ°ü bzip2 ÎÄ¼ş.");
				return 0;
			}
			break;
		case "gzip":
			if(!$this->create_tar())
			{
				$this->print_error("FileZip::create_archive: ²»ÄÜÉú³ÉÑ¹Ëõ°ü tar ÎÄ¼ş.");
				return 0;
			}
			if(!$this->create_gzip())
			{
				$this->print_error("FileZip::create_archive: ²»ÄÜÉú³ÉÑ¹Ëõ°ü gzip ÎÄ¼ş.");
				return 0;
			}
			break;
		case "tar":
			if(!$this->create_tar())
			{
				$this->print_error("FileZip::create_archive: ²»ÄÜÉú³ÉÑ¹Ëõ°ü tar ÎÄ¼ş.");
				return 0;
			}
		}

		if($this->options['inmemory'] == 0)
		{
			fclose($this->archive);
			if($this->options['type'] == "gzip" || $this->options['type'] == "bzip")
			{
				unlink($this->options['basedir'] . "/" . $this->options['name'] . ".tmp");
			}
		}
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:add_data($data)
	¼òÒªÃèÊö:ÏòÑ¹Ëõ°üÖĞĞ´Êı¾İ
	ÊäÈë:string
	Êä³ö:void
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function add_data($data)
	{
		if($this->options['inmemory'] == 0)
		{
			fwrite($this->archive,$data);
		}
		else
		{
			$this->archive .= $data;
		}
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:make_list()
	¼òÒªÃèÊö:»ñÈ¡ËùÓĞ½«ÒªÑ¹ËõµÄÎÄ¼ş
	ÊäÈë:void
	Êä³ö:array
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function make_list()
	{
		if(!empty($this->exclude))
		{
			foreach($this->files as $key => $value)
			{
				foreach($this->exclude as $current)
				{
					if($value['name'] == $current['name'])
					{
						unset($this->files[$key]);
					}
				}
			}
		}
		if(!empty($this->storeonly))
		{
			foreach($this->files as $key => $value)
			{
				foreach($this->storeonly as $current)
				{
					if($value['name'] == $current['name'])
					{
						$this->files[$key]['method'] = 0;
					}
				}
			}
		}
		unset($this->exclude,$this->storeonly);
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:add_files($list) 
	¼òÒªÃèÊö:Ìí¼ÓÎÄ¼şÃû³Æ
	ÊäÈë:array
	Êä³ö:void (½øĞĞÀàÊôĞÔ¸½Öµ)
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function add_files($list)
	{
		$temp = $this->list_files($list);
		foreach($temp as $current)
		{
			$this->files[] = $current;
		}
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:exclude_files($list)
	¼òÒªÃèÊö:ÉèÖÃÑ¹ËõÊ±¹ıÂËµÄÎÄ¼şÀàĞÍ
	ÊäÈë:array
	Êä³ö:void
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function exclude_files($list)
	{
		$temp = $this->list_files($list);
		foreach($temp as $current)
		{
			$this->exclude[] = $current;
		}
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:store_files($list)
	¼òÒªÃèÊö:ÉèÖÃÄÄĞ©ÎÄ¼ş²»Ñ¹Ëõ£¬Ö»ÊÇ´ò°ü´æ´¢
	ÊäÈë:array
	Êä³ö:void
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function store_files($list)
	{
		$temp = $this->list_files($list);
		foreach($temp as $current)
		{
			$this->storeonly[] = $current;
		}
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:list_files($list)
	¼òÒªÃèÊö:»ñÈ¡ÎÄ¼şÈ«Â·¾¶
	ÊäÈë:array
	Êä³ö:array
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function list_files($list)
	{
		if(!is_array($list))
		{
			$temp = $list;
			$list = array($temp);
			unset($temp);
		}

		$files = array();

		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach($list as $current)
		{
			$current = str_replace("\\","/",$current);
			$current = preg_replace("/\/+/","/",$current);
			$current = preg_replace("/\/$/","",$current);
			if(strstr($current,"*"))
			{
				$regex = preg_replace("/([\\\^\$\.\[\]\|\(\)\?\+\{\}\/])/","\\\\\\1",$current);
				$regex = str_replace("*",".*",$regex);
				$dir = strstr($current,"/")? substr($current,0,strrpos($current,"/")) : ".";
				$temp = $this->parse_dir($dir);
				foreach($temp as $current2)
				{
					if(preg_match("/^{$regex}$/i",$current2['name']))
					{
						$files[] = $current2;
					}
				}
				unset($regex,$dir,$temp,$current);
			}
			else if(@is_dir($current))
			{
				$temp = $this->parse_dir($current);
				foreach($temp as $file)
				{
					$files[] = $file;
				}
				unset($temp,$file);
			}
			else if(@file_exists($current))
			{
				$files[] = array('name'=>$current,'name2'=>$this->options['prepend'] .
					preg_replace("/(\.+\/+)+/","",($this->options['storepaths'] == 0 && strstr($current,"/"))? 
					substr($current,strrpos($current,"/") + 1) : $current),'type'=>0,
					'ext'=>substr($current,strrpos($current,".")),'stat'=>stat($current));
			}
		}

		chdir($pwd);

		unset($current,$pwd);

		usort($files,array("archive","sort_files"));

		return $files;
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:parse_dir($dirname)
	¼òÒªÃèÊö:¸ñÊ½»¯Ä¿Â¼
	ÊäÈë:string
	Êä³ö:string
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function parse_dir($dirname)
	{
		if($this->options['storepaths'] == 1 && !preg_match("/^(\.+\/*)+$/",$dirname))
		{
			$files = array(array('name'=>$dirname,'name2'=>$this->options['prepend'] . 
				preg_replace("/(\.+\/+)+/","",($this->options['storepaths'] == 0 && strstr($dirname,"/"))? 
				substr($dirname,strrpos($dirname,"/") + 1) : $dirname),'type'=>5,'stat'=>stat($dirname)));
		}
		else
		{
			$files = array();
		}
		$dir = @opendir($dirname);

		while($file = @readdir($dir))
		{
			if($file == "." || $file == "..")
			{
				continue;
			}
			else if(@is_dir($dirname."/".$file))
			{
				if(empty($this->options['recurse']))
				{
					continue;
				}
				$temp = $this->parse_dir($dirname."/".$file);
				foreach($temp as $file2)
				{
					$files[] = $file2;
				}
			}
			else if(@file_exists($dirname."/".$file))
			{
				$files[] = array('name'=>$dirname."/".$file,'name2'=>$this->options['prepend'] . 
					preg_replace("/(\.+\/+)+/","",($this->options['storepaths'] == 0 && strstr($dirname."/".$file,"/"))? 
					substr($dirname."/".$file,strrpos($dirname."/".$file,"/") + 1) : $dirname."/".$file),'type'=>0,
					'ext'=>substr($file,strrpos($file,".")),'stat'=>stat($dirname."/".$file));
			}
		}

		@closedir($dir);

		return $files;
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:sort_files($a,$b)
	¼òÒªÃèÊö:ÎÄ¼şÀàĞÍ
	ÊäÈë:---
	Êä³ö:---
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function sort_files($a,$b)
	{
		if($a['type'] != $b['type'])
		{
			return $a['type'] > $b['type']? -1 : 1;
		}
		else if($a['type'] == 5)
		{
			return strcmp(strtolower($a['name']),strtolower($b['name']));
		}
		else
		{
			if($a['ext'] != $b['ext'])
			{
				return strcmp($a['ext'],$b['ext']);
			}
			else if($a['stat'][7] != $b['stat'][7])
			{
				return $a['stat'][7] > $b['stat'][7]? -1 : 1;
			}
			else
			{
				return strcmp(strtolower($a['name']),strtolower($b['name']));
			}
		}
		return 0;
	}
	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:download_file()
	¼òÒªÃèÊö:ÏÂÔØÑ¹Ëõ°ü
	ÊäÈë:void
	Êä³ö:¶¨Î»ä¯ÀÀÆ÷Êä³ö
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function download_file()
	{
		if($this->options['inmemory'] == 0)
		{
			$this->print_error("FileZip::download_file: Ö»ÓĞµ±Ñ¹Ëõ°üÔÚÄÚ´æÖĞµÄÊ±ºò£¬²ÅÄÜÄÍÓÃ download_file() º¯ÊıÏÂÔØ¡£ µ«ÊÇÑ¹Ëõ°ü·ÅÔÚÄÚ´æÖĞ£¬Ö´ĞĞÑ¹ËõµÄËÙ¶È·Ç³£¿ì¡£");
			return;
		}
		switch($this->options['type'])
		{
		case "zip":
			header("Content-type:application/zip");
			break;
		case "bzip":
			header("Content-type:application/x-compressed");
			break;
		case "gzip":
			header("Content-type:application/x-compressed");
			break;
		case "tar":
			header("Content-type:application/x-tar");
		}
		$header = "Content-disposition: attachment; filename=\"";
		$header .= strstr($this->options['name'],"/")? substr($this->options['name'],strrpos($this->options['name'],"/") + 1) : $this->options['name'];
		$header .= "\"";
		header($header);
		header("Content-length: " . strlen($this->archive));
		header("Content-transfer-encoding: binary");
		header("Pragma: no-cache");
		header("Expires: 0");
		print($this->archive);
	}

	/*
	-----------------------------------------------------------
	º¯ÊıÃû³Æ:print_error($str = "")
	¼òÒªÃèÊö:ÏÔÊ¾²Ù×÷´íÎóĞÅÏ¢
	ÊäÈë:string 
	Êä³ö:echo or false
	ĞŞ¸ÄÈÕÖ¾:------
	-----------------------------------------------------------
	*/
	function print_error($str = "")
	{
		//ÉèÖÃÈ«¾Ö±äÁ¿$PHPSEA_ERROR..
		global $PHPSEA_ERROR;
		$PHPSEA_ERROR['FileZip_Error'] = $str;
	
		//ÅĞ¶ÏÊÇ·ñÏÔÊ¾´íÎóÊä³ö..
		if ( $this->show_errors )
		{
			print "<blockquote><font face=arial size=2 color=ff0000>";
			print "<b>FileZip Error --</b> ";
			print "[<font color=000077>$str</font>]";
			print "</font></blockquote>";
		}
		else
		{
			return false;	
		}
	}//end func

}

//=============================================================================
//½¨Á¢tarÑ¹Ëõ°ü
class tar_file extends archive
{
	function tar_file($name)
	{
		$this->archive($name);
		$this->options['type'] = "tar";
	}

	function create_tar()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach($this->files as $current)
		{
			if($current['name'] == $this->options['name'])
			{
				continue;
			}
			if(strlen($current['name2']) > 99)
			{
				$path = substr($current['name2'],0,strpos($current['name2'],"/",strlen($current['name2']) - 100) + 1);
				$current['name2'] = substr($current['name2'],strlen($path));
				if(strlen($path) > 154 || strlen($current['name2']) > 99)
				{
					$this->print_error("FileZip::create_tar: ²»ÄÜÌî¼Ó {$path}{$current['name2']} µ½Ñ¹Ëõ°üÖĞ£¬ÒòÎªÎÄ¼şÃûÌ«³¤¡£");
					continue;
				}
			}
			$block = pack("a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12",$current['name2'],decoct($current['stat'][2]),
				sprintf("%6s ",decoct($current['stat'][4])),sprintf("%6s ",decoct($current['stat'][5])),
				sprintf("%11s ",decoct($current['stat'][7])),sprintf("%11s ",decoct($current['stat'][9])),
				"        ",$current['type'],"","ustar","00","Unknown","Unknown","","",!empty($path)? $path : "","");

			$checksum = 0;
			for($i = 0; $i < 512; $i++)
			{
				$checksum += ord(substr($block,$i,1));
			}
			$checksum = pack("a8",sprintf("%6s ",decoct($checksum)));
			$block = substr_replace($block,$checksum,148,8);

			if($current['stat'][7] == 0)
			{
				$this->add_data($block);
			}
			else if($fp = @fopen($current['name'],"rb"))
			{
				$this->add_data($block);
				while($temp = fread($fp,1048576))
				{
					$this->add_data($temp);
				}
				if($current['stat'][7] % 512 > 0)
				{
					$temp = "";
					for($i = 0; $i < 512 - $current['stat'][7] % 512; $i++)
					{
						$temp .= "\0";
					}
					$this->add_data($temp);
				}
				fclose($fp);
			}
			else
			{
				$this->print_error("FileZip::create_tar: ²»ÄÜ´ò¿ª {$current['name']} ÒÔ±¸¶ÁÈ¡. Ã»ÓĞ±»Ìí¼Ó.");
			}
		}

		$this->add_data(pack("a512",""));

		chdir($pwd);

		return 1;
	}

	function extract_files()
	{
		$pwd = getcwd();
		chdir($this->options['basedir']);

		if($fp = $this->open_archive())
		{
			if($this->options['inmemory'] == 1)
			{
				$this->files = array();
			}

			while($block = fread($fp,512))
			{
				$temp = unpack("a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100temp/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a155prefix/a12temp",$block);
				$file = array(
					'name'=>$temp['prefix'] . $temp['name'],
					'stat'=>array(
						2=>$temp['mode'],
						4=>octdec($temp['uid']),
						5=>octdec($temp['gid']),
						7=>octdec($temp['size']),
						9=>octdec($temp['mtime']),
					),
					'checksum'=>octdec($temp['checksum']),
					'type'=>$temp['type'],
					'magic'=>$temp['magic'],
				);
				if($file['checksum'] == 0x00000000)
				{
					break;
				}
				else if($file['magic'] != "ustar")
				{
					$this->print_error("FileZip::extract_files: ±¾Àà¿â²»Ö§³ÖÕâÖÖtarÑ¹Ëõ¸ñÊ½¡£");
					break;
				}
				$block = substr_replace($block,"        ",148,8);
				$checksum = 0;
				for($i = 0; $i < 512; $i++)
				{
					$checksum += ord(substr($block,$i,1));
				}
				if($file['checksum'] != $checksum)
				{
					$this->print_error("FileZip::extract_files: ²»ÄÜ½âÑ¹Ëõ {$this->options['name']}, ÎÄ¼şÓĞÎÊÌâ.");
				}

				if($this->options['inmemory'] == 1)
				{
					$file['data'] = fread($fp,$file['stat'][7]);
					fread($fp,(512 - $file['stat'][7] % 512) == 512? 0 : (512 - $file['stat'][7] % 512));
					unset($file['checksum'],$file['magic']);
					$this->files[] = $file;
				}
				else
				{
					if($file['type'] == 5)
					{
						if(!is_dir($file['name']))
						{
							mkdir($file['name'],$file['stat'][2]);
							chown($file['name'],$file['stat'][4]);
							chgrp($file['name'],$file['stat'][5]);
						}
					}
					else if($this->options['overwrite'] == 0 && file_exists($file['name']))
					{
						$this->error[] = "{$file['name']} already exists.";
					}
					else if($new = @fopen($file['name'],"wb"))
					{
						fwrite($new,fread($fp,$file['stat'][7]));
						fread($fp,(512 - $file['stat'][7] % 512) == 512? 0 : (512 - $file['stat'][7] % 512));
						fclose($new);
						chmod($file['name'],$file['stat'][2]);
						chown($file['name'],$file['stat'][4]);
						chgrp($file['name'],$file['stat'][5]);
					}
					else
					{
						$this->print_error("FileZip::extract_files: ²»ÄÜ´ò¿ª {$file['name']} ÒÔ±¸Ğ´²Ù×÷.");
					}
				}
				unset($file);
			}
		}
		else
		{
			$this->print_error("FileZip::extract_files: ²»ÄÜ´ò¿ª {$this->options['name']}");
		}

		chdir($pwd);
	}

	function open_archive()
	{
		return @fopen($this->options['name'],"rb");
	}

} //end class

//=============================================================================
//½¨Á¢gzipÑ¹Ëõ°ü
class gzip_file extends tar_file
{
	function gzip_file($name)
	{
		$this->tar_file($name);
		$this->options['type'] = "gzip";
	}

	function create_gzip()
	{
		if($this->options['inmemory'] == 0)
		{
			$pwd = getcwd();
			chdir($this->options['basedir']);
			if($fp = gzopen($this->options['name'],"wb{$this->options['level']}"))
			{
				fseek($this->archive,0);
				while($temp = fread($this->archive,1048576))
				{
					gzwrite($fp,$temp);
				}
				gzclose($fp);
				chdir($pwd);
			}
			else
			{
				$this->print_error("FileZip::create_gzip: ²»ÄÜ´ò¿ª {$this->options['name']} ÒÔ±¸Ğ´²Ù×÷.");
				chdir($pwd);
				return 0;
			}
		}
		else
		{
			$this->archive = gzencode($this->archive,$this->options['level']);
		}

		return 1;
	}

	function open_archive()
	{
		return @gzopen($this->options['name'],"rb");
	}
}//end class

//=============================================================================
//½¨Á¢bzipÑ¹Ëõ°ü
class bzip_file extends tar_file
{
	function bzip_file($name)
	{
		$this->tar_file($name);
		$this->options['type'] = "bzip";
	}

	function create_bzip()
	{
		if($this->options['inmemory'] == 0)
		{
			$pwd = getcwd();
			chdir($this->options['basedir']);
			if($fp = bzopen($this->options['name'],"wb"))
			{
				fseek($this->archive,0);
				while($temp = fread($this->archive,1048576))
				{
					bzwrite($fp,$temp);
				}
				bzclose($fp);
				chdir($pwd);
			}
			else
			{
				$this->print_error("FileZip::create_bzip: ²»ÄÜ´ò¿ª {$this->options['name']} ÒÔ±¸Ğ´²Ù×÷.");
				chdir($pwd);
				return 0;
			}
		}
		else
		{
			$this->archive = bzcompress($this->archive,$this->options['level']);
		}

		return 1;
	}

	function open_archive()
	{
		return @bzopen($this->options['name'],"rb");
	}

}//end class

//=============================================================================
//½¨Á¢zipÑ¹Ëõ°ü
class zip_file extends archive
{
	function zip_file($name)
	{
		$this->archive($name);
		$this->options['type'] = "zip";
	}

	function create_zip()
	{
		$files = 0;
		$offset = 0;
		$central = "";

		if(!empty($this->options['sfx']))
		{
			if($fp = @fopen($this->options['sfx'],"rb"))
			{
				$temp = fread($fp,filesize($this->options['sfx']));
				fclose($fp);
				$this->add_data($temp);
				$offset += strlen($temp);
				unset($temp);
			}
			else
			{
				$this->print_error("FileZip::create_zip: ²»ÄÜ´ò¿ª sfx module ´Ó {$this->options['sfx']}.");
			}
		}

		$pwd = getcwd();
		chdir($this->options['basedir']);

		foreach($this->files as $current)
		{
			if($current['name'] == $this->options['name'])
			{
				continue;
			}

			$translate =  array('Ç'=>pack("C",128),'ü'=>pack("C",129),'é'=>pack("C",130),'â'=>pack("C",131),'ä'=>pack("C",132),
								'à'=>pack("C",133),'å'=>pack("C",134),'ç'=>pack("C",135),'ê'=>pack("C",136),'ë'=>pack("C",137),
								'è'=>pack("C",138),'ï'=>pack("C",139),'î'=>pack("C",140),'ì'=>pack("C",141),'Ä'=>pack("C",142),
								'Å'=>pack("C",143),'É'=>pack("C",144),'æ'=>pack("C",145),'Æ'=>pack("C",146),'ô'=>pack("C",147),
								'ö'=>pack("C",148),'ò'=>pack("C",149),'û'=>pack("C",150),'ù'=>pack("C",151),'_'=>pack("C",152),
								'Ö'=>pack("C",153),'Ü'=>pack("C",154),'£'=>pack("C",156),'¥'=>pack("C",157),'_'=>pack("C",158),
								'ƒ'=>pack("C",159),'á'=>pack("C",160),'í'=>pack("C",161),'ó'=>pack("C",162),'ú'=>pack("C",163),
								'ñ'=>pack("C",164),'Ñ'=>pack("C",165));
			$current['name2'] = strtr($current['name2'],$translate);

			$timedate = explode(" ",date("Y n j G i s",$current['stat'][9]));
			$timedate = ($timedate[0] - 1980 << 25) | ($timedate[1] << 21) | ($timedate[2] << 16) | 
				($timedate[3] << 11) | ($timedate[4] << 5) | ($timedate[5]);

			$block = pack("VvvvV",0x04034b50,0x000A,0x0000,(isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate);

			if($current['stat'][7] == 0 && $current['type'] == 5)
			{
				$block .= pack("VVVvv",0x00000000,0x00000000,0x00000000,strlen($current['name2']) + 1,0x0000);
				$block .= $current['name2'] . "/";
				$this->add_data($block);
				$central .= pack("VvvvvVVVVvvvvvVV",0x02014b50,0x0014,$this->options['method'] == 0? 0x0000 : 0x000A,0x0000,
					(isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate,
					0x00000000,0x00000000,0x00000000,strlen($current['name2']) + 1,0x0000,0x0000,0x0000,0x0000,$current['type'] == 5? 0x00000010 : 0x00000000,$offset);
				$central .= $current['name2'] . "/";
				$files++;
				$offset += (31 + strlen($current['name2']));
			}
			else if($current['stat'][7] == 0)
			{
				$block .= pack("VVVvv",0x00000000,0x00000000,0x00000000,strlen($current['name2']),0x0000);
				$block .= $current['name2'];
				$this->add_data($block);
				$central .= pack("VvvvvVVVVvvvvvVV",0x02014b50,0x0014,$this->options['method'] == 0? 0x0000 : 0x000A,0x0000,
					(isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate,
					0x00000000,0x00000000,0x00000000,strlen($current['name2']),0x0000,0x0000,0x0000,0x0000,$current['type'] == 5? 0x00000010 : 0x00000000,$offset);
				$central .= $current['name2'];
				$files++;
				$offset += (30 + strlen($current['name2']));
			}
			else if($fp = @fopen($current['name'],"rb"))
			{
				$temp = fread($fp,$current['stat'][7]);
				fclose($fp);
				$crc32 = crc32($temp);
				if(!isset($current['method']) && $this->options['method'] == 1)
				{
					$temp = gzcompress($temp,$this->options['level']);
					$size = strlen($temp) - 6;
					$temp = substr($temp,2,$size);
				}
				else
				{
					$size = strlen($temp);
				}
				$block .= pack("VVVvv",$crc32,$size,$current['stat'][7],strlen($current['name2']),0x0000);
				$block .= $current['name2'];
				$this->add_data($block);
				$this->add_data($temp);
				unset($temp);
				$central .= pack("VvvvvVVVVvvvvvVV",0x02014b50,0x0014,$this->options['method'] == 0? 0x0000 : 0x000A,0x0000,
					(isset($current['method']) || $this->options['method'] == 0)? 0x0000 : 0x0008,$timedate,
					$crc32,$size,$current['stat'][7],strlen($current['name2']),0x0000,0x0000,0x0000,0x0000,0x00000000,$offset);
				$central .= $current['name2'];
				$files++;
				$offset += (30 + strlen($current['name2']) + $size);
			}
			else
			{
				$this->print_error("FileZip::create_zip: ²»ÄÜ´ò¿ªÎÄ¼ş {$current['name']} ÒÔ±¸¶ÁÈ¡. Ã»ÓĞ±»Ìí¼Ó.");
			}
		}

		$this->add_data($central);

		$this->add_data(pack("VvvvvVVv",0x06054b50,0x0000,0x0000,$files,$files,strlen($central),$offset,
			!empty($this->options['comment'])? strlen($this->options['comment']) : 0x0000));

		if(!empty($this->options['comment']))
		{
			$this->add_data($this->options['comment']);
		}

		chdir($pwd);

		return 1;
	}

} //end class
//=============================================================================
?>