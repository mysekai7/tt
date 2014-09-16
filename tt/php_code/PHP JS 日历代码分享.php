<style>
/* style for calendar */
.calendar
{
    background-color: #FFFFFF;
    border: 1px solid #003366;
}
.calendar .title
{
    background-image:  url("/theme/monthbg.gif");
    line-height: 17pt;
    background-color: #D8E2EC;
    text-align: center;
    vertical-align: middle;
    font-family: Geneva, Verdana, Arial, sans-serif;
    font-size: 13px;
    font-weight: Bold;
    color: #252216;
}
.calendar .head
{
    background-image:  url("dayBg.gif");
    font-family: Geneva, Verdana, Arial, sans-serif;
    font-size: 10px;
    font-weight: Bold;
    color: #433D27;
}
.calendar tbody
{
    line-height: 11pt;
    background-color: #F5F4D3;
    text-align: center;
    vertical-align: middle;
}
.calendar td
{
    font-family: Geneva, Verdana, Arial, sans-serif;
    />    text-align: center;
    vertical-align: middle;
    font-family: Geneva, Verdana, Arial, sans-serif;
    font-size: 13px;
    font-weight: Bold;
    color: #252216;
}
.calendar .head
{
    background-image:  url("dayBg.gif");
    font-family: Geneva, Verdana, Arial, sans-serif;
    font-size: 10px;
    font-weight: Bold;
    color: #433D27;
}
.calendar tbody
{
    line-height: 11pt;
    background-color: #F5F4D3;
    text-align: center;
    vertical-align: middle;
}
.calendar td
{
    font-family: Geneva, Verdana, Arial, sans-serif;
    font-size: 10px;
    line-height: 15pt;
    text-align: center;
    vertical-align: middle;
    width: 25px;
}
.calendar .weekday
{
    background-color: #e0e0e0;
}
.calendar .weekend
{
    background-color: #d0d0d0;
}
.calendar .today
{
    background-color: #f7bebd;
}
.calendar .exmonth
{
    background-color: #eeeeee;
}
.calendar a
{
    text-decoration: none;
    cursor: hand;
}
</style>

--------------------------------------------------------------------------------


<?php
// 显示某天所在月䥦ont-size: 10px;
    line-height: 15pt;
    text-align: center;
    vertical-align: middle;
    width: 25px;
}
.calendar .weekday
{
    background-color: #e0e0e0;
}
.calendar .weekend
{
    background-color: #d0d0d0;
}
.calendar .today
{
    background-color: #f7bebd;
}
.calendar .exmonth
{
    background-color: #eeeeee;
}
.calendar a
{
    text-decoration: none;
    cursor: hand;
}
</style>

--------------------------------------------------------------------------------


<?php
// 显示某天所在月份的日历
/*
* param: $time  timestamp of desired date and time
*/
function calendar($time)
{
    $start=mktime(0, 0, 0, date('m', $time), 1/*
* param: $time  timestamp of desired date and time
*/
function calendar($time)
{
    $start=mktime(0, 0, 0, date('m', $time), 1,  date('Y', $time));
    $start=$start-date('w', $start)*86400; // extent to start of week
    $end=mktime,  date('Y', $time));
    $start=$start-date('w', $start)*86400; // extent to start of week
    $end=mktime(0, 0, 0, date('m', $time)+1, 1,  date('Y', $time));
    $end=$end(0, 0, 0, date('m', $time)+1, 1,  date('Y', $time));
    $end=$end+(7-date('w', $end))*86400; // extent to end of week
    //$sWeekday=array('日','一','二','三','四','五','六');
    $sWeekday=array('S','M','T','W'+(7-date('w', $end))*86400; // extent to end of week
    //$sWeekday=array('日','一','二','三','四','五','六');
    $sWeekday=array('S','M','T','W','T','F','S');
    $title=date('M Y', $time);
    $prev=mktime(0, 0, 0, ,'T','F','S');
    $title=date('M Y', $time);
    $prev=mktime(0, 0, 0, date('m', $time)-1, 1,  date('Y', $time));
    $next=mktime(0, 0, 0, date('m', $time)-1, 1,  date('Y', $time));
    $next=mktime(0, 0, 0, date('m', $time)+1, 1,  date('Y', $time));
    $url=$_SERVER['PHP_SELF'].'?time=';

    date('m', $time)+1, 1,  date('Y', $time));
    $url=$_SERVER['PHP_SELF'].'?time=';

    $str = '';
    $str .= <<<END
<table class="calendar" cellspacing="1">
    <tr class="title">
        <th colspan="2"><a href="{$url}{$prev}"><<</a></th>
        <th colspan="3"ont>$str = '';
    $str .= <<<END
<table class="calendar" cellspacing="1">
    <tr class="title">
        <th colspan="2"><a href="{$url}{$prev}"><<</a></th>
        <th colspan="3">{$title}</td>
        <th colspan="2"><a href="{$url}{$next}">>></a></th>
    </tr>
    <tr class="head">
        <th>{$sWeekday[0] }<;>{$title}</td>
        <th colspan="2"><a href="{$url}{$next}">>></a></th>
    </tr>
    <tr class="head">
        <th>{$sWeekday[0] }</td>
        <th>{$sWeekday[1] }</td>
        <th>{$sWeekday[2] }</td>
        <th>{$sWeekday[3] }</td>
        <th>        <th>{$sWeekday[1] }</td>
        <th>{$sWeekday[2] }</td>
        <th>{$sWeekday[3] }</td>
        <th>{$sWeekday[4] }</td>
        <th>{$sWeekday[5] }</td>
        <th>{$sWeekday[6] }</td>
    </tr>
    <tr>

{$sWeekday[4] }</td>
        <th>{$sWeekday[5] }</td>
        <th>{$sWeekday[6] }</td>
    </tr>
    <tr>

END;

    for($stamp=$start;$stamp<$end;$stamp+=86400) // loop through each day, which is 86400 seconds
    {
        $weekday=date('w', $stampEND;

    for($stamp=$start;$stamp<$end;$stamp+=86400) // loop through each day, which is 86400 seconds
    {
        $weekday=date('w', $stamp);
        if(date('m', $stamp)!=date('m', $time)) $style='exmonth';
        else if(date('Y-m-d', $stamp)>);
        if(date('m', $stamp)!=date('m', $time)) $style='exmonth';
        else if(date('Y-m-d', $stamp)==date('Y-m-d')) $style='today';
        else if(date('w', $stamp)==0 || date('w', $stamp)==6) $style='weekend';
        else $style='weekday';
        $str.= "\t\t".'<td class="'.$style.'"><a href="'.$url.$stamp.'">'.sprintf('%d',date('d', $stamp)).'</a></td>'."\n";
        if(date('w', $stamp)==6) $str.="\t".'</tr>'."\n";
    }

    $str .= '</table>';
    return $str;
}
?>