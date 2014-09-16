<?php
/*
* Short Description.
* Cache For PHP5 Based On Xml Or Not
* Detail Description
* @version   1.0
* @access    public
* @author    Xinge(2006.7.31)
* @update    2006.8.1 15:12 By Xinge
*-----------------------------------
* example:
* $cache = new cache(10000,"../Include/Language/","Gb2312","test");
* $cache -> cacheCheck();
* echo date("Y-m-d H:i:s");
* $cache -> caching();
*/
class cache
{
/*** 默认缓存目录      ***/
public $CacheRoot      = "../CacheRoot/";
/*** 缓存更新时间秒数，默认为0,则为不缓存 ***/
public $CacheLimitTime = 0;
/*** 默认缓存文件名    ***/
public $CacheFileName  = "";
/*** 默认XML存放路径   ***/
private $XmlRoot       = "../Files/Xml/";
/*** 默认XML文件名     ***/
public $XmlSourceName  = "";
/*** 默认缓存扩展名    ***/
public $CacheFileExt   = "php";
/*=======================================
  @函数名:__construct
  @作  用:构造函数
  @参  数:$CacheLimitTime-------缓存更新时间
    $CacheRoot------------缓存目录
    $CacheFileName--------缓存文件名
    $XmlSourceName--------XML数据源文件名
  @返  回:无
  @备  注:缓存文件名及XML数据源文件名都不需要加后辍名
=======================================*/
function __construct($CacheLimitTime,$CacheRoot,$CacheFileName,$XmlSourceName)
{
  if (intval($CacheLimitTime))
  {
   $this->CacheLimitTime = $CacheLimitTime;
   $this->CacheRoot = $CacheRoot;
   if ($CacheFileName == "")
   {
    $this->CacheFileName = $CacheRoot.$this->autoCacheFileName();
   }
   else
   {
    $this->CacheFileName  = $CacheRoot.$CacheFileName.".".$this->CacheFileExt;
   }
   $this->XmlSourceName  = $this->XmlRoot.$XmlSourceName.".xml";
   //print $this->CacheFileName."<br>".$this->XmlSourceName;
   //exit;
  }
  ob_start();
}

/*=======================================
  @函数名:cacheCheck
  @作  用:检查缓存文件是否在设置更新时间之内($XmlSourceName不为空时则与XML数据源文件最后时间进行比较)
  @参  数:无
  @返  回:如果在更新时间之内则返回文件内容，反之则返回失败
  @备  注:无
=======================================*/
function cacheCheck()
{
  if (file_exists($this->CacheFileName))
  {
   $CacheFileMakeTime = $this->getFileCreateTime($this->CacheFileName);
   $CacheTime = $CacheFileMakeTime + $this->CacheLimitTime;
   if ($this->XmlSourceName == "") /*** 缓存内容不是来源于XML数据文件 ***/
   {
    if ($CacheTime > time())
    {
     print file_get_contents($this->CacheFileName);
     ob_end_flush();
     exit;
    }
   }
   else                            /*** 缓存内容是来源于XML数据文件   ***/
   {
    if (file_exists($this->XmlSourceName))
    {
     $XmlFileMakeTime = $this->getFileCreateTime($this->XmlSourceName);
    }
    else
    {
     showMsg("XML数据源不存在！","-1",0,3000);
     exit;
    }
    if ($CacheTime > time() && $CacheTime > $XmlFileMakeTime)
    {
     print file_get_contents($this->CacheFileName);
     ob_end_flush();
     exit;
    }
   }
  }
  else
  {
   return false;
  }
}

/*=======================================
  @函数名:caching
  @作  用:缓存文件或者输出静态
  @参  数:$StaticFileName-------静态文件名(含相对路径)
  @返  回:无
  @备  注:无
=======================================*/
function caching($StaticFileName = "")
{
  if ($this->cacheCheck() == false)
  {
   if ($this->CacheFileName)
   {
    $CacheContent = ob_get_contents();
    //echo $CacheContent;
    ob_end_flush();
   }
   if ($StaticFileName)
   {
    $this->saveFile($StaticFileName, $CacheContent);
   }
   if ($this->CacheLimitTime)
   {
    $this->saveFile($this->CacheFileName,$CacheContent);
   }
  }
}

/*=======================================
  @函数名:clearCache
  @作  用:清除缓存文件
  @参  数:$FileName-------------指定文件名(含函数),为all时则删除全部，为空时删除本身
  @返  回:清除成功返回true，反之返回false
  @备  注:无
=======================================*/
function clearCache($FileName = "all")
{
  if ($FileName != "all")
  {
   if ($FileName == "")
   {
    $FileName = $this->CacheFileName;
   }
   else
   {
    $FileName = $this->CacheRoot.$FileName.".".$this->CacheFileExt;
   }
   if (file_exists($FileName))
   {
    return @unlink($FileName);
   }
   else
   {
    return false;
   }
  }
  else
  {
   if (is_dir($this->CacheRoot))
   {
    if ($dir = @opendir($this->CacheRoot))
    {
     while ($file = @readdir($dir))
     {
      $check = is_dir($file);
      if (!$check)
      {
       @unlink($this->CacheRoot.$file);
      }
     }
     @closedir($dir);
     return true;
    }
    else
    {
     return false;
    }
   }
   else
   {
    return false;
   }
  }
}

/*=======================================
  @函数名:autoCacheFileName
  @作  用:根据当前动态文件生成缓存文件名
  @参  数:无
  @返  回:返回缓存文件名
  @备  注:无
=======================================*/
function autoCacheFileName()
{
  return $this->CacheRoot.strtoupper(md5($_SERVER["REQUEST_URI"])).".".$this->CacheFileExt;
}

/*=======================================
  @函数名:getFileCreateTime
  @作  用:缓存文件建立时间
  @参  数:$FileName-------------缓存文件名（含相对路径）
  @返  回:文件生成时间秒数，文件不存在返回0
  @备  注:无
=======================================*/
function getFileCreateTime($FileName)
{
  if (!trim($FileName)) return 0;
  if (file_exists($FileName))
  {
   return intval(filemtime($FileName));
  }
  else
  {
   return 0;
  }
}

/*=======================================
  @函数名:makeDir
  @作  用:连续建目录
  @参  数:$Dir------------------目录字符串
    $Mode-----------------模式
  @返  回:成功则返回true，否则返回错误提示信息
  @备  注:无
=======================================*/
function makeDir($Dir, $Mode = "0777")
{
  if (!$Dir) return 0;
  $Dir = str_replace("\\","/",$Dir);
  $mDir = "";
  foreach(explode("/",$Dir) as $val)
  {
   $mDir .= $val."/";
   if ($val == ".." || $val == ".") continue;
   if (!file_exists($mDir))
   {
    if (!@mkdir($mDir,$Mode))
    {
     showMsg("创建目录 [".$mDir."] 失败！","-1",0,3000);
     exit;
    }
   }
  }
  return true;
}

/*=======================================
  @函数名:saveFile
  @作  用:保存缓存文件
  @参  数:$FileName-------------缓存文件名（含相对路径）
    $text-----------------缓存内容
  @返  回:成功返回ture,失败返回false
  @备  注:无
=======================================*/
function saveFile($FileName,$text)
{
  if (!$FileName || !$text) return false;
  if ($this->makeDir(dirname($FileName)))
  {
   if ($fp = fopen($FileName,"w"))
   {
    if (@fwrite($fp,$text))
    {
     fclose($fp);
     return true;
    }
    else
    {
     fclose($fp);
     return false;
     }
   }
  }
  return false;
}
}
?>