<?php
function ImageResize($srcFile,$toW,$toH,$toFile="")
{
   if($toFile==""){ $toFile = $srcFile; }
   $info = "";
   $data = GetImageSize($srcFile,$info);
   switch ($data[2])
   {
    case 1:
      if(!function_exists("imagecreatefromgif")){
      echo "你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式！<a href='javascript:go(-1);'>返回</a>";
      exit();
      }
      $im = ImageCreateFromGIF($srcFile);
      break;
    case 2:
      if(!function_exists("imagecreatefromjpeg")){
      echo "你的GD库不能使用jpeg格式的图片，请使用其它格式的图片！<a href='javascript:go(-1);'>返回</a>";
      exit();
      }
      $im = ImageCreateFromJpeg($srcFile);
      break;
    case 3:
      $im = ImageCreateFromPNG($srcFile);
      break;
}
$srcW=ImageSX($im);
$srcH=ImageSY($im);
$toWH=$toW/$toH;
$srcWH=$srcW/$srcH;
if($toWH<=$srcWH){
       $ftoW=$toW;
       $ftoH=$ftoW*($srcH/$srcW);
}
else{
      $ftoH=$toH;
      $ftoW=$ftoH*($srcW/$srcH);
}
if($srcW>$toW||$srcH>$toH)
{
     if(function_exists("imagecreatetruecolor")){
        @$ni = ImageCreateTrueColor($ftoW,$ftoH);
        if($ni) ImageCopyResampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
        else{
        $ni=ImageCreate($ftoW,$ftoH);
          ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
        }
     }else{
        $ni=ImageCreate($ftoW,$ftoH);
       ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
     }
     if(function_exists('imagejpeg')) ImageJpeg($ni,$toFile);
     else ImagePNG($ni,$toFile);
     ImageDestroy($ni);
}
ImageDestroy($im);
}
?>