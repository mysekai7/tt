<?php
//水印

Header("Content-type: image/png");      /*通知浏览器,要输出图像*/
$im       = imagecreate(400 , 300);        /*定义图像的大小*/
$gray     = ImageColorAllocate($im , 235 , 235 , 235);
$pink     = ImageColorAllocate($im, 255 , 128 , 255);
/*
$fontfile = "C:\WINDOWS\Fonts\SIMHEI.TTF";
不好意思,这句老是粘上后一提交就丢了,不知道是怎么回事,想测试的朋友们将注释去了现测试吧
*/
/* $fontfile 字体的路径,视操作系统而定,可以是 simhei.ttf(黑体) , SIMKAI.TTF(楷体) , SIMFANG.TTF(仿宋) ,SIMSUN.TTC(宋体&新宋体) 等 GD 支持的中文字体*/
$str   = iconv('GB2312','UTF-8','php爱好者phpfans.net');     /*将 gb2312 的字符集转换成 UTF-8 的字符*/
ImageTTFText($im, 30, 0, 50, 140, $pink , $fontfile , $str);
/* 加入中文水印 */
Imagepng($im);
ImageDestroy($im);
?>

<?php
header("Content-type:image/png");
$pic=imagecreatefromjpeg("434.jpg");
$color1 = imagecolorallocate($pic,255,255,255);
$font1  = "C:\WINDOWS\Fonts\STZHONGS.TTF";
$info = "php爱好者phpfans.net";
$info = iconv("GB2312","UTF-8",$info);
imagettftext($pic,12,0,10,20,$color1,$font1,$info);
imagepng($pic);
imagedestroy ($pic);
?>