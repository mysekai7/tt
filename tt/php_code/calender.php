<?php

class Calender {
    public $date;

    function __construct() {

    }

    function build($year=null, $month=null, $day=1) {
        if($year && $month) {
            $timestamp = mktime(0, 0, 0, $month, $day, $year);
        } else {
            $timestamp = time();
        }

        $this->date = getdate($timestamp);

        $html = '';
        $html .= "<table cellspacing='5'>";
        $html .= "<tr><td colspan='7'>";
        //$html .= "<a href='{$_SERVER['PHP_SELF']}?date={$this->date['year']}-{$this->date['mon']}'>&lt;&lt;</a>";
        $html .= $this->lastMonth();
        $html .= $this->date['month'].', '.$this->date['year'];
        $html .= "</td></tr>";
        $html .= "<tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr>";

        $days = $this->getDays();
        for($mday=1; $mday<=$days; $mday++) {
            if($this->isFirst($mday))
                $html .= "<tr>";
            if($mday == 1) {
                $html .= $this->padFirst();
            }
            if($mday == $this->date['mday']) {
                $html .= "<td><b>{$mday}</b></td>";
            } else {
                $html .= "<td>{$mday}</td>";
            }

            if($mday == $days) {
                echo $this->padLast();
                $html .= $this->padLast();
            }

            if($this->isLast($mday))
                $html .= "</tr>";
        }
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
        if($this->getWday($mday) == 6)
            return true;
        return false;
    }

    //日历开始填充
    function padFirst() {
        $html = '';
        $days = $this->getWDay();
        for($i=0; $i<$days; $i++){
            $html .= "<td>&nbsp;</td>";
        }
        return $html;
    }

    //日历末尾填充
    function padLast() {
        $html = '';
        $days = 6-$this->getWday($this->getDays());
        for($i=0; $i < $days; $i++){
            $html .= "<td>&nbsp;</td>";
        }
        return $html;
    }

    //上个月
    function lastMonth() {
        $year = $this->date['year'];
        $lastmonth = $this->date['mon'] - 1;
        if($lastmonth < 1) {
            $year = $year - 1;
            $laetmonth = 1;
        }
        return "<a href='{$_SERVER['PHP_SELF']}?date={$year}-{$lastmonth}'>&lt;&lt;</a>";
    }
}



list($year, $month) = explode('-', $_GET['date']);
$c = new Calender;
echo $c->build($year, $month);
