<?php

//$date  格式: 2010-7-18
//$c = new calender;
//echo $c->build($date);

class calender
{
    public $date;
    public $url;

    function __construct() {
    }

    function set_url($site,$engine)
    {
        $this->url = $_SERVER['PHP_SELF'].'?site='.$site.'&engine='.$engine;
    }

    function build($date) {
        if($date) {
            $timestamp = strtotime($date);
        } else {
            $timestamp = strtotime('last day');
        }

        $this->date = getdate($timestamp);

        $html = '';
        $html .= "<table cellspacing='0'>\n";
        $html .= "<tr><td colspan='7'><table width='100%'><tr>";
        //$html .= "<a href='{$_SERVER['PHP_SELF']}?date={$this->date['year']}-{$this->date['mon']}'>&lt;&lt;</a>";
        $html .= '<td>'.$this->prevMonth().'</td>';
        $html .= '<td class="yearmon">'.$this->date['month'].', '.$this->date['year'].'</td>';
        $html .= '<td>'.$this->nextMonth().'</td>';
        $html .= "</tr></table></td></tr>\n";
        $html .= "<tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr>\n";

        $days = $this->getDays();
        for($mday=1; $mday<=$days; $mday++) {
            $timestamp = mktime(0, 0, 0, $this->date['mon'], $mday, $this->date['year']);

            if($this->isFirst($mday))
                $html .= "<tr>\n";
            if($mday == 1) {
                $html .= "<tr>\n".$this->padFirst();
            }
            if($mday == $this->date['mday'])
            {
                $html .= "\t<td><b>{$mday}</b></td>\n";
            }
            else if( $timestamp >= $GLOBALS['tj_starttime'] && $timestamp <= strtotime('last day'))
            {
                $year = $this->date['year'];
                $mon = $this->date['mon'];
                $html .= "\t<td><a href='{$this->url}&date={$year}-{$mon}-{$mday}'>{$mday}</a></td>\n";
            }
            else if($mday == date('j') && date('n') == $this->date['mon'])
            {
                $html .= "\t<td><span class='today'>{$mday}</span></td>\n";
            }
            else
            {
                $html .= "\t<td>{$mday}</td>\n";
            }

            if($mday == $days) {
                //echo $this->padLast();
                $html .= $this->padLast().'</tr>';;
            }

            if($this->isLast($mday))
                $html .= "</tr>\n";
        }
        $html .= "</table>";
        return $html;
    }

    //获得当前月所有天数
    function getDays() {
        return date('t', $this->date[0]);
    }

    //返回一周内第几天
    function getWday($mday=1) {
        return date('w', mktime(0, 0, 0, $this->date['mon'], $mday, $this->date['year']));
    }

    //是否为最左侧
    function isFirst($mday) {
        if($this->getWday($mday) == 0)
            return true;
        return false;
    }

    //是否为最右侧
    function isLast($mday) {
        //echo $mday.',';
        //if($mday == 30)
            //echo $this->getWday($mday);
        if($this->getWday($mday) == 6)
            return true;
        return false;
    }

    //日历开始填充
    function padFirst() {
        $html = '';
        $days = $this->getWDay();
        for($i=0; $i<$days; $i++){
            $html .= "\t<td></td>\n";
        }
        return $html;
    }

    //日历末尾填充
    function padLast() {
        $html = '';
        $days = 6-$this->getWday($this->getDays());
        for($i=0; $i < $days; $i++){
            $html .= "\t<td></td>\n";
        }
        return $html;
    }

    //上个月
    function prevMonth() {
        $year = $this->date['year'];
        $prevMonth = $this->date['mon'] - 1;
        //$day = $this->date['mday'];
        $day = date('t', mktime(0, 0, 0, $prevMonth, 1, $year));
        if($prevMonth < 1) {
            $year = $year - 1;
            $prevMonth = 12;
        }
        return "<a class='prev_mon' href='{$this->url}&date={$year}-{$prevMonth}-{$day}'>&lt;&lt;</a>";
    }

    //下个月
    function nextMonth() {
        $year = $this->date['year'];
        $nextmonth = $this->date['mon'] + 1;
        //$day = $this->date['mday'];
        $day = 1;
        if($nextmonth > 12) {
            $year = $year + 1;
            $nextmonth = 1;
        }
        return "<a class='next_mon' href='{$this->url}&date={$year}-{$nextmonth}-{$day}'>&gt;&gt;</a>";
    }

}



