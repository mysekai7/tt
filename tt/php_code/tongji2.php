<?php

/**
    /usr/local/php5/bin/php tongji2.php outfile.txt log1 [log2] [log3]

*/

class LogCount {

    public $search;     //查询列表
    public $result;     //统计结果
    public $logfile;    //日志文件
    public $output;     //输出文件

    public function __construct()
    {
        if(!file_exists('/home/whz/logs/search.txt'))
            die('search.txt no exist !!');
        $this->search   = file('/home/whz/logs/search.txt');
        $this->result   = array();
        $this->logfile  = array_slice($_SERVER['argv'], 2);
        $this->output   = '/home/whz/logs/'.$_SERVER['argv'][1];
    }

    public function start()
    {
        if(count($this->logfile) > 0)
        {
            foreach($this->logfile as $v)
            {
                if(!file_exists('/home/whz/logs/'.$v))
                    die('File '.$v.'no exists !');  //log filename

                if(count($this->search) > 0)
                {
                    foreach($this->search as $val)
                    {
                        $val = trim($val);
                        if($val != '')
                        {
                            $hits = intval(system("grep -c '$val' /home/whz/logs/{$v}"));
                            $this->result['total'] += $hits;
                            $this->result['detail'][$val] += $hits;
                        }
                    }
                }
                else
                {
                    die('search.txt is no null !');
                }
            }
        }
    }//end fun

    public function output()
    {
        global $body;
        $total = $this->result['total'];
        $str .= 'Total: '.$total." \n";
        foreach($this->result['detail'] as $k => $v)
        {
            $str .= trim($k)."\t Hits: ".$v."\tPercent: ".round(($v/$total*100), 2)."% \n";
        }
        $body = $str;
        file_put_contents($this->output, $str);
        echo "########Output Success ^-^ #########\n";
    }

}


$log = new LogCount;
$log->start();
$log->output();

?>