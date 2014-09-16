<?
/*
---------------------------------------------------------------------
- 项目: DNS phpsea
- 版本: 1.5
- 文件名：templates.func.php
- 编写者：indraw
- 整理者：indraw
- 编写日期：2004/11/16
- 简要描述：简单实现smarty编译型模版
- 运行环境：无需求
- 修改记录：2004/11/16，indraw，程序创立
---------------------------------------------------------------------
*/

/*
	get_template($page)
	import_template($tplfile, $outfile)
*/

//=============================================================================

	define("phpsea", "1.0");
	$PS["TMP"]=array();
	$PS["DATA"]=array();

	/*
	-----------------------------------------------------------
	函数名称：get_template($page)
	简要描述：判断模版文件是否需要编译
	输入：制作好的模版文件
	输出：输出页面
	修改日志：------
	-----------------------------------------------------------
	*/
	function get_template($page)
	{
		//初始化模版文件路径 //为以后设计多模版，多用户做准备
		$PS=$GLOBALS["PS"];
		if(!empty($PS["args"]["template"])) {
			$PS["template"]=basename($PS["args"]["template"]);
		}
		if((!isset($PS['display_fixed']) || !$PS['display_fixed']) && isset($PS['user']['user_template'])) {
			$PS['template']=$PS['user']['user_template'];
		}
		if(empty($PS["template"])){
			$PS["template"]=$PS["default_template"];
		}
		$tpl="./templates/$PS[template]/$page";
		//如果模版文件为php，那么直接读入，否则编译
		if(file_exists("$tpl.php")){
			$phpfile="$tpl.php";
		} else {
			$tplfile= $tpl;
			$phpfile="$PS[cache]/tpl-$PS[template]-$page-".md5(dirname(__FILE__)).".php";
			if(!file_exists($phpfile) || filemtime($tplfile) > filemtime($phpfile)){
				import_template($tplfile, $phpfile);
			}
		}
		
		//返回编译后的页面
		return $phpfile;

	}//end func

	/*
	-----------------------------------------------------------
	函数名称：import_template($tplfile, $outfile)
	简要描述：将模版文件进行编译操作
	输入：模版文件名，编译后的文件名
	输出：将编译后的混编代码写入文件
	修改日志：------
	-----------------------------------------------------------
	*/
	function import_template($tplfile, $outfile)
	{
		global $PS;

		//读入模版文件
		$fp=fopen($tplfile, "r");
		$page=fread($fp, filesize($tplfile));
		fclose($fp);

		$page = str_replace("?>","<?php echo '?>\n' ?>",$page);
		$page = str_replace("<?xml","<?php echo '<?xml' ?>",$page);
		//解析模版文件
		preg_match_all("/\{[\!\/A-Za-z].+?\}/s", $page, $matches);
		settype($oldloopvar, "string");
		settype($loopvar, "string");
		settype($olddatavar, "string");
		settype($datavar, "string");

		//循环解析
		foreach($matches[0] as $match){

			unset($parts);
			$string=substr($match, 1, -1);

			//预处理变量指示箭头
			if(strstr($string, "->")){
				$string=str_replace("->", "']['", $string);
			}
			$parts=explode(" ", $string);

			switch(strtolower($parts[0])){
				//注释
				case "!":
					$repl="<?php // ".implode(" ", $parts)." ?>";
					break;

				//包含文件
				case "include":
					$repl="<?php include_once get_template('$parts[1]'); ?>";
					break;
				
				//包含变量所代表的文件
				case "include_var": // include a file given by a variable
					$repl="<?php include_once get_template( \$PS[\"DATA\"]['$parts[1]']); ?>";
					break;						  

				//生成引擎使用的变量
				case "define":
					$repl="<?php \$PS[\"TMP\"]['$parts[1]']='";
					array_shift($parts);
					array_shift($parts);
					foreach($parts as $part){
						$repl.=str_replace("'", "\\'", $part)." ";
					}
					$repl=trim($repl)."'; ?>";
					break;

				//生成模版文件使用的变量				  
				case "var":
					$repl="<?php \$PS[\"DATA\"]['$parts[1]']='";
					array_shift($parts);
					array_shift($parts);
					foreach($parts as $part){
						$repl.=str_replace("'", "\\'", $part)." ";
					}
					$repl=trim($repl)."'; ?>";
					break;


				//开始一个循环
				case "loop":
					$loopvars[$parts[1]]=true;
					$repl="<?php if(isset(\$PS['DATA']['$parts[1]']) && is_array(\$PS['DATA']['$parts[1]'])) foreach(\$PS['DATA']['$parts[1]'] as \$PS['TMP']['$parts[1]']){ ?>";
					break;

				//结束一个循环
				case "/loop":
					$repl="<?php } unset(\$PS['TMP']['$parts[1]']); ?>";
					unset($loopvars[$parts[1]]);
					break;


				//开始一个条件判断
				case "if":
				case "elseif":
					//定义if和elseif
					$prefix = (strtolower($parts[0])=="if") ? "if" : "} elseif";
					//默认输入DATA
					$index="DATA";
					//检查循环变量并且如果他是一个使用TMP
					if(strstr($parts[1], "'") && isset($loopvars)  && count($loopvars)){
						$varname=substr($parts[1], 0, strpos($parts[1], "'"));
						if(isset($loopvars[$varname])){						  
							$index="TMP";
						}
					}						  
					if(isset($parts[2])){
						if(!is_numeric($parts[2]) && !defined($parts[2])){
							$parts[2]="\"$parts[2]\"";
						}
						$repl="<?php $prefix(isset(\$PS['$index']['$parts[1]']) && \$PS['$index']['$parts[1]']==$parts[2]){ ?>";
					} else {
						$repl="<?php $prefix(isset(\$PS['$index']['$parts[1]']) && !empty(\$PS['$index']['$parts[1]'])){ ?>";
					}
					//从新设计前缀
					$prefix="";
					break;

				//开始一个else
				case "else":
					$repl="<?php } else { ?>";
					break;

				// 结束一个条件判断
				case "/if":
					$repl="<?php } ?>";
					break;
				
				//附值语句
				case "assign":
					if(defined($parts[2])){
						$repl="<?php $parts[1]; ?>";
						$repl="<?php \$PS[\"DATA\"]['$parts[1]']=$parts[2]";
					} else {
						//DATA是默认数组
						$index="DATA";
						//检查循环变量并且如果他是一个使用TMP
						if(strstr($parts[2], "'") && isset($loopvars)  && count($loopvars)){
							$varname=substr($parts[2], 0, strpos($parts[2], "'"));
							if(isset($loopvars[$varname])){
								$index="TMP";
							}
						}
						$repl="<?php \$PS[\"DATA\"]['$parts[1]']=\$PS['$index']['$parts[2]']; ?>";
					}
					break;

				//从DATA和TMP直接显示变量，如果他是一个。
				default:
					if(defined($parts[0])){
						$repl="<?php echo $parts[0]; ?>";
					} else {
						//DATA是默认数组
						$index="DATA";
						//检查循环变量并且如果他是一个使用TMP
						if(strstr($parts[0], "'") && isset($loopvars)  && count($loopvars)){
							$varname=substr($parts[0], 0, strpos($parts[0], "'"));
							if(isset($loopvars[$varname])){
								$index="TMP";
							}
						}
						$repl="<?php echo \$PS['$index']['$parts[0]']; ?>";
					}
			}
			//执行替换操作
			$page=str_replace($match, $repl, $page);
		}

		//将编译后的模版写入新文件
		if( $PS["template"] == 2 )
		{
			$page = iconv("gb2312", "UTF-8",$page);
		}
		if($fp=fopen($outfile, "w")){
			fputs($fp, "<?php if(!defined(\"phpsea\")) return; ?>\n");
			fputs($fp, $page);
			fclose($fp);
		}

	}//end func

//=============================================================================
?>