<?php
	class UpFile
	{
		public  $FilePath = "./images";
		public  $FileType = array("image/jpeg","image/bmp","image/gif");
		public  $FileSize = 1000000;

		function __construct($InputName)
		{
			$this->File = $InputName;
		}
		function UpLoadFile()
		{
			$this->UpType = $this->File["type"];
			$this->UpName = $this->File["name"];
			$this->UpTmp_Name = $this->File["tmp_name"];
			$this->UpSize = $this->File["size"];
			if($this->UpSize > $this->FileSize)
			{
				echo "<script>";
				echo "alert('上传文件超过规定大小范围!');";
				echo "location.href='javascript:history.go(-1)';";
				echo "</script>";
			}
			if(!in_array($this->UpType,$this->FileType))
			{
				echo "<script>";
				echo "alert('不支持此类文件上传!');";
				echo "location.href='javascript:history.go(-1)';";
				echo "</script>";
			}
			if(!file_exists($this->FilePath))
			{
				mkdir($this->FilePath);
			}
			if($this->File["error"]==0)
			{
				$this->FileNameType = pathinfo($this->UpName);
				$this->FileNameType = $this->FileNameType["extension"];
				$this->FileName = $this->FilePath . "/" ."S_". date("Ymdhis") . "." .$this->FileNameType;
				if(move_uploaded_file($this->UpTmp_Name,$this->FileName))
				{
					echo "<script>";
					echo "alert('图片上传成功!');";
					echo "location.href='javascript:history.go(-1)';";
					echo "</script>";
				}else
				{
					echo "<script>";
					echo "alert('图片上传失败!');";
					echo "location.href='javascript:history.go(-1)';";
					echo "</script>";
				}
			}else
			{
				echo "<script>";
					echo "alert('图片上传失败!');";
					echo "location.href='javascript:history.go(-1)';";
					echo "</script>";
			}
			return $this->FileName;
		}
	}

?>
<!--
<form enctype="multipart/form-data" action="demo.php" method="POST">
	<input type="file" name="UpFile"/>
	<input type="submit" value="�ϴ�" name="submit"/>
</form>
-->