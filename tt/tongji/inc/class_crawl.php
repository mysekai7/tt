<?php
class crawl
{
    public $site;
    public $urls;
    public $data_dir;

    public function __construct()
    {
        global $C, $site, $engine;
        $this->site = $site;
        $this->urls = $C->CRAWL_URLS[$this->site]; //当前网站urls
        //url以后针对多engine改进
        $this->data_dir = $C->DATA_DIR.'crawl/'.$engine.'/'.$this->site.'/';
    }

    //获取数据目录
    public function get_data_dir($engine)
    {
        return $GLOBALS['C']->DATA_DIR.'crawl/'.$engine.'/'.$this->site.'/';
    }

    /**
     *  返回指定日期的抓取
     *  $day <string> 时间 20100717
     *  $engine <string> google,baidu那些...
     */
    public function get_crawls_by_date($date, $engine='google')
    {
        if(!$date)
        {
            return FALSE;
        }

        $time = strtotime($date);
        $data_dir = $this->get_data_dir($engine);
        $ym = date('Ym',$time);
        $no = date('j', $time);
        $return =array();
        foreach($this->urls as $url)
        {
            //$filename = $data_dir.md5($url).'/'.$ym.'.txt';
            $filename = $data_dir.md5($url).'/'.$ym.'.txt';

            if(!file_exists($filename))
            {
                continue;
            }

            $arr = file($filename);

            if(count($arr)>0)
            {
                foreach($arr as $val)
                {
                    list($index, $res) = explode('#####', $val);
                    if($index == $no)
                    {
                        $return[$url] = unserialize($res);
                    }
                }
            }
        }
        return $return;
    }

    /**
     *  按url查找
     */
    public function get_crawl_by_url($url, $key, $date='')
    {
        if(!$url || !$key)
        {
            return FALSE;
        }
        $mon = date('Ym', strtotime($date));
        if(!$mon)
        {
            $mon = date('Ym');
        }
        $filename = $this->data_dir.md5($url).'/'.$mon.'.txt';

        if(!file_exists($filename))
        {
            return FALSE;
        }
        $return = array();
        $arr = file($filename);
        if(count($arr)>0)
        {
            foreach($arr as $val)
            {
                list($mday, $res) = explode('#####', $val);
                $tmp = unserialize($res);
                //$return[$mday] = (int)$tmp['count'];
                $return[$mday] = $tmp[$key];
            }
            return $return;
        }
        return FALSE;
    }

}