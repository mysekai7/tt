<?php


class crawl_count
{

    /**
     *  $cc = new crawl_count;
     *  $cc->timestamp = '时间戳';
     *  $cc->site = 'tootoo';
     *  $cc->crawler = 'Googlebot';
     *  $cc->search_urls = array('buy-','d-rp','d-c','d-p','s-','company');
     *  $cc->find($log);
     *  $cc->do_result();
     *  $cc->output();
     *  $cc->save();
     */


    public $C;
    public $log;
    public $site;
    public $crawler;
    public $search_urls;
    public $result;
    public $timestamp;


    public function __construct()
    {
        $this->C = $GLOBALS['C'];
    }

    public function find($log)
    {
        if(!$log)
            return FALSE;

        if(!stripos($log, $this->crawler))
            return FALSE;

        //preg_match('|GET\s(.*?)\sHTTP/|is', $log, $row);
		if($this->site == 'chinatopsupplier')
		{
			preg_match('|GET\s(.*?)\sHTTP.*?(\d+)$|is', trim($log), $row);
		}
		else
		{
			preg_match('|GET\s(.*?)\sHTTP.*?(\d+)"$|is', trim($log), $row);
		}
        $url = $row[1];
        $time = intval($row[2]);

        foreach($this->search_urls as $val)
        {
            $pattern = '|^/'.$val.'|';
            if(preg_match($pattern, $url))
            {
                $this->result['all']['count']++;
                $this->result[$val]['count']++;

                $this->result['all']['time'] += $time;
                $this->result[$val]['time'] += $time;

                if($time > 1000000)
                {
                    $this->result[$val]['time_more_than_1']++;
                    $this->result['all']['time_more_than_1']++;
                }
                if($time > 2000000)
                {
                    $this->result[$val]['time_more_than_2']++;
                    $this->result['all']['time_more_than_2']++;
                }

                break;
            }
        }
    }

    public function do_result()
    {
        if(!$this->result)
            return FALSE;
        foreach($this->result as $k => $v)
        {
            foreach($v as $kk => $vv)
            {
                if($kk == 'time')
                {
                    $this->result[$k]['average_time'] = ($vv / 1000000) / $this->result[$k]['count'];
                    $this->result[$k]['average_time'] = round($this->result[$k]['average_time'], 2);
                    unset($this->result[$k][$kk]);
                }
            }

            $this->result[$k]['more_than_1s'] = $v['time_more_than_1'] / $v['count'] * 100;
            $this->result[$k]['more_than_1s'] = number_format($this->result[$k]['more_than_1s'], 2, '.', '').'%';
            $this->result[$k]['more_than_2s'] = $v['time_more_than_2'] / $v['count'] * 100;
            $this->result[$k]['more_than_2s'] = number_format($this->result[$k]['more_than_2s'], 2, '.', '').'%';
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

        foreach($this->result as $url => $val)
        {
            $dir = $this->C->DATA_DIR.'crawl/google/'.$this->site.'/'.md5($url).'/';
            //$dir = './crawl/google/'.$this->site.'/'.md5($url).'/';//测试
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