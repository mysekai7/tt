<?php
class crawl
{
    public $log;
    public $crawler;
    public $time;
    public $url;


    public function __construct()
    {
    }

    public function is_crawler()
    {
        //$pattern = '|Googlebot/2.1; \+http://www.google.com/bot.html|';
        //var_dump($this->log);
        //if(preg_match($pattern, $this->log))
        if(stripos($this->log, $this->crawler))
            return TRUE;
        return FALSE;
    }

    public function exists_url($find)
    {
        if(!$this->url)
            $this->get_url();
        $pattern = '|^GET\s/'.$find.'|';
        if(preg_match($pattern, $this->url))
            return TRUE;
        return FALSE;
    }

    public function get_url()
    {
        $pattern = '|GET\s(.*?)\sHTTP/|is';
        preg_match($pattern, $this->log, $row);
        $this->url = $row[0];
        return $this->url;
    }

    public function get_time()
    {
        $tmp = explode('" "', $this->log);
        $last = array_pop($tmp);
        $this->time = intval(trim($last, '"'));
        return $this->time;
    }

    public function is_more_than_1s()
    {
        if(!$this->time)
            $this->get_time();
        if($this->time > 1000000)
            return TRUE;
        return FALSE;
    }

    public function is_more_than_2s()
    {
        if(!$this->time)
            $this->get_time();
        if($this->time > 2000000)
            return TRUE;
        return FALSE;
    }
}