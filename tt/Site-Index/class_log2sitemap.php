<?php

/**
 *  分析日志并生成sitemap
 *
 */

class log2sitemap
{
    public $date;
    public $log_file;
    public $tpl;//模板
    public $page;//分页

    public function __construct($date)
    {
        $this->date = $date;
        if(!file_exists("./html_tmp/datesitemap/"))
            mkdir("./html_tmp/datesitemap/", 0775);

        $this->log_file = "./access_log-web1.".$this->date;;

    }

    public function __destruct()
    {
        echo "#### all works end ####\n";
    }

    public function run()
    {
        echo "begin\n";
        // 1. 下载日志
        $this->download_log();

        // 2. 分析日志 提取url中关键词
        $this->parse_log2txt();

        // 3. 随机那一关键词添加到index页
        $this->add_keyword_to_index();

        // 4. 更新index首页
        $this->update_index_html();

        // 5. 把keywords txt 生成html
        $this->create_sitemap_html();

        // 6. 上传html到36 37 39
        $this->copy_tmp();

        // 7. 将临时html_tmp文件 备份到html中
        $this->move_tmp();

        // 8. 删除日志
        $this->del_log();

    }

    //同步所有的html到36 37 39
    public static function sync_all()
    {
        echo "sync 37 begin \n";
        system("scp -r ./html/* liuyan@172.18.0.39:/home/www/nine/ebs/");
        echo "html 39 end\n";

        system("scp -r ./html/* liuyan@172.18.0.37:/home/www/nine/ebs/");
        echo "html 37 end\n";

        system("scp -r ./html/* liuyan@172.18.0.36:/home/www/nine/ebs/");
        echo "html 36 end\n";

	system("scp -r ./html/* liuyan@172.18.0.73:/home/www/nine/ebs/");
        echo "html 73 end\n";
    }

    //下载日志
    public function download_log()
    {
        $log_file = $this->log_file;
        touch($log_file);
        chmod($log_file, 0775);

        //从36,37,39拷贝日志
        system("scp liuyan@172.18.0.36:/usr/local/apache2/logs/ebs-access_logs/access_log.{$this->date}.gz ./access_log.{$this->date}_36.gz");
        system("scp liuyan@172.18.0.37:/usr/local/apache2/logs/ebs-access_logs/access_log.{$this->date}.gz ./access_log.{$this->date}_37.gz");
        system("scp liuyan@172.18.0.39:/usr/local/apache/logs/ebs-access_logs/access_log.{$this->date}.gz ./access_log.{$this->date}_39.gz");
	system("scp liuyan@172.18.0.73:/usr/local/apache2/logs/ebs-access_logs/access_log.{$this->date}.gz ./access_log.{$this->date}
_73.gz");
        //解压
        system("gzip -d ./access_log.{$this->date}_36.gz");
        system("gzip -d ./access_log.{$this->date}_37.gz");
        system("gzip -d ./access_log.{$this->date}_39.gz");
	system("gzip -d ./access_log.{$this->date}_73.gz");
        //合并
        system("cat access_log.{$this->date}_36 access_log.{$this->date}_37 access_log.{$this->date}_39 access_log.{$this->date}_73 >> $log_file");

        echo "1. download logs end\n";
    }

    public function del_log()
    {
        system("rm ./access_log.{$this->date}_36");
        system("rm ./access_log.{$this->date}_37");
        system("rm ./access_log.{$this->date}_39");
	system("rm ./access_log.{$this->date}_73");
        system("rm ./access_log-web1.{$this->date}");
        echo "8. del logs end\n";
    }


    //提取日志url中关键词
    public function parse_log2txt()
    {
        if(!file_exists($this->log_file))
        {
            echo "Log file is not exist\n";
            return false;
        }

        $path_parts = pathinfo($this->log_file);
        $date = $path_parts["extension"];//获取扩展名

        $fp_log = fopen($this->log_file, 'r');
        if(!$fp_log)
            return false;

        $txt_file = './kw_tmp.txt';

        $fp_txt = fopen($txt_file, 'w');//注意保存目录手动创建
        if(!$fp_txt) return false;

        @chmod($txt_file, 0775);

        $i=0;
        while(!feof($fp_log))
        {
            //if($i>10) break;

            //获得一行日志
            $log= fgets($fp_log);

            if($keyword = $this->parse_kw($log))
            {
                fwrite($fp_txt, $keyword."\n");
            }
            //$i++;
        }

        fclose($fp_log);
        fclose($fp_txt);

        //去除重复关键词
        //system("./make_unique.pl");
		system("./filter.pl $date");
        unlink($txt_file);

		//跟stat_all进行比较 获得唯一词


        //转移临时文件
        //system("mv distinct_kw.txt ./newadd/add_{$date}.txt");

        echo "2. parse kw end\n";
    }

    //处理kw
    private function parse_kw($log)
    {
        $log = trim($log);

        //if(!preg_match('|^GET\s/buy-|', $log))
            //return false;

        $pattern = '|GET\s/buy-(.*?)\sHTTP/|is';
        //$pattern = '|^/buy-(.*?)$|is';//针对2010年5月31号以前的日志

        if(!preg_match($pattern, $log, $row))
            return false;

        $kw = html_entity_decode( urldecode($row[1]) );
        unset($row);


        if(strlen($kw)<1)
            return false;

        if($pos = strpos($kw, '/', 1))
        {
            $kw = substr($kw, 0, $pos);
        }

        if(strpos($kw, '-B_'))
        {
            $pieces = explode('-B_', $kw);
            $kw = $pieces[0];
            unset($pieces);
        }

        if(strpos($kw, '-T_'))
        {
            $pieces = explode("-T_", $kw);
            $kw = $pieces[0];
            unset($pieces);
        }

        if(strpos($kw, "-M_"))
        {
            $pieces = explode("-M_", $kw);
            $kw = $pieces[0];
            unset($pieces);
        }

        if(strpos($kw, "-Q_"))
        {
            $pieces = explode("-Q_", $kw);
            $kw = $pieces[0];
            unset($pieces);
        }

        if(strpos($kw, "-CC_"))
        {
            $pieces = explode("-CC_", $kw);
            $kw = $pieces[0];
            unset($pieces);
        }

        if(strpos($kw, "-P_"))
        {
            $pieces = explode("-P_", $kw);
            $kw = $pieces[0];
            unset($pieces);
        }

        $kw = str_replace('.,', ' ', $kw);
        $kw = preg_replace('|([^A-Za-z0-9.])+|', " ", strtolower($kw));
        $kw = str_replace(array('. ', ' .'), ' ', $kw);
        $kw = trim(trim($kw), '.');
		return $kw;
    }

    //生成sitemap索引页
    public function update_index_html()
    {
        require_once('class_page2.php');
        $oPage = new page2;
        $oPage->PerPage = 100;
        $oPage->PerDiv = 10;
        $oPage->PerDiv2 = 10;
        $oPage->Url = "/site_index";
        $oPage->Condition = ".html";

        if(!file_exists('./alldata.txt'))
            return false;
        $tmp = file('./alldata.txt');
        $index = array();
        if($tmp && is_array($tmp) && count($tmp)>0)
        {
            foreach($tmp as $k => $v)
            {
                list($date, $kw) = explode("\t", $v);
                $index[$k]['kw'] = trim($kw);
                $index[$k]['date'] = trim($date);
            }
            krsort($index);
        }

        $total = count($index);
        $oPage->Count = $total;
        $pagenum = intval(($total/$oPage->PerPage) + 1);

        for($i=1; $i<=$pagenum; $i++)
        {
            $oPage->Page = $i;

            $subindex = array_slice($index, ($i-1)*$oPage->PerPage, $oPage->PerPage);
            $this->tpl->assign('products', $subindex);
            $this->tpl->assign( "p1", $oPage->getPage());
            $this->tpl->assign( "p2", $oPage->getDiv());
            $this->tpl->assign( "p3", $oPage->getDiv2());
            $indexbuf = $this->tpl->fetch('www.tootoo.com-index.html');
            $sitemap_name = $i == 1 ? "site_index.html" : "site_index_{$i}.html";
            file_put_contents('./html_tmp/'.$sitemap_name,$indexbuf);
        }

        echo "4. update index end\n";
    }

    public function create_sitemap_html()
    {
        $date = $this->date;

        $urls = array();
        if(!file_exists("./newadd/add_{$date}.txt")) ///newadd/add_
            return false;
        $h = fopen("./newadd/add_{$date}.txt", 'r');

        while(!feof($h))
        {
            $buff = fgets($h);
            //list($word, $hit) = explode(',', $buff);
			$word = trim($buff);
            $urls[$word] = 1;
        }
        fclose($h);
        //arsort($urls);
        //$top30 = array_slice($urls, 0, 30);
        //print_r($top30);  //搜索量在前30的关键词
        $total = count($urls);
        $this->page->Count = $total;
        $pagenum = intval(($total/$this->page->PerPage) + 1);

        //保存所有url到文本
        for($i=1; $i<=$pagenum; $i++)
        {
            $f_simp = fopen("sitemap_url.txt", "a");
            fputs($f_simp, "/datesitemap/$date"."_$i.html\n");
            fclose($f_simp);
        }

        //生成html
        for($i=1; $i<=$pagenum; $i++)
        {
            $this->page->Page = $i;
            $this->page->Url = "/datesitemap/{$date}_";

            $suburls = array_slice($urls, ($i-1)*$this->page->PerPage, $this->page->PerPage);
            $this->tpl->assign( "urls", $suburls);
            $this->tpl->assign( "p1", $this->page->getPage());
            $this->tpl->assign( "p2", $this->page->getDiv());
            $this->tpl->assign( "p3", $this->page->getDiv2());
            $wordpagebuf = $this->tpl->fetch("www.tootoo.com-word.html");
            $dir = "./html_tmp/";
            if(!file_exists($dir))
            {
                mkdir($dir, 0775);
            }
            $dir = $dir."datesitemap/";
            if(!file_exists($dir))
            {
                mkdir($dir, 0775);
            }
            $fn = $dir."$date"."_$i.html";
            file_put_contents($fn,$wordpagebuf);
        }

        echo "5. create sitemap html end\n";
    }

    public function add_keyword_to_index()
    {
        //$file为从前一天日志得到的唯一关键词文本
        $file = "./newadd/add_{$this->date}.txt";
        if(!file_exists($file))
        {
            echo "keyword txt is not exist";
            return false;
        }

        $kw = $this->get_rand_kw($file);
        $date = $this->date;

        //将关键词添加到index文本
        $alldata_index = './alldata.txt';
        $h = fopen($alldata_index, 'a');
        fwrite($h, $date."\t".$kw."\n");
        fclose($h);
        chmod($alldata_index,0775);

        echo "3. add kw to index end\n";
    }

    public function get_rand_kw($file)
    {
        $f = @fopen($file, "r");
        $times = rand(100, 500);
        $i=0;
        $buffer = array();
        while($i<$times)
        {
            $buffer = fgets($f);
            $i++;
        }
        fclose($f);
        //$buffer = explode(',',$buffer);
        //$kw = trim($buffer[0]);
		$kw = trim($buffer);
        if(strlen($kw)<8 || is_numeric($kw))
        {
            echo $kw."\n";
            $kw = $this->get_rand_kw($file);
        }
        return $kw;
    }

    public function move_tmp()
    {
        system("cp -rf ./html_tmp/* ./html/");
        system("rm -r ./html_tmp/*");

        echo "7. move html_tmp end\n";
    }

    public function copy_tmp()
    {
        system("scp -r ./html_tmp/* liuyan@172.18.0.39:/home/www/nine/ebs/");
        system("scp -r ./html_tmp/* liuyan@172.18.0.37:/home/www/nine/ebs/");
        system("scp -r ./html_tmp/* liuyan@172.18.0.36:/home/www/nine/ebs/");
	system("scp -r ./html_tmp/* liuyan@172.18.0.73:/home/www/nine/ebs/");

        echo "6. copy html_tmp end\n";
    }



    /**
     *  生成索引文本 alldata.txt
     *  2010-5-31以前数据用以下处理生成索引文本
     */
    public function create_old_datalist()
    {


        //创建index数据
        $index = array();
        $path = './newadd/';
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    list($date, $ext) = explode('.', $file);

                    $kw = $this->get_rand_kw($path.$file);

                    $index[$date] = $kw;
                    unset($date, $ext, $buffer, $kw, $hits);
                }
            }
            closedir($handle);
        }
        ksort($index);
        $alldata_index = './alldata.txt';
        $h = fopen($alldata_index, 'a');
        foreach($index as $k => $v)
        {
            fwrite($h, $k."\t".$v."\n");
        }
        fclose($h);
        chmod($alldata_index,0775);
        echo "end";
    }

    /**
     *  全部重新生成以前数据到 html
     *
     */
    public function make_olddata_to_html()
    {
        //临时处理老数据
        $tmp = file('./alldata.txt');
        $index = array();
        if($tmp && is_array($tmp) && count($tmp)>0)
        {
            foreach($tmp as $k => $v)
            {
                list($t, $kw) = explode("\t", $v);
                $index[$t] = trim($kw);
            }
            ksort($index);
        }

        foreach($index as $date => $kw)
        {
            $urls = array();
            if(!file_exists("./newadd/add_{$date}.txt"))
                return false;
            $h = fopen("./newadd/add_{$date}.txt", 'r');
			

            while(!feof($h))
            {
				
                $buff = fgets($h);
                //list($word, $hit) = explode(',', $buff);
				$word = trim($buff);
				//echo $word."\n";
                $urls[$word] = 1;
            }
            fclose($h);
            //arsort($urls);
            //$top30 = array_slice($urls, 0, 30);
            //print_r($top30);
            $total = count($urls);
            $this->page->Count = $total;
            $pagenum = intval(($total/$this->page->PerPage) + 1);

            //保存所有url到文本
            for($i=1; $i<=$pagenum; $i++)
            {
                $f_simp = fopen("sitemap_url.txt", "a");//重写
                fputs($f_simp, "/datesitemap/$date"."_$i.html\n");
                fclose($f_simp);
            }

            //生成html
            for($i=1; $i<=$pagenum; $i++)
            {
                $this->page->Page = $i;
                $this->page->Url = "/datesitemap/{$date}_";
                $suburls = array_slice($urls, ($i-1) * $this->page->PerPage, $this->page->PerPage);
                $this->tpl->assign( "urls", $suburls);
                $this->tpl->assign( "p1", $this->page->getPage());
                $this->tpl->assign( "p2", $this->page->getDiv());
                $this->tpl->assign( "p3", $this->page->getDiv2());
                $wordpagebuf = $this->tpl->fetch("www.tootoo.com-word.html");
                $dir = "./html/";
                if(!file_exists($dir))
                {
                    mkdir($dir, 0775);
                }
                $dir = $dir."datesitemap/";
                if(!file_exists($dir))
                {
                    mkdir($dir, 0775);
                }
                $fn = $dir."$date"."_$i.html";
                file_put_contents($fn,$wordpagebuf);
            }
            echo "$date\n";
        }
        echo "end----------------\n";
    }
}
