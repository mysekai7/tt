<?php

class ImageReSize {

	function __construct($SrcFile="",$NewWidth="",$NewHeiht="",$Path="")
	{
		$this->SrcFile = $SrcFile;
		$this->NewWidth = $NewWidth;
		$this->NewHeiht = $NewHeiht;
		$this->Path = $Path;
	}
	function GoReSize()
	{
		if(!file_exists($this->SrcFile)) // 判断要生成缩略图的文件是否存在
		{
			echo "图片不存在请检查！";
		}else
		{
				$this->File = getimagesize($this->SrcFile);
				$Width = $this->File[0]; //得到图片宽度
				$Height = $this->File[1]; //得到图片高度
				$Type = $this->File[2]; //得到图片类型 ， 1为gif 2为jpg 3为png
				switch($Type)
				{
					case 1 : $MyType = 'gif'; $SrcIm = imagecreatefromgif($this->SrcFile); break;
					case 2 : $MyType = 'jpg'; $SrcIm = imagecreatefromjpeg($this->SrcFile); break;
					case 3 : $MyType = 'png'; $SrcIm = imagecreatefrompng($this->SrcFile); break;
				}
				if(empty($MyType))
				{
					echo "不支持此类型图片";
				}else
				{
					$Im = imagecreatetruecolor($this->NewWidth,$this->NewHeiht);
                    imagecopyresized($Im,$SrcIm,0,0,0,0,$this->NewWidth,$this->NewHeiht,$Width,$Height);
					if(!file_exists($this->Path))
					{
						mk($this->Path);
					}else
					{
						$Name = $this->Path ."R_". date("Ydmhis").".".$MyType;
						if($MyType == "gif")
							imagegif($Im,$Name);
						else if($MyType == "jpg")
							imagejpeg($Im,$Name);
						else
							imagepng($Im,$Name);
					}
					if(file_exists($Name))
					{
						echo "<script>";
						echo "alert('图片生成成功!');";
						//echo "location.href='javascript:history.go(-1)';";
						echo "</script>";
					}else
					{
						echo "<script>";
						echo "alert('图片失败');";
						//echo "location.href='javascript:history.go(-1)';";
						echo "</script>";
					}
				}
		}
	}
}
//$MyImage = new ImageReSize("1.jpg","100","80","./images/");
//$MyImage->GoReSize();
?>