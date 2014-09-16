<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名:ImageStat.class.php
- 原作者:indraw
- 整理者:indraw
- 编写日期:2004/11/11
- 简要描述:常用统计图片显示结果类集，为综合几个类库从新编写
- 运行环境:需要GD库支持，版本最佳为2.0以上，比如：2.0.28
- 修改记录:2004/11/11，indraw，程序创立
---------------------------------------------------------------------
*/

//-----------------------------------------------------------------------------
//以下为具体使用方法
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	//折线
	$newStat = new ImageStat();
	$newStat->set_data($a);
	if($action == "line"){
		$newStat->set_size("500","150");
		$newStat->stat_line();
	}
	//饼型
	if($action == "pie"){
		$newStat->set_size("300","150");
		$newStat->stat_pie();
	}
	//拄状
	if($action == "bar"){
		$newStat->set_size("400","150");
		$newStat->set_border(20,20,20,20);
		$newStat->set_items("1M,2M,3M,4M,5M,6M,7M,8M,9M,10M,11M,12M");
		$newStat->stat_bar();
	}
/*
	set_data($data)                        //将统计数据附给类属性$this->statistic以备使用
	set_size($width,$height)               //设置图象宽高，也就是统计图表的强制长宽
	set_border($left,$right,$top,$down)    //设置统计图和图象边框的距离
	set_items($items)                      //将统计条目写入数组以备使用
	set_color($color)                      //设置前景颜色
	set_bkcolor($color)                    //设置背景颜色
	stat_pie()                             //饼型图
	stat_line()                            //折线图
	stat_bar()                             //拄状图
*/
//=============================================================================
class ImageStat
{
	var $img_width  = 300;		//定义图象宽度
	var $img_height = 150;		//定义图象高度

	var $statistic  = array();	//定义被统计数据数组
	var $items      = array();	//调查选项

	var $left       = 20;		//图表左距
	var $right      = 20;		//图表右距
	var $top        = 20;		//图表上距
	var $down       = 10;		//图表下距

	var $color = array(
				array(0x97, 0xbd, 0x00),
				array(0x00, 0x99, 0x00),
				array(0xcc, 0x33, 0x00),
				array(0xff, 0xcc, 0x00),
				array(0x33, 0x66, 0xcc),
				array(0x33, 0xcc, 0x33),
				array(0xff, 0x99, 0x33),
				array(0xcc, 0xcc, 0x99),
				array(0x99, 0xcc, 0x66),
				array(0x66, 0xff, 0x99),

				array(0x99, 0xcc, 0x66),
				array(0x66, 0xff, 0x99)
				);//图象前景色
	var $bkcolor = array(
				array(0x4f, 0x66, 0x00),
				array(0x00, 0x33, 0x00),
				array(0x48, 0x10, 0x00),
				array(0x7d, 0x64, 0x00),
				array(0x17, 0x30, 0x64),
				array(0x1a, 0x6a, 0x1a),
				array(0x97, 0x4b, 0x00),
				array(0x78, 0x79, 0x3c),
				array(0x55, 0x7e, 0x27),
				array(0x00, 0x93, 0x37),

				array(0x55, 0x7e, 0x27),
				array(0x00, 0x93, 0x37)
				);//图象背景色
	/*
	-----------------------------------------------------------
	函数名称:set_data($data)
	简要描述:将统计数据附给类属性$this->statistic以备使用
	输入:string (接受格式“3,5,8,34”)
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_data($data)
	{
		$this->statistic = explode(",", $data);
		if(count($this->statistic) == 0) die("统计数据太少");
	}

	/*
	-----------------------------------------------------------
	函数名称:set_size($width,$height)
	简要描述:设置图象宽高，也就是统计图表的强制长宽
	输入:mixed (图象宽，高)
	输出:---
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_size($width,$height)
	{
		$this->img_width = $width;
		$this->img_height = $height;
	}

	/*
	-----------------------------------------------------------
	函数名称:set_border($left,$right,$top,$down)
	简要描述:设置统计图和图象边框的距离
	输入:mixed (左，右，上，下);
	输出:mixed
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_border($left,$right,$top,$down)
	{
		$this->left       = $left;
		$this->right      = $right;
		$this->top        = $top;
		$this->down       = $down;
	}

	/*
	-----------------------------------------------------------
	函数名称:set_items($items)
	简要描述:将统计条目写入数组以备使用
	输入:mixed (接受格式“3,5,8,34”)
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_items($items)
	{
		$this->items = explode(",", $items);
		if(count($items) == 0) die("统计条目太少");
	}

	/*
	-----------------------------------------------------------
	函数名称:set_color($color)
	简要描述:设置前景颜色
	输入:array (每个条目的颜色，为三原色)；
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_color($color)
	{
		$this->color = $color;
		if(count($this->color[0]) == 0) die("统计颜色太少");
	}

	/*
	-----------------------------------------------------------
	函数名称:set_bkcolor($color)
	简要描述:设置背景颜色
	输入:array (每个条目的颜色，为三原色)；
	输出:void
	修改日志:------
	-----------------------------------------------------------
	*/
	function set_bkcolor($color)
	{
		$this->bkcolor = $color;
		if(count($this->color[0]) == 0) die("统计颜色太少");
	}

	/*
	-----------------------------------------------------------
	函数名称:stat_pie()
	简要描述:饼型显示统计数据
	输入:void
	输出:直接输出到浏览器
	修改日志:------
	-----------------------------------------------------------
	*/
	function stat_pie()
	{
		$angle = array();
		$total = 0;
		//统计加总
		for ($i=0; $i< count($this->statistic); $i++){
			$total += $this->statistic[$i];
		}
		//计算每个统计量占用角度
		for ($i=0; $i<count($this->statistic); $i++) {
			array_push ($angle, round(360*$this->statistic[$i]/$total));
		}
		//初始化方形图片
		$image = imagecreate($this->img_width, $this->img_height);
		$white = imagecolorallocate($image, 0xEE, 0xEE, 0xEE);
		$border=imagecolorallocate($image,"0","0","0");

		$radius = $this->img_width/2;
		//化椭圆背景
		for ($h=$this->img_height/2+5; $h>$this->img_height/2-5; $h--) {
			$start = 0;
			$end = 0;
			for ($i=0; $i<count($this->statistic); $i++)  {
				$start  = $start+0;
				$end  = $start+$angle[$i];
				$color_bit = $this->bkcolor[$i];
				$color = imagecolorallocate($image, $color_bit[0],$color_bit[1], $color_bit[2]);
				imagefilledarc($image, $radius, $h, $this->img_width, $this->img_height-20, $start, $end, $color, IMG_ARC_PIE);
				$start += $angle[$i];
				$end += $angle[$i];
			}
		}
		//画椭圆明亮部分
		for ($i=0; $i<count($this->statistic); $i++)  {
				$start  = $start + 0;
				$end  = $start + $angle[$i];
				$color_bit = $this->color[$i];
				$color = imagecolorallocate($image, $color_bit[0], $color_bit[1], $color_bit[2]);
				imagefilledarc($image, $radius, $h, $this->img_width, $this->img_height-20, $start, $end, $color, IMG_ARC_PIE);
				
				//向饼型上写百分比---测试中
				//$mid_point = round((($angle[$i])/2) + $start);
				//echo $mid_point."--".$start."++".$angle[$i]."<br>";
				//$x= $this->img_width + cos($mid_point * (pi()/180.0)) * (($this->img_width)*3/4);
				//$y= $this->img_height + sin($mid_point * (pi()/180.0)) * (($this->img_height)*3/4);
				//$percent = number_format($this->statistic[$i]/$total*100, 1);
				//imagestring($image,3,$x,$i*10,$percent."%",$border);

				$start += $angle[$i];
				$end += $angle[$i];
			}
		//输出图象
		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
	}

	/*
	-----------------------------------------------------------
	函数名称:stat_line()
	简要描述:折线图显示结果
	输入:void
	输出:直接输出到浏览器
	修改日志:------
	-----------------------------------------------------------
	*/
	function stat_line()
	{
		$left=$this->left;
		$right=$this->right;
		$top=$this->top;
		$down=$this->down;
		$data=$this->statistic;

		$max_value=1;
		$p_x = array();
		$p_y = array();

		for($i=0;$i<count($data);$i++){
			if(!is_numeric($data[$i])) die("error id:1");
			if($data[$i]>$max_value) $max_value=$data[$i];
		}

		$space = ($this->img_width-$left-$right)/count($data);

		$image = imagecreate($this->img_width,$this->img_height);

		$white    = imagecolorallocate($image, 0xEE, 0xEE, 0xEE);
		$back_color = imagecolorallocate($image, 0x00, 0x00, 0x00);
		$line_color = imagecolorallocate($image, 0x00, 0x00, 0xFF);

		imageline ( $image, $left, $this->img_height-$down, $this->img_width-$right/2, $this->img_height-$down, $back_color);
		imageline ( $image, $left, $top/2,  $left, $this->img_height-$down, $back_color);

		for($i=0;$i<count($data);$i++){
			array_push ($p_x, $left+$i*$space);
			array_push ($p_y, $top+round(($this->img_height-$top-$down)*(1-$data[$i]/$max_value)));
		}

		imageline ( $image, $left, $top,  $left+6, $top, $back_color);
		imagestring ( $image, 1, $left/4, $top,$max_value, $back_color);
		imageline ( $image, $left, $top+($this->img_height-$top-$down)*1/4,  $left+6, $top+($this->img_height-$top-$down)*1/4, $back_color);
		imagestring ( $image, 1, $left/4, $top+($this->img_height-$top-$down)*1/4,$max_value*3/4, $back_color);
		imageline ( $image, $left, $top+($this->img_height-$top-$down)*2/4,  $left+6, $top+($this->img_height-$top-$down)*2/4, $back_color);
		imagestring ( $image, 1, $left/4, $top+($this->img_height-$top-$down)*2/4,$max_value*2/4, $back_color);
		imageline ( $image, $left, $top+($this->img_height-$top-$down)*3/4,  $left+6, $top+($this->img_height-$top-$down)*3/4, $back_color);
		imagestring ( $image, 1, $left/4, $top+($this->img_height-$top-$down)*3/4,$max_value*1/4, $back_color);

		for($i=0;$i<count($data);$i++){
			imageline ( $image, $left+$i*$space, $this->img_height-$down,  $left+$i*$space, $this->img_height-$down-6, $back_color);
			imagestring ( $image, 1, $left+$i*$space-$space/4, $top+($this->img_height-$top-$down)+2,$this->items[$i], $back_color);
		}

		for($i=0;$i<count($data);$i++){
			if($i+1<>count($data)){
				imageline ( $image, $p_x[$i], $p_y[$i],  $p_x[$i+1], $p_y[$i+1], $line_color);
				$point_color = imagecolorallocate($image, $this->color[$i][0], $this->color[$i][1], $this->color[$i][2]);
				imagefilledrectangle($image, $p_x[$i]-1, $p_y[$i]-1,  $p_x[$i]+1, $p_y[$i]+1, $point_color);
			}
		}

		imagefilledrectangle($image, $p_x[count($data)-1]-1, $p_y[count($data)-1]-1,  $p_x[count($data)-1]+1, $p_y[count($data)-1]+1, $line_color);

		for($i=0;$i<count($data);$i++){
			imagestring ( $image, 3, $p_x[$i]+4, $p_y[$i]-12,$data[$i], $back_color);
		}

		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
	}

	/*
	-----------------------------------------------------------
	函数名称:stat_bar()
	简要描述:柱型图显示结果
	输入:void
	输出:直接输出到浏览器
	修改日志:------
	-----------------------------------------------------------
	*/
	function stat_bar()
	{

		$left=$this->left;
		$right=$this->right;
		$top=$this->top;
		$down=$this->down;

		$data=$this->statistic;

		$space = ($this->img_width-$left-$right)/(count($data)*3);
		$bar_width = $space*2;

		$max_value=1;

		for($i=0;$i<count($data);$i++){
			if(!is_numeric($data[$i])) die("error id:1");
			if($data[$i]>$max_value) $max_value=$data[$i];
		}
		$bar_height = array();
		$image = imagecreate($this->img_width,$this->img_height);
		$white    = imagecolorallocate($image, 0xEE, 0xEE, 0xEE);

		$img_color = imagecolorallocate($image, 0x00, 0x00, 0x00);
		imageline ( $image, $left, $this->img_height-$down, $this->img_width-$right/2, $this->img_height-$down, $img_color);
		imageline ( $image, $left, $top/2,  $left, $this->img_height-$down, $img_color);

		imageline ( $image, $left, $top,  $left+6, $top, $img_color);
		imagestring ( $image, 3, $left/4, $top,round($max_value), $img_color);
		imageline ( $image, $left, $top+($this->img_height-$top-$down)*1/4,  $left+6, round($top+($this->img_height-$top-$down)*1/4), $img_color);
		imagestring ( $image, 3, $left/4, $top+($this->img_height-$top-$down)*1/4,round($max_value*3/4), $img_color);
		imageline ( $image, $left, $top+($this->img_height-$top-$down)*2/4,  $left+6, $top+($this->img_height-$top-$down)*2/4, $img_color);
		imagestring ( $image, 3, $left/4, $top+($this->img_height-$top-$down)*2/4,round($max_value*2/4), $img_color);
		imageline ( $image, $left, $top+($this->img_height-$top-$down)*3/4,  $left+6, $top+($this->img_height-$top-$down)*3/4, $img_color);
		imagestring ( $image, 3, $left/4, $top+($this->img_height-$top-$down)*3/4,round($max_value*1/4), $img_color);

		for($i=0;$i<count($data);$i++){
			array_push ($bar_height, round(($this->img_height-$top-$down)*$data[$i]/$max_value));
		}

		for($i=0;$i<count($data);$i++){
			$bar_color = imagecolorallocate($image, $this->color[$i][0], $this->color[$i][1], $this->color[$i][2]);
			imagefilledrectangle( $image,$left+$space+$i*($bar_width+$space),$top+($this->img_height-$top-$down)-$bar_height[$i],$left+$space+$i*($bar_width+$space)+$bar_width,($this->img_height-$down)-1 ,$bar_color);

			imagestring ( $image, 1, $left+$space+$i*($bar_width+$space), $top+($this->img_height-$top-$down)+2,$this->items[$i], $img_color);
		}

		for($i=0;$i<count($data);$i++){
			imagestring ( $image, 1, $left+$space+$i*($bar_width+$space)+2,$top+($this->img_height-$top-$down)-$bar_height[$i]-10,$data[$i], $img_color);
		}
		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);

	}

}//end class
//=============================================================================
?>