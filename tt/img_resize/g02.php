<?php 
	//创建一个画板
	$img=imagecreatetruecolor(300,300);
	
	//创建画笔
	$red=imagecolorallocate($img,255,0,0);
	$blue=imagecolorallocate($img,0,0,255);
	$green=imagecolorallocate($img,0,255,0);
	$black=imagecolorallocate($img,0,0,0);
	
	//开始作画
	imagefill($img,0,0,$green);
	imageline($img,10,10,10,150,$red);
	imagestring($img,6,20,10,"hello world",$black);
	
	imagefilledrectangle($img,20,50,30,150,$black);
	
	imagerectangle($img,40,70,50,150,$black);
	imagefill($img,45,75,$blue);
	
	//输出图像
	header("Content-Type:image/gif");
	imagegif($img);
	
	//销毁图片资源
	imagedestroy($img);
	
?>