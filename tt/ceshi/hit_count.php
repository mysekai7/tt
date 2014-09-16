<?php



class hit_count
{


    public $search;

    public function __construct()
    {
    }

    public function parse_file()
    {
        if(!$this->search)
            return FALSE;

        $return = array();
        foreach($this->search as $key => $val)
        {

            //$filename = '/home/whz/logs/'.$val;
            $filename = $val;
            if(!file_exists($filename))
                continue;

            $tmp = file($filename);
            if($tmp && count($tmp))
            {
                foreach($tmp as $k => $v)
                {
                    list($kword, $cname) = explode('####', $v);
                    $return[$key][$k]['kword'] = $kword;
                    $return[$key][$k]['cname'] = $cname;
                }
            }
        }
        return $return;
    }
}