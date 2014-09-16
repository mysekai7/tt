<?php
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.6
- 文件名:funcPage.inc.php
- 原作者:indraw
- 整理者:indraw
- 编写日期:2006/07/21
- 简要描述:常用分页
- 运行环境:---
- 修改记录:2006/07/28,indraw,添加新的分页方法
---------------------------------------------------------------------
*/

/*
	//常用分页显示
	getPage()             //获取分页str
	getPageArray()        //获取分页arr
	getPageHtml()         //获取分页htm

*/

//-------------------------------------------------------------------
class PageMaker 
{
	var $Page = 1;              //当前页
	var $Count = 0;          //总条数
	var $PerDiv = 10;        //没段
	var $PerPage = 20;       //每页
	var $Condition = "";     //条件传递

	function getBegin()
	{
		if($this->Page < 1){
			$this->Page = 1;
		}
		return $this->PerPage*($this->Page-1);
	}

	/*
	-----------------------------------------------------------
	函数名称：getPage
	简要描述：分页处理函数
	输入：string
	输出：string
	修改日志：------
	-----------------------------------------------------------
	*/
	//  分段的页数
	//$offset  每页显示条数
	function getPage() 
	{
		$totalnum = ceil($this->Count/$this->PerPage);
		if(!$this->Page){
			$this->Page = 1;
			}
		if($this->Page > $this->PerDiv) {
			if ($this->Page == 1) {
				$htmPage .= "\n";
			}
			else {
				$prevp = $this->Page-1;
				$htmPage .= "<a href=\"$PHP_SELF?page=1{$this->Condition}\" title=\"首页\">[1]</a>\n";
			}
		}
		$term = $this->PerDiv;$f = 1;$l = $term;
		while ($f <= $totalnum) {
			if (($f <= $this->Page) && ($this->Page <= $l)) {
				$prevp = $f - 1;
				if ($prevp > 0) {
					$htmPage .= "<a href=\"$PHP_SELF?page=$prevp{$this->Condition}\" title=\"前 $this->PerDiv页\">[Prev]</a>-";
				}
				else {
					$htmPage .= "\n";
				}
				if ($l <= $totalnum) {
					for ($page = $f; $page <= $l; $page++) {
						if ($page == $this->Page) {
							$htmPage .= "[<font color=\"red\" title=\"当前页\">$page</font>]";
						}
						else {
							$htmPage .= "<a href=\"$PHP_SELF?page=$page{$this->Condition}\" title=\"$page页\">[$page]</a>";
						}
					}
				}
				else {
					for ($page = $f; $page <= $totalnum; $page++) {
						if ($page == $this->Page) {
							$htmPage .= "[<font color=\"red\" title=\"当前页\">$page</font>]";
						}
						else {
							$htmPage .= "<a href=\"$PHP_SELF?page=$page{$this->Condition}\"  title=\"$page页\">[$page]</a>";
						}
					}
				}
				$nextp = $l + 1;
				if ($nextp <= $totalnum) {
					$htmPage .= "-<a href=\"$PHP_SELF?page=$nextp{$this->Condition}\" title=\"后 $this->PerDiv页\">[Next]</a>";
				}
				else {
					$htmPage .= "";
				}
			}
			$f = $f + $term;
			$l = $l + $term;
		}
		if($nextp <= $totalnum){
			$htmPage .= "<a href=\"$PHP_SELF?page=$totalnum{$this->Condition}\" title=\"尾页\">[$totalnum]</a>\n";
		}
		return $htmPage;
	}//end func
	/*
	-----------------------------------------------------------
	函数名称：getPageArray()
	简要描述：分页处理函数-与上一个函数最大的不同是返回分页数组
	输入：string
	输出：string
	修改日志：------
	-----------------------------------------------------------
	*/
	function getPageArray()
	{
		$thepage = "";
		$area = intval($this->PerDiv);
		if($area < 4)
			$area = 4;
		$page = intval($this->Page);
		if($page < 1)
			$page = 1;
		$maxpage = ceil($this->Count / $this->PerPage);
		if($maxpage == 0)
			$maxpage = 1;
		if($page > $maxpage)
			$page = $maxpage;
		$start  = ($page - 1) * $this->PerPage;
		$areapage = ceil($page / $area);
		$prevPage = ($areapage - 2) * $area + 1;
		$nextPage = $areapage * $area + 1;
		$startpage = ($areapage - 1) * $area + 1;
		if($prevPage>0)
		$thepage= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$prevPage."{$this->Condition}\">&lt;&lt;</a> ";
		for($i=$startpage; $i<$startpage + $area; $i++){
			if($i>$maxpage)
				break;
			if($i==$page)
				$thepage.= "<font color=red>$i</font>&nbsp;";
			else 
				$thepage.= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$i."{$this->Condition}\">$i</a>&nbsp;";
		}
		if($nextPage<=$maxpage)
			$thepage.= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$nextPage."{$this->Condition}\">&gt;&gt;</a>";
		$result["thepage"] = $thepage;
		$result["total"] = $this->Count;
		$result["start"] = $start;
		$result["page"]  = $page;
		$result["maxpage"] = $maxpage;
		if($page>1)
			$result["pagepre"] = $page-1;
		else
			$result["pagepre"] = 1;
		if($page<$maxpage)
			$result["pagenext"] = $page+1;
		else
			$result["pagenext"] = $maxpage;
		return $result;
	}

	/*
	-----------------------------------------------------------
	函数名称：getPageHtml()
	简要描述：分页处理函数-通过post传递数据
	输入：string
	输出：string
	修改日志：2005/6/28 by indraw
	-----------------------------------------------------------
	*/
	function getPageHtml() 
	{
		$page = intval($this->Page);
		if($page < 1)
			$page = 1;
		$maxpage = ceil($this->Count / $this->PerPage);
		if($maxpage == 0)
			$maxpage = 1;
		if($page > $maxpage)
			$page = $maxpage;
		//-----------------------------------------------------------
		$sCondition = "";
		if( is_array($this->Condition) )
		{
			foreach( $this->Condition as $key=>$val )
			{
				$sCondition .= "<input type=hidden name=\"$key\" value=\"$val\">\n\t\t";
			}
		}
		$sPageHtml = "
		<!---分页显示开始------------------------------------------>
		<form name=turnover1 method=post>
		第{$page}页 共{$maxpage}页 共{$this->Count}条&nbsp;到第
		<input name=gotopage type=text size=6 maxlength=10 onkeyup=\"document.turnover1.page.value = document.turnover1.gotopage.value;\">页
		<input type=submit value=\"提交\" onclick=\"document.turnover1.page.value = document.turnover1.gotopage.value;\">
		<input type=submit value=\"上一页\" onclick=\"document.turnover1.page.value--;\">
		<input type=submit value=\"下一页\" onclick=\"document.turnover1.page.value++;\">
		<input type=hidden name=\"page\" value=\"{$page}\">
		$sCondition
		</form>
		<!---分页显示结束------------------------------------------>
		";
		return $sPageHtml;
	}

}
//-----------------------------------------------------------------------------
//end
?>