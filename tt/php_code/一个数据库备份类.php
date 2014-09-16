<?php
/*******************************************************
**文 件 名:DBManagement.php
**Copyright (c)2007-2008 ** 网站开发部
**文件编号:wtcms-lcq-db-004
**创 建 人:林超旗
**日    期:2007-04-07
**修 改 人:
**日    期:
**描    述:实现数据的导入导出,数据表结构的导入导出
********************************************************/

        //
        //包含Mysql数据库操作文件
        //
        require_once("MysqlDB.php");

/*******************************************************
**类    名:MysqlDB
**类 编 号:lcq-DB-003
**作    用:数据库链接的建立,数据操作,获取字段个数,记录条数等
**作    者:林超旗
**编写日期:(2007-04-07)
********************************************************/
class DBManagement implements IDBManagement
{
        //
        //当前数据库中所有的数据表的名字
        //
        private $TablesName;
        //
        //默认路径
        //
        private $DefaultPath;
        //
        //当前要操作的数据库名
        //
        private $DatabaseName;
        //
        //操作数据库的对象
        //
        private $db;

/*******************************************************
**方 法 名:__construct
**功能描述:创建一个DBManagement的对象
**输入参数:$_DatabaseName-string<要操作的数据库名,如果为空则从配置文件中读取>
**        $_DefaultPath-string<存储数据的默认路径,如果为空则从配置文件中读取>
**输出参数:无
**返 回 值:无
**作    者:林超旗
**日    期:2007-04-09
**修 改 人:
**日    期:
********************************************************/
        function __construct($_DatabaseName="",$_DefaultPath="")//
        {
                require("config.inc.php");
                if(!$_DatabaseName)        {$this->DatabaseName=$dbName;}
                else {$this->DatabaseName=$_DatabaseName;}

                if(!$_DefaultPath) {$this->DefaultPath=$defaultPath;}
                else {$this->DefaultPath=$_DefaultPath;}
                $path=realpath($this->DefaultPath);
                $this->DefaultPath=str_replace("\\","/",$path);
                //$this->db=new DBFactory();
                $this->db=new MysqlDB();
        }

/*******************************************************
**方 法 名:GetTablesName
**功能描述:获取$this->Database的所有数据表的名字
**输入参数:无
**输出参数:无
**返 回 值:-array <$this->TablesName:$this->Database的所有数据表的名字>
**作    者:林超旗
**日    期:2007-04-09
**修 改 人:
**日    期:
********************************************************/
        protected function GetTablesName()
        {
                $result=$this->db->Query("show table status");
                while($Row=$this->db->NextRecord($result))
                {
                        $this->TablesName[]=$Row["Name"];
                }
                return $this->TablesName;
        }

/*******************************************************
**方 法 名:GetDataFileName
**功能描述:获取与$this->Database的所有数据表对应的数据文件的物理文件名
**输入参数:无
**输出参数:无
**返 回 值:-array <$DataFilesName:$与$this->Database的所有数据表对应的数据文件的物理文件名>
**作    者:林超旗
**日    期:2007-04-09
**修 改 人:
**日    期:
********************************************************/
        protected function GetDataFileName()
        {
                $this->GetTablesName();
                $count=count($this->GetTablesName());
                for ($i=0;$i<$count;$i++)
                {
                        $DataFilesName[]=$this->DefaultPath."/".$this->TablesName[$i].".txt";
                        //echo $DataFilesName[$i];
                }
                return $DataFilesName;
        }

/*******************************************************
**方 法 名:SaveTableStructure
**功能描述:保存数据表的结构到install.sql文件中,目标路径为$DefaultPath
**输入参数:无
**输出参数:无
**返 回 值:- bool<返回为true表示保存成功;
**                为false表示保存失败>
**作    者:林超旗
**日    期:2007-04-09
**修 改 人:
**日    期:
********************************************************/
        protected function SaveTableStructure($text)
        {
                $fileName=$this->DefaultPath."/Install.sql";
                //if(file_exists($fileName))
                //{
                //        unlink($fileName);
                //}
                //echo $text;
                $fp=fopen($fileName,"w+");
                fwrite($fp,$text);
        }

/*******************************************************
**方 法 名:RestoreTableStructure
**功能描述:备份$this->Database中所有数据表的结构
**输入参数:无
**输出参数:无
**返 回 值:- bool<返回为true表示所有数据表的结构备份成功;
**                为false表示全部或部分数据表的结构备份失败>
**作    者:林超旗
**日    期:2007-04-09
**修 改 人:
**日    期:
********************************************************/
        protected function BackupTableStructure()
        {
                $i=0;
                $sqlText="";

                $this->GetTablesName();
                $count=count($this->TablesName);
                //
                //取出所有数据表的结构
                //
                while($i<$count)
                {
                        $tableName=$this->TablesName[$i];
                        $result=$this->db->Query("show create table $tableName");
                        $this->db->NextRecord($result);
                        //
                        //取出成生表的SQL语句
                        //
                        $sqlText.="DROP TABLE IF EXISTS `".$tableName."`; ";//`
                        $sqlText.=$this->db->GetField(1)."; ";
                        $i++;
                }
                $sqlText=str_replace("\r","",$sqlText);
                $sqlText=str_replace("\n","",$sqlText);
                //$sqlText=str_replace("'","`",$sqlText);
                $this->SaveTableStructure($sqlText);
        }

/*******************************************************
**方 法 名:RestoreTableStructure
**功能描述:还原$this->Database中所有数据表的结构
**输入参数:无
**输出参数:无
**返 回 值:- bool<返回为true表示所有数据表的结构还原成功;
**                为false表示全部或部分数据表的结构还原失败>
**作    者:林超旗
**日    期:2007-04-09
**修 改 人:
**日    期:
********************************************************/
        protected function RestoreTableStructure()
        {
                $fileName=$this->DefaultPath."/Install.sql";
                if(!file_exists($fileName)){echo "找不到表结构文件,请确认你以前做过备份!";exit;}
                $fp=fopen($fileName,"r");

                $sqlText=fread($fp,filesize($fileName));
                //$sqlText=str_replace("\r","",$sqlText);
                //$sqlText=str_replace("\n","",$sqlText);
                $sqlArray=explode("; ",$sqlText);
                try
                {
                        $count=count($sqlArray);
                        //
                        //数组的最后一个为";",是一个无用的语句,
                        //
                        for($i=1;$i<$count;$i++)
                        {
                                $sql=$sqlArray[$i-1].";";
                                $result=$this->db->ExecuteSQL($sql);
                                if(!mysql_errno())
                                {
                                        if($i%2==0){echo "数据表".($i/2)."的结构恢复成功!\n";}
                                }
                                else
                                {
                                        if($i%2==0)
                                        {
                                                echo "数据表".($i/2)."的结构恢复失败!\n";
                                                exit();
                                        }
                                }
                        }
                }
                catch(Exception $e)
                {
                        $this->db->ShowError($e->getMessage());
                        $this->db->Close();
                        return false;
                }
        }

/*******************************************************
**方 法 名:ImportData
**功能描述:导入$this->Database中所有数据表的数据从与其同名的.txt文件中,源路径为$DefaultPath
**输入参数:无
**输出参数:无
**返 回 值:- bool<返回为true表示所有数据表的数据导入成功;
**                为false表示全部或部分数据表的数据导入失败>
**作    者:林超旗
**日    期:2007-04-09
**修 改 人:
**日    期:
********************************************************/
        function ImportData()
        {
                $DataFilesName=$this->GetDataFileName();
                $count=count($this->TablesName);
                //$this->db->ExecuteSQL("set character set gbk");
                for ($i=0;$i<$count;$i++)
                {
                        //$DataFilesName[$i]=str_replace("\\","/",$DataFilesName[$i])
                        //echo $DataFilesName[$i];
                        $sqlLoadData="load data infile '".$DataFilesName[$i]."'  into table ".$this->TablesName[$i];
                        $sqlCleanData="delete from ".$this->TablesName[$i];
                        //echo $sql."\n";
                        try
                        {
                                //$this->db->ExecuteSQL("set character set utf8");
                                //$this->db->ExecuteSQL("SET NAMES 'utf8'");
                                $this->db->ExecuteSQL($sqlCleanData);
                                $this->db->ExecuteSQL($sqlLoadData);
                                return true;
                                //echo "数据导入成功!";
                        }
                        catch (Exception $e)
                        {
                                $this->db->ShowError($e->getMessage());
                                return false;
                                exit();
                        }
                }
        }

/*******************************************************
**方 法 名:ExportData
**功能描述:导出$this->Database中所有数据表的数据到与其同名的.txt文件中,目标路径为$DefaultPath
**输入参数:无
**输出参数:无
**返 回 值:- bool<返回为true表示所有数据表的数据导出成功;
**                为false表示全部或部分数据表的数据导出失败>
**作    者:林超旗
**日    期:2007-04-09
**修 改 人:
**日    期:
********************************************************/
        function ExportData()
        {
                $DataFilesName=$this->GetDataFileName();
                $count=count($this->TablesName);
                try
                {
                        for ($i=0;$i<$count;$i++)
                        {
                                $sql="select * from ".$this->TablesName[$i]."  into outfile '".$DataFilesName[$i]."'";
                                if(file_exists($DataFilesName[$i]))
                                {
                                        unlink($DataFilesName[$i]);
                                }
                                //$this->db->ExecuteSQL("SET NAMES 'utf8'");
                                $this->db->ExecuteSQL($sql);
                        }
                        return true;
                        //echo "数据导出成功!";
                }
                catch (Exception $e)
                {
                        $this->db->ShowError($e->getMessage());
                        return false;
                        exit();
                }
        }

/*******************************************************
**方 法 名:BackupDatabase
**功能描述:备份$this->Database中所有数据表的结构和数据
**输入参数:无
**输出参数:无
**返 回 值:- bool<返回为true表示所有数据表的数据库备份成功;
**                为false表示全部或部分数据表的数据库备份失败>
**作    者:林超旗
**日    期:2007-04-10
**修 改 人:
**日    期:
********************************************************/
        function BackupDatabase()
        {
                try
                {
                        $this->BackupTableStructure();
                        $this->ExportData();
                        return true;
                }
                catch (Exception $e)
                {
                        $this->db->ShowError($e->getMessage());
                        return false;
                        exit();
                }
        }

/*******************************************************
**方 法 名:RestoreDatabase
**功能描述:还原$this->Database中所有数据表的结构和数据
**输入参数:无
**输出参数:无
**返 回 值:- bool<返回为true表示所有数据表的数据库还原成功;
**                为false表示全部或部分数据表的数据库还原失败>
**作    者:林超旗
**日    期:2007-04-10
**修 改 人:
**日    期:
********************************************************/
        function RestoreDatabase()
        {
                try
                {
                        $this->RestoreTableStructure();
                        $this->ImportData();
                        return true;
                }
                catch (Exception $e)
                {
                        $this->db->ShowError($e->getMessage());
                        return false;
                        exit();
                }
        }
}
?>