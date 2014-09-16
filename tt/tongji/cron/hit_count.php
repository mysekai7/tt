<?php


class hit_count
{
    //$hc = new hit_count;
    //$hc->timestamp = '时间戳';
    //$hc->site = 'tootoo';
    //$hc->mark = 'tt?id'; //查找标记
    //$hc->words = array('k'=>'list.txt'); //搜索关键词列表
    //$hc->parse_words(); //处理关键词列表
    //$hc->find($log);
    //$hc->output();
    //$hc->save();

    public $log;
    public $words;
    public $words_tmp;
    public $mark;
    public $url;
    public $result;
    public $site;
    public $C;
    public $timstamp;

    public function __construct()
    {
        $this->C = $GLOBALS['C'];
    }

    public function parse_words()
    {
        if(!is_array($this->words) || count($this->words)<1)
            return FALSE;

        foreach($this->words as $key => $val)
        {
            $filename = '/home/whz/logs/'.$val;
            if(!file_exists($filename))
                continue;

            $arr = file($filename);
            if(!$arr || count($arr)<1)
                continue;

            foreach($arr as $k => $v)
            {
                list($kw, $name) = explode('####', $v);
                $this->words_tmp[$key][$k]['kw'] = trim($kw);
                $this->words_tmp[$key][$k]['name'] = trim($name);
            }
        }
        return $this->words_tmp;
    }

    public function find($log)
    {
        if(!$log)
            return FALSE;

        if( !stripos($log, $this->mark) )
            return FALSE;

        //解析url
        preg_match('|GET\s(.*?)\sHTTP/|is', $log, $row);
        $url = $row[1];

        //遍历关键词列表
        foreach($this->words_tmp as $key => $val)
        {
            foreach($val as $k => $v)
            {
                if(stripos($url, $v['kw']))
                {
                    $this->result[$key]['total']++;
                    $this->result[$key]['data'][$k]['hits']++;
                    $this->result[$key]['data'][$k]['name'] = $v['name'];
                    break 2;
                }
            }
        }
    }

    public function output()
    {
        print_r($this->result);
    }

    public function save()
    {
        if(!$this->result)
            return FALSE;

        $filename = date('Ym', $this->timestamp).'.txt';
        $today = getdate($this->timestamp);

        foreach($this->result as $key => $val)
        {
            //保存目录
            $dir = $this->C->DATA_DIR.'hits/'.$this->site.'/'.md5($key).'/';
            //$dir = 'hits/'.$this->site.'/'.md5($key).'/';//测试

            if(!file_exists($dir))
                mkdir_p($dir);

            //判断是否已经记录过
            if(file_exists($dir.$filename))
            {
                $res = file($dir.$filename);
                if(count($res)>0)
                {
                    $last = explode('#####', array_pop($res));
                    if($last[0] == $today['mday'])
                    {
                        continue;
                    }
                }
            }

            ksort($val);
            $content = $today['mday'].'#####'.serialize($val)."\n";

            //记录到文本


            $fp = fopen($dir.$filename, "a");
            if($fp)
            {
                fwrite($fp, $content);
                fclose($fp);
            }
            @chmod($dir.$filename, 0777);

        }

    }


}