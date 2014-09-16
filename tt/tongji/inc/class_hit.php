<?php
class hit
{
    public $site;
    public $urls;
    public $data_dir;

    public function __construct()
    {
        global $C, $site;
        $this->site = $site;
        $this->urls = $C->HITS_URLS[$this->site]; //当前网站urls
        //url以后针对多engine改进
        $this->data_dir = $C->DATA_DIR.'hits/'.$this->site.'/';
    }


    /**
     *  按天返回当天所有url数据
     *  $day <string> 时间 20100717
     *  $engine <string> google,baidu那些...
     */
    public function get_hits_by_day($date)
    {
        if(!$date)
        {
            return FALSE;
        }

        $time = strtotime($date);
        $ym = date('Ym',$time);
        $no = date('j', $time);
        $return = array();
        foreach($this->urls as $url)
        {
            $filename = $this->data_dir.md5($url).'/'.$ym.'.txt';
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
     /*
    public function get_record_by_url($url, $date='')
    {
        if(!$url)
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
                list($mday, $total) = explode('#####', $val);
                $return[$mday] = (int)$total;
            }
            return $return;
        }
        return FALSE;
    }
    */


    /**
     *  按月份返回当前搜索引擎所有url收录
     *  $mon <string> 年月 格式:201007
     */
     /*
    public function get_records_by_month($mon)
    {
        global $C;

        if(!$mon)
        {
           return FALSE;
        }

        $return = array();
        foreach($this->urls as $url)
        {
            $filename = $this->data_dir.md5($url).'/'.$mon.'.txt';
            if(!file_exists($filename))
                continue;

            $tmp = file($filename);
            if( is_array($tmp) && count($tmp)>0 )
            {
                foreach($tmp as $val)
                {
                    list($mday, $total) = explode('#####', $val);
                    $return[$url]['dy'][$mday] = (int)$total;
                }
            }
        }

        return $return;
    }
    */


}