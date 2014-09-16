<?php
/**
 *  author: 4kychao
 *  blog: blog.sk80.com
 */
/**
 *  生成缩略图
 *  $src <string> 源文件名
 *  $destination <string> 输出文件名
 *  $max_w <int> 自定义宽
 *  $max_h <int> 自定义高
 */
function resize_img($src, $destination='', $max_w, $max_h)
{
    if(! file_exists($src))
    {
        return FALSE;
    }

    $max_w = isset($max_w) ? $max_w : 80;
    $max_h = isset($max_h) ? $max_h : 80;

    list($w, $h, $type) = (getimagesize($src));

    if( $w==0 || $h==0)
    {
        return FALSE;
    }

    if( $type!=IMAGETYPE_GIF && $type!=IMAGETYPE_JPEG && $type!=IMAGETYPE_PNG)
    {
        return FALSE;
    }

    $src_copy = FALSE;
    switch($type)
    {
        case IMAGETYPE_GIF:
            $src_copy = imagecreatefromgif($src);
            break;
        case IMAGETYPE_JPEG:
            $src_copy = imagecreatefromjpeg($src);
            break;
        case IMAGETYPE_PNG:
            $src_copy = imagecreatefrompng($src);
            break;
    }
    if(!$src_copy)
    {
        return FALSE;
    }

    //目标图片大小
    $dst = imagecreatetruecolor($max_w, $max_h);
    $white = imagecolorallocate($dst, 255, 255, 255);//为一副图像分配颜色
    imagefill($dst, 0, 0, $white);

    $new_w = $w;
    $new_h = $h;

    if($w > $max_w)
    {
        $new_w = $max_w;
        $new_h = ($max_w / $w) * $h;
    }

    if($h > $max_h)
    {
        $new_h = $max_h;
        $new_w = ($max_h / $h) * $w;
    }

    //偏移
    $dst_x = ($max_w - $new_w)/2;
    $dst_y = ($max_h - $new_h)/2;

    $res = imagecopyresampled($dst, $src_copy, $dst_x, $dst_y, 0, 0, $new_w, $new_h, $w, $h);

    //output
    header('Content-type: image/jpeg');
    imagejpeg($dst, null, 100);
/*
    if(!$res)
    {
        imagedestroy($src_copy);
        imagedestroy($dst);
        return FALSE;
    }
    $res = FALSE;

    switch($type) {
        case IMAGETYPE_GIF:
            $res	= imagegif($dst, $destination);
            break;
        case IMAGETYPE_JPEG:
            $res	= imagejpeg($dst, $destination, 100);
            break;
        case IMAGETYPE_PNG:
            $res	= imagepng($dst, $destination);
            break;
    }
    imagedestroy($src_copy);
    imagedestroy($dst);
    if( ! $res ) {
        return FALSE;
    }

    if( ! file_exists($destination) ) {
        return FALSE;
    }
    chmod( $destination, 0777 );
    return TRUE;
    */
}

resize_img('aaa.jpg', '', 120, 80);



//demo
//if(resize_img('aaa.jpg', 'p2.jpg', 120, 80))
{
    //echo 'OK';
}