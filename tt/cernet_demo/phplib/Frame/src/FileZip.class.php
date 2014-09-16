<?php
/*
---------------------------------------------------------------------
- ��Ŀ: DNS phpsea
- �汾: 1.5
- �ļ���:FileZip.class.php
- ԭ����:Devin Doucette
- ������:indraw
- ��д����:2004/11/4
- ��Ҫ����:TAR/GZIP/BZIP2/ZIP �ļ������༯
- ���л���:php4������
- �޸ļ�¼:2004/11/4��indraw��������
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
	set_options($options)    //����ѹ������
	create_archive()         //ִ��ѹ������
	add_data($data)          //��ѹ������д����
	make_list()              //��ȡ���н�Ҫѹ�����ļ�
	add_files($list)         //�����ļ�����
	exclude_files($list)     //����ѹ��ʱ���˵��ļ�����
	store_files($list)       //������Щ�ļ���ѹ����ֻ�Ǵ���洢
	list_files($list)        //��ȡ�ļ�ȫ·��
	parse_dir($dirname)      //��ʽ��Ŀ¼
	sort_files($a,$b)        //�ļ�����
	download_file()          //����ѹ����

	archive($name)           //������
	tar_file($name)          //tar��
	gzip_file($name)         //gzip��
	bzip_file($name)         //bzip��
	zip_file($name)          //zip��
*/

//===================================================================
class archive
{
	var $show_errors = false;			//�Ƿ���ʾ������Ϣ

	function archive($name)
	{
		$this->options = array(
			'basedir'=>".",				//ѹ����·��
			'name'=>$name,				//ѹ������
			'prepend'=>"",				//Ԥ����·��
			'inmemory'=>0,				//�Ƿ�ѹ�������ڴ���
			'overwrite'=>0,				//���ѹ���������Ƿ񸲸�
			'recurse'=>1,				//�Ƿ���õݹ�Ŀ¼ѹ��
			'storepaths'=>1,			//�Ƿ�ѹ��Ŀ¼�ṹ
			'level'=>3,					//ѹ����
			'method'=>1,				//???(1�ļ�С��0�ļ���)
			'sfx'=>"",					//???
			'type'=>"",					//����
			'comment'=>""				//ע��
		);
		$this->files = array();
		$this->exclude = array();
		$this->storeonly = array();
		$this->error = array();
	}
	/*
	-----------------------------------------------------------
	��������:set_options($options)
	��Ҫ����:����ѹ������
	����:array (�������ò鿴����$this->options)
	���:void
	�޸���־:------
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
	��������:create_archive()
	��Ҫ����:ִ��ѹ������
	����:void
	���:boolean
	�޸���־:------
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
				$this->print_error("FileZip::create_archive: ѹ���� {$this->options['name']} �Ѿ�����.");
				chdir($pwd);
				return 0;
			}
			else if($this->archive = @fopen($this->options['name'] . ($this->options['type'] == "gzip" || $this->options['type'] == "bzip"? ".tmp" : ""),"wb+"))
			{
				chdir($pwd);
			}
			else
			{
				$this->print_error("FileZip::create_archive: ���ܴ�ѹ���� {$this->options['name']} ����д����.");
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
				$this->print_error("FileZip::create_archive: ��������ѹ���� zip �ļ�.");
				return 0;
			}
			break;
		case "bzip":
			if(!$this->create_tar())
			{
				$this->print_error("FileZip::create_archive: ��������ѹ���� tar �ļ�.");
				return 0;
			}
			if(!$this->create_bzip())
			{
				$this->print_error("FileZip::create_archive: ��������ѹ���� bzip2 �ļ�.");
				return 0;
			}
			break;
		case "gzip":
			if(!$this->create_tar())
			{
				$this->print_error("FileZip::create_archive: ��������ѹ���� tar �ļ�.");
				return 0;
			}
			if(!$this->create_gzip())
			{
				$this->print_error("FileZip::create_archive: ��������ѹ���� gzip �ļ�.");
				return 0;
			}
			break;
		case "tar":
			if(!$this->create_tar())
			{
				$this->print_error("FileZip::create_archive: ��������ѹ���� tar �ļ�.");
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
	��������:add_data($data)
	��Ҫ����:��ѹ������д����
	����:string
	���:void
	�޸���־:------
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
	��������:make_list()
	��Ҫ����:��ȡ���н�Ҫѹ�����ļ�
	����:void
	���:array
	�޸���־:------
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
	��������:add_files($list) 
	��Ҫ����:�����ļ�����
	����:array
	���:void (���������Ը�ֵ)
	�޸���־:------
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
	��������:exclude_files($list)
	��Ҫ����:����ѹ��ʱ���˵��ļ�����
	����:array
	���:void
	�޸���־:------
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
	��������:store_files($list)
	��Ҫ����:������Щ�ļ���ѹ����ֻ�Ǵ���洢
	����:array
	���:void
	�޸���־:------
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
	��������:list_files($list)
	��Ҫ����:��ȡ�ļ�ȫ·��
	����:array
	���:array
	�޸���־:------
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
	��������:parse_dir($dirname)
	��Ҫ����:��ʽ��Ŀ¼
	����:string
	���:string
	�޸���־:------
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
	��������:sort_files($a,$b)
	��Ҫ����:�ļ�����
	����:---
	���:---
	�޸���־:------
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
	��������:download_file()
	��Ҫ����:����ѹ����
	����:void
	���:��λ��������
	�޸���־:------
	-----------------------------------------------------------
	*/
	function download_file()
	{
		if($this->options['inmemory'] == 0)
		{
			$this->print_error("FileZip::download_file: ֻ�е�ѹ�������ڴ��е�ʱ�򣬲������� download_file() �������ء� ����ѹ���������ڴ��У�ִ��ѹ�����ٶȷǳ��졣");
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
	��������:print_error($str = "")
	��Ҫ����:��ʾ����������Ϣ
	����:string 
	���:echo or false
	�޸���־:------
	-----------------------------------------------------------
	*/
	function print_error($str = "")
	{
		//����ȫ�ֱ���$PHPSEA_ERROR..
		global $PHPSEA_ERROR;
		$PHPSEA_ERROR['FileZip_Error'] = $str;
	
		//�ж��Ƿ���ʾ�������..
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
//����tarѹ����
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
					$this->print_error("FileZip::create_tar: ������� {$path}{$current['name2']} ��ѹ�����У���Ϊ�ļ���̫����");
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
				$this->print_error("FileZip::create_tar: ���ܴ� {$current['name']} �Ա���ȡ. û�б�����.");
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
					$this->print_error("FileZip::extract_files: ����ⲻ֧������tarѹ����ʽ��");
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
					$this->print_error("FileZip::extract_files: ���ܽ�ѹ�� {$this->options['name']}, �ļ�������.");
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
						$this->print_error("FileZip::extract_files: ���ܴ� {$file['name']} �Ա�д����.");
					}
				}
				unset($file);
			}
		}
		else
		{
			$this->print_error("FileZip::extract_files: ���ܴ� {$this->options['name']}");
		}

		chdir($pwd);
	}

	function open_archive()
	{
		return @fopen($this->options['name'],"rb");
	}

} //end class

//=============================================================================
//����gzipѹ����
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
				$this->print_error("FileZip::create_gzip: ���ܴ� {$this->options['name']} �Ա�д����.");
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
//����bzipѹ����
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
				$this->print_error("FileZip::create_bzip: ���ܴ� {$this->options['name']} �Ա�д����.");
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
//����zipѹ����
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
				$this->print_error("FileZip::create_zip: ���ܴ� sfx module �� {$this->options['sfx']}.");
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

			$translate =  array('�'=>pack("C",128),'�'=>pack("C",129),'�'=>pack("C",130),'�'=>pack("C",131),'�'=>pack("C",132),
								'�'=>pack("C",133),'�'=>pack("C",134),'�'=>pack("C",135),'�'=>pack("C",136),'�'=>pack("C",137),
								'�'=>pack("C",138),'�'=>pack("C",139),'�'=>pack("C",140),'�'=>pack("C",141),'�'=>pack("C",142),
								'�'=>pack("C",143),'�'=>pack("C",144),'�'=>pack("C",145),'�'=>pack("C",146),'�'=>pack("C",147),
								'�'=>pack("C",148),'�'=>pack("C",149),'�'=>pack("C",150),'�'=>pack("C",151),'_'=>pack("C",152),
								'�'=>pack("C",153),'�'=>pack("C",154),'�'=>pack("C",156),'�'=>pack("C",157),'_'=>pack("C",158),
								'�'=>pack("C",159),'�'=>pack("C",160),'�'=>pack("C",161),'�'=>pack("C",162),'�'=>pack("C",163),
								'�'=>pack("C",164),'�'=>pack("C",165));
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
				$this->print_error("FileZip::create_zip: ���ܴ��ļ� {$current['name']} �Ա���ȡ. û�б�����.");
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