<?php


class Calender
{

    public $year;
    public $month;
    public $day;
    public $week;

    function __construct($year='', $month='', $day='') {
        $this->year = empty($year) ? date('Y') : $year;
        $this->month = empty($month) ? date('m') : $month;
        $this->day = empty($day) ? date('d') : $day;
        $this->week = array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa');
    }

    function build() {
        $month = $this->buildMonth();

        $html = '';
        $html .= "<table cellspacing='5'>\n";
        $html .= "<tr><td class='monthname' colspan='7'>{$this->month}, $this->year</td></tr>";
        $html .= "<tr>";
        foreach($this->week as $val) {
            $html .= "<td>{$val}</td>";
        }
        $html .= "</tr>";

        for($day=1; $day<=$month; $day++) {
            if($this->isFirst($day)) {
                $html .= "<tr>";
            }

            if($day == 1)
            {
                for($i=0; $i<$this->getDay($day); $i++){
                    $html .= "<td>&nbsp;</td>";
                }
            }

            if($this->day == $day)
                $html .= "<td><strong>{$day}</td>";
            else
                $html .= "<td>{$day}</td>";

            if($day == $month)
            {
                for($i=0; $i<(6-$this->getDay($day)); $i++){
                    $html .= "<td>&nbsp;</td>";
                }
            }


            if($this->isLast($day)) {
                $html .= "</tr>";
            }
        }

        $html .= "</table>";

        return $html;
    }

    function buildMonth() {
        return date('t', mktime(0, 0, 0, $this->month, 1, $this->year));
    }

    function isFirst($day) {
        if(date('w', mktime(0, 0, 0, $this->month, $day, $this->year)) == 0)
            return true;
        return false;
    }

    function isLast($day) {
        if(date('w', mktime(0, 0, 0, $this->month, $day, $this->year)) == 6)
            return true;
        return false;
    }

    function getDay($day) {
        return date('w', mktime(0, 0, 0, $this->month, $day, $this->year));
    }

}

$c = new Calender;
echo $c->build();







