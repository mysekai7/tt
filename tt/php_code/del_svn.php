<?php
//定义文件扩展名
define('EXT','chaozi');
/**
*Filie:File_DIR_Opration 文件目录删除方法
 * 功能：一个项目中所有的.svn目录
 * 可以根据需要扩展
 *@param $path 文件目录
 * return true
*/
function delete_file($path) {
    if(! is_writable($path))
    {
        if(! chmod($path,0777)) {
            echo '文件操作失败！~';exit();
        }
    }
    $dh = opendir($path);
    while(($file = readdir($dh)) !== false) {
        if(($file !=".") && ($file !="..")) {
            if(is_dir($path.'/'.$file)) {
                if($file == '.svn') {
                    full_rmdir($path.'/'.$file);
                    continue;
                }
                delete_file($path.'/'.$file);
            }
        }
    }
    return false;
}
/**
 * 针对目录所有的删除
 * @param $dir 目录路径
 *
 */
function full_rmdir( $dir ) {
    if( !is_writable( $dir ))
    {
        if( !chmod( $dir ,0777))
        {
            return false;
        }
    }
    $dh = opendir( $dir );
    while( FALSE !== ( $entry = readdir( $dh ) ))
    {
        if( $entry =='.' || $entry == '..')
        {
            continue;
        }
        $entry = $dir . '/' . $entry;
        if( is_dir($entry) )
        {
            if( !full_rmdir( $entry ))
            {
                return false;
            }
            continue;
        }
        if( !@unlink( $entry ))
        {
            //如果不能删除修改文件权限，再删除，如果不能修改文件权限报错！~
            if(! is_writable($entry))
            {
                if(! chmod($entry,0777))
                {
                    echo 'not chmod!~';
                }
            }
            if(! unlink($entry))
            {
                echo "##Error:file-".$entry." is not person";
            }
            echo "<br />DELETE -file:" . $entry;
            continue;
        }
    }
   //这里判断是否删除目录
	closedir($dh);
    if(rmdir( $dir ))
    {
        echo "<br /><font color=red>" . $dir."</font>";
        $entry = $dir;
    }
    return true;
}
/**
*获取文件的扩展名
*/
function get_file_ext($filename)
{
    $ext = pathinfo($filename);
    if(empty($ext['extension']))
    {
        die('Unknow file extension!');
    }
    return $ext['extension'];
}
$dir = "D:/www/OpenSource/frog";
$filename = $dir.'/index.chaozi';
delete_file($dir);
echo get_file_ext($filename);

//full_rmdir( $dir );
?>