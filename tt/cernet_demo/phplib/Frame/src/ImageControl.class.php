<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:ImageControl.class.php
- 原作者:indraw
- 整理者:indraw
- 编写日期:2004/11/11
- 简要描述:常用图片大小控制类。
- 运行环境:需要GD库支持，版本最佳为2.0以上，比如：2.0.28
- 修改记录:2004/11/11，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	$imm = new ImageControl('test.jpg', '.');
	$imm->make_thumb("180");
*/
/*
	ImageControl ($name = "",$directory = ".",$mode = "1")
	set_back($bg_red="0",$bg_green="0",$bg_blue="0")               //设置背景
	set_frame($fr_red="0",$fr_green="0",$fr_blue="0")              //设置边框
	get_image_name ()                                              //获取图片名字
	get_image_height ()                                            //获取图片高度
	get_image_path ()                                              //获取图片路径
	get_image_width ()                                             //获取图片宽度
	get_image_size ()                                              //获取图片大小
	get_thumb_name ()                                              //获取缩略图名
	get_image_type ()                                              //获取文件类型
	exist_thumb ($prefix = 'thb_')                                 //判断是否存在缩略图
	exist_image ()                                                 //判断文件是否存在
	make_thumb ($dimension = 100, $quality = 70, $prefix = "thb_") //生成缩略图
	html_thumb_image ()                                            //在ie中显示缩略图
	read_image_from_file($filename, $type)                         //从文件中读出图片
	write_image_to_file($im, $filename, $type, $quality)           //将图片写入到文件
*/

//=============================================================================
class ImageControl 
{

	var $name;                    //图片名
	var $directory;               //图片路径
	var $path;                    //图片路径+图片名
	var $width, $height, $type, $dimension;   //图片：宽 高 类型 文本字符串
	var $prefix;                  //缩小图片后的前缀
	var $exist;                   //判断文件是否存在

	var $bg_red;                  //前景色->RGB
	var $bg_green;                //前景色->RGB
	var $bg_blue;                 //前景色->RGB

	var $fr_red;                  //边框色->RGB
	var $fr_green;                //边框色->RGB
	var $fr_blue;                 //边框色->RGB

	var $max_size;                //是否强制大小
	var $max_fram;                //是否制作边框

	var $mode;                    //输出图片还是显示出来 1写入文件0显示出来

	/*
	-----------------------------------------------------------
	函数名称:ImageControl ($name = "",$directory = ".",$mode = "1") 
	简要描述:构造函数
	输入:mixed (文件名，文件路径，模式：1生成图片，0直接输出到浏览器)
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function ImageControl ($name = "",$directory = ".",$mode = "1") 
	{
		if (!is_dir ($directory))
			mkdir ($directory, 0775);
			$this->path = $directory."/".$name;
		if (file_exists ($this->path)) {
			$this->name	 	= $name;
			$this->directory	= $directory;
			$this->exist		= TRUE;
			$size				= GetImageSize($this->path);
			$this->width		= $size [0];
			$this->height		= $size [1];
			$this->type	 	= $size [2];
			$this->dimension 	= $size [3];

			$this->mode 	= $mode;

		} else {
			$this->name 		= $name;
			$this->directory 	= $directory;
			$this->exist		= FALSE;
			$this->width	 	= 0;
			$this->heigth 	= 0;
			$this->type		= "";
		}
	}

//-----------------------------------------------------------------------------
	/*
	-----------------------------------------------------------
	函数名称:set_back($bg_red="0",$bg_green="0",$bg_blue="0") 
	简要描述:设置背景颜色
	输入:mixed (这里设置的是三原色)
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_back($bg_red="0",$bg_green="0",$bg_blue="0") 
	{
		$this->bg_red	 	= $bg_red;
		$this->bg_green		= $bg_green;
		$this->bg_blue		= $bg_blue;
		$this->max_size		= 1;
	}

	/*
	-----------------------------------------------------------
	函数名称:set_frame($fr_red="0",$fr_green="0",$fr_blue="0") 
	简要描述:设置边框颜色颜色
	输入:mixed (这里设置的是三原色)
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_frame($fr_red="0",$fr_green="0",$fr_blue="0") 
	{
		$this->fr_red	 	= $fr_red;
		$this->fr_green		= $fr_green;
		$this->fr_blue		= $fr_blue;
		$this->max_fram		= 1;
	}

	/*
	-----------------------------------------------------------
	函数名称:exist_thumb ($prefix = 'thb_') 
	简要描述:判断缩略后的图片是否存在
	输入:string(缩略图前缀)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function exist_thumb ($prefix = 'thb_') 
	{
		if (file_exists ($this->directory."/$prefix".$this->name)) {
			$this->prefix = $prefix; 
			return TRUE;
		} else 
			return FALSE;
	}

	/*
	-----------------------------------------------------------
	函数名称:get_thumb_name () 
	简要描述:获取缩略图的名称
	输入:void
	输出:string
	修改日志:------
	-----------------------------------------------------------
	*/
	function get_thumb_name () 
	{
		$result = $this->prefix . $this->get_image_name();
		return $result;
	}
//-----------------------------------------------------------------------------
	/*
	-----------------------------------------------------------
	函数名称:---
	简要描述:返回原始文件名、高、宽、路径、大小、文本字符串，图片类型，原始文件是否存在
	输入:---
	输出:---
	修改日志:------
	-----------------------------------------------------------
	*/
	function get_image_name () 
	{
		return $this->name;
	}

	function get_image_height () 
	{
		return $this->height;
	}

	function get_image_width () 
	{
		return $this->width;
	}

	function get_image_path () 
	{
		return $this->path;
	}
	
	function get_image_size () 
	{
		$size = filesize ($this->path);
		return $size;
	}
	function get_image_dimension () 
	{
		return $this->dimension;
	}	

	function get_image_type ()
	{
		switch ($this->type) {
			case 1 :
				return "GIF";
			case 2 :
				return "Jpeg";
			case 3 :
			 	return "PNG";
			case 4 :
				return "SWF";
			case 5 :
				return "PSD";
			case 6 :
				return "BMP";
			case 7 :
				return "TIFF_II";
			case 8 :
				return "TIFF_MM";
			case 9 :
				return "JPC";
			case 10 :
				return "JP2";
			case 11 :
				return "JPX";	
		}
	}
	function exist_image () 
	{
		return $this->exist;
	}

//-----------------------------------------------------------------------------
	/*
	-----------------------------------------------------------
	函数名称:make_thumb ($dimension = 100, $quality = 70, $prefix = "thb_")
	简要描述:生成缩略图函数
	输入:mixed (图片长宽，生成图片质量，图象前缀)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function make_thumb ($dimension = 100, $quality = 70, $prefix = "thb_") 
	{
		
		$this->prefix = $prefix;
		$newHeight = $dimension;
		$newWidth	= $dimension;
		
 		if ($im = $this->read_image_from_file($this->path, $this->type)) {
			//固定长宽，如果不够，用背景替代
			if ($newHeight && ($this->width < $this->height)) {
				$newWidth = ($newHeight / $this->height) * $this->width;
			} else {
				$newHeight = ($newWidth / $this->width) * $this->height;
			}
			if( $this->max_size )
			{
				if ($this->width < $this->height)
				{
					$newxsize = $newHeight * ($this->width/$this->height);
					$adjustX = ($dimension - $newxsize)/2;
					$adjustY = 0;
				}
				else
				{
					$newysize = $newWidth / ($this->width/$this->height);
					$adjustX = 0;
					$adjustY = ($dimension - $newysize)/2;
				}
				if (function_exists("ImageCreateTrueColor")) {
					$im2 = ImageCreateTrueColor($dimension,$dimension);
				} else {
					$im2 = ImageCreate($dimension,$dimension);
				}
				$bgfill = imagecolorallocate( $im2, $this->bg_red, $this->bg_green, $this->bg_blue );	
				$frame = imagecolorallocate ( $im2, $this->fr_red, $this->fr_green, $this->fr_blue);
				imagefilledrectangle ( $im2, 0, 0, $dimension, $dimension, $bgfill);

				if (function_exists("ImageCopyResampled")) {
					ImageCopyResampled($im2,$im,$adjustX+2,$adjustY,0,0,$newWidth-4,$newHeight,$this->width,$this->height);
				} else {
					ImageCopyResized($im2,$im,$adjustX,$adjustY,0,0,$newWidth,$newHeight,$this->width,$this->height);
				}
				//是否显示边框
				if($this->max_fram)
				imagerectangle ( $im2, 1, 1, ($dimension-2), ($dimension-2), $frame);
			}//按原比例长宽
			else{
				if (function_exists("ImageCreateTrueColor")) {
					$im2 = ImageCreateTrueColor($newWidth,$newHeight);
				} else {
					$im2 = ImageCreate($newWidth,$newHeight);
				}
				if (function_exists("ImageCopyResampled")) {
					ImageCopyResampled($im2,$im,0,0,0,0,$newWidth,$newHeight,$this->width,$this->height);
				} else {
					ImageCopyResized($im2,$im,0,0,0,0,$newWidth,$newHeight,$this->width,$this->height);
				}
			}//end maxsize

			if ($this->write_image_to_file($im2, $this->directory."/".$this->prefix.$this->name, $this->type, $quality)) {
				return true;
			}
		}//end checkexist
	 }

	/*
	-----------------------------------------------------------
	函数名称:html_thumb_image ()
	简要描述:显示缩略图
	输入:void
	输出:void (直接在浏览器上显示缩略图)
	修改日志:------
	-----------------------------------------------------------
	*/
	function html_thumb_image () 
	{
		$html = "<a href=\"".$this->path."\"> <img src=\"".$this->directory."/".$this->get_thumb_name()."\" border=\"0\">";
		return $html;
	}	
	
	
	/*
	-----------------------------------------------------------
	函数名称:read_image_from_file($filename, $type)
	简要描述:将图片读入字符串
	输入:mixed (图片名称，类型)
	输出:boolean
	修改日志:------
	-----------------------------------------------------------
	*/
	function read_image_from_file($filename, $type) 
	{
		
		$imagetypes = imagetypes();

 		switch ($type) {
		case 1 :
		 	if ($imagetypes & IMG_GIF)
				return ImageCreateFromGIF($filename);
			else
				return FALSE;
		 	break;
		case 2 :
		 	if ($imagetypes & IMG_JPEG)
				return ImageCreateFromJPEG($filename);
			else
				return FALSE;
		 	break;
		case 3 :
		 	if ($imagetypes & IMG_PNG)
				return ImageCreateFromPNG($filename);
			else
				return FALSE;
		 	break;
		default:
		 	return FALSE;
 		}
 	}

	/*
	-----------------------------------------------------------
	函数名称:write_image_to_file($im, $filename, $type, $quality)
	简要描述:将图片写入文件
	输入:mixed (图片句柄，图象名称，类型，质量)
	输出:boolean or echo
	修改日志:------
	-----------------------------------------------------------
	*/
	function write_image_to_file($im, $filename, $type, $quality) 
	{
		if($this->mode){
			switch ($type) {
			case 1 :
				return ImageGif($im, $filename);
				break;	 
			case 2 :
				return ImageJpeg($im, $filename, $quality);
				break;	 
			case 3 :
				return ImagePNG($im, $filename);
				break;
			default:
				return false;
			}
			return false;
		}
		else{
			header("Content-type: image/jpeg");
			imagejpeg($im);
		}
	}
	

}//end class
//=============================================================================