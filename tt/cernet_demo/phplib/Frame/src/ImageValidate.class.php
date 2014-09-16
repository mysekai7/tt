<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:ImageValidate.class.php
- 原作者:佟杰义
- 整理人:indraw
- 编写日期:2004/11/11
- 简要描述:图片验证密码
- 运行环境:需要GD库支持，版本最佳为2.0以上，比如：2.0.28
- 修改记录:2004/11/11，indraw，程序创立
---------------------------------------------------------------------
*/

/*
if($_GET["show"] == "true")
{
	$im = new ImageValidate;
	$im->show();
	echo($this->Code);
}
	ImageValidate()    //
	create_image()     //生成密码验证图片
	transfer_code()    //设置session
	show()             //显示验证码图片
*/
//=============================================================================
class ImageValidate
{

	var $x;                 //数字在图片中的x坐标
	var $y;                 //数字在图片中的y坐标
	var $numChars;          //生成几位数字图片
	var $Code;              //验证码数字
	var $Width;             //图片宽度
	var $Height;            //图片高度
	var $BG;                //背景三原色
	var $colTxt;            //数字原色
	var $colBorder;         //图片边框三原色
	var $numCirculos;       //随机数字随机参数
	//var $vCode;             //session变量名字

	//构造函数、初始值
	function ImageValidate($x = "",$y = "6",$numChars = "4",$Code = "",$Width = "80",$Height = "25",$BG = "255 255 255",$colTxt = "0 0 0 0",$colBorder = "100 100 100",$numCirculos = "800"/*,$vCode = "vCode"*/)
	{
		$this->x = $x;
		$this->y = $y;
		$this->numChars = $numChars;
		$this->Code = $Code;
		$this->Width = $Width;
		$this->Height = $Height;
		$this->BG = $BG;
		$this->colTxt = $colTxt;
		$this->Border = $colBorder;
		$this->numCirculos = $numCirculos;
		//$this->vCode = $vCode;
	}

	/*
	-----------------------------------------------------------
	函数名称:create_image()
	简要描述:生成密码验证图片
	输入:void
	输出:source (等待写入图片资源)
	修改日志:------
	-----------------------------------------------------------
	*/
	function create_image()
	{
		//新建一个基于调色板的图像
		$im = imagecreate ($this->Width, $this->Height) or die ("Cannot Initialize new GD image stream");

		//获取三原色
		$colorBG = explode(" ", $this->BG);
		$colorBorder = explode(" ", $this->Border);
		$colorTxt = explode(" ", $this->colTxt);

		//将背景图片写入图片
		$imBG = imagecolorallocate ($im, $colorBG[0], $colorBG[1], $colorBG[2]);

		//将边框颜色写入图片
		$Border = ImageColorAllocate($im, $colorBorder[0], $colorBorder[1], $colorBorder[2]);
		$imBorder = ImageRectangle($im, 0, 0, $this->Width-1,$this->Height-1, $Border);

		//将图片颜色写入图片
		$imTxt = imagecolorallocate ($im, $colorTxt[0], $colorTxt[1], $colorTxt[2]);

		//随机数800
		for($i = 0; $i < $this->numCirculos; $i++)
		{
			$imPoints = imagesetpixel($im, mt_rand(0,80), mt_rand(0,80), $Border);
		}

		//将随机数写入图片
		//$this->Code = "";
		for($i = 0; $i < $this->numChars; $i++)
		{
			$this->x = 21 * $i + 5;

			mt_srand((double) microtime() * 1000000*getmypid());
			$this->Code.= (mt_rand(0, 9));
			$putCode = substr($this->Code, $i, "1");
			$Code = imagestring($im, 5, $this->x, $this->y, $putCode,$imTxt);
		}

		return $im;
	}

	/*
	-----------------------------------------------------------
	函数名称:transfer_code()
	简要描述:设置session
	输入:void
	输出:void (设置session变量)
	修改日志:------
	-----------------------------------------------------------
	*/
	/*
	function transfer_code()
	{
		$this->create_image();
		session_start();
		session_register($this->vCode);
		$_SESSION[$this->vCode] = $this->Code;
	}
	*/

	/*
	-----------------------------------------------------------
	函数名称:show()
	简要描述:显示验证码图片
	输入:void
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function show()
	{
		header("Content-type:image/png");
		$sImages = $this->create_image();
		Imagepng($sImages);
		Imagedestroy($sImages);
	}


}//end class
//=============================================================================
?>
