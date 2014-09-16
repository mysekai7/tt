<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script type="text/javascript" src="<?php echo $C->SITE_URL.'/theme/'; ?>jquery1.4.2.js"></script>
    <script type="text/javascript" src="<?php echo $C->SITE_URL.'/theme/'; ?>wbox.js"></script>
    <script type="text/javascript" src="<?php echo $C->SITE_URL.'/theme/'; ?>js.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $C->SITE_URL.'/theme/'; ?>style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $C->SITE_URL.'/theme/'; ?>wbox/wbox.css" />
    <title>tongji</title>

</head>
<body>

    <div id="header">
        <h1><?php echo ucwords($sitename); ?> 统计</h1>
    </div>
    <div id="main">
        <div id="sub-title" class="title">
            <?php echo $today;?><span> 和 <?php echo $lastday ?> 比较</span>
        </div>
        <div id="content">
            <div id="summary">
                <h2 class="mod_title title">概要</h2>
                <div class="tbody">
                    <table>
                        <tr>
                            <td class="numeric center title"><?php echo number_format($record_summary['curr']) ?></td>
                            <td class="text">records</td>
                        </tr>
                        <tr>
                            <td class="numeric center prev"><?php echo number_format($record_summary['last']) ?></td>
                            <td><?php echo $record_summary['diff'] ?></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td class="numeric center title"><?php echo number_format($crawl_summary['curr']) ?></td>
                            <td class="text">crawls</td>
                        </tr>
                        <tr>
                            <td class="numeric center prev"><?php echo number_format($crawl_summary['last']) ?></td>
                            <td><?php echo $crawl_summary['diff'] ?></td>
                        </tr>
                    </table>
					<table>
                        <tr>
                            <td class="numeric center title">当月平均抓取</td>
                        </tr>
                        <tr>
                            <td class="center"><?php echo $current_average_crawl ?></td>
                        </tr>
                    </table>
                    <div class="clear"></div>
                </div>
            </div>

            <div id="record">
                <h2 class="mod_title title"><?php echo ucwords($engine); ?>收录/天</h2>
                <div class="tbody list">
                    <table>
                        <tr>
                            <th class="first">统计链接</th>
                            <th class="curr center">收录数</th>
                            <th class="last center"> </th>
                            <th class="diff">±</th>
                        </tr>
                    <?php
                        if(isset($curr_record['data']) && count($curr_record['data'])>0){
                            foreach($curr_record['data'] as $url => $num){
                    ?>
                        <tr>
                            <td class="first">
                            <?php
                                $chart_record = "show_record_chart('$engine','$sitename',encodeURIComponent('$url'),'$today')";
                                echo '<a href="javascript:void(0)" onclick="'.$chart_record.'">'.$url.'</a><img src="theme/img/external.png" />';
                            ?>
                            </td>
                            <td class="curr center"><?php echo number_format($num); ?></td>
                            <td class="last center"><?php if($last_record['data']){ echo number_format($last_record['data'][$url]); }else{echo '—';}  ?></td>
                            <td class="diff">
                            <?php
                                if($last_record['data']){
                                    //output_diff($num, $last_record['data'][$url]);
                                    echo percent_diff($num,$last_record['data'][$url]);
                                }
                            ?>
                            </td>
                        </tr>
                    <?php
                            }
                        }
                    ?>
                    </table>
                </div>
            </div>


            <!-- 抓取 -->
            <?php if(isset($curr_crawl) && count($curr_crawl)>0){ ?>
            <div id="crawl">
                <h2 class="mod_title title"><?php echo ucwords($engine); ?>抓取/天</h2>
                <div class="tbody list">
                    <table>
                        <tr>
                            <th class="first">统计链接</th>
                            <th class="center">总抓取量</th>
                            <th class="center"></th>
                            <th class="center">平均时间(秒)</th>
                            <th class="center"></th>
                            <th class="center">大于1秒</th>
                            <th class="center">大于2秒</th>
                            <th class="center">大于1秒%</th>
                            <th class="center">大于2秒%</th>
                            <!--th class="diff">±</th-->
                        </tr>
                    <?php
                        foreach($curr_crawl as $url => $val){
                        echo '<tr>';
                            echo '<td class="first">'.$url.'</td>';
                            //总数
                            $chart_count = "show_crawl_chart('$engine','$sitename',encodeURIComponent('{$url}'),'count','$today')";
                            echo '<td class="center"><a href="javascript:void(0)" onclick="'.$chart_count.'">'.number_format($val['count']).'</a></td>';
                            echo "<td class='last center'>";
                            if($last_crawl){ echo number_format($last_crawl[$url]['count']); }else{echo '—';}
                            echo "</td>";
                            $chart_time = "show_crawl_chart('$engine','$sitename',encodeURIComponent('{$url}'),'average_time','$today')";
                            echo '<td class="center"><a href="javascript:void(0)" onclick="'.$chart_time.'">';
                            if(floatval($val['average_time']) > 0.5){
                                echo '<b style="color:red;">'.$val['average_time'].'</b>';
                            } else {
                                echo $val['average_time'];
                            }
                            echo '</a></td>';
                            echo "<td class='center last'>";
                            //var_dump($last_crawl[$url]);
                            if($last_crawl){ echo $last_crawl[$url]['average_time']; }else{echo '—';}
                            echo "</td>";
                            echo "<td class='center'>".number_format($val['time_more_than_1'])."</td>";
                            echo "<td class='center'>{$val['time_more_than_2']}</td>";
                            echo "<td class='center'>{$val['more_than_1s']}</td>";
                            echo "<td class='center'>{$val['more_than_2s']}</td>";
                            //echo "<td class='diff'>";
                            //output_diff($val['count'], $last_crawl[$url]['count']);
                            //echo "</td>";
                        echo '</tr>';
                        }
                    ?>
                    </table>
                </div>
            </div>
            <?php } ?>

            <?php
            if($curr_hits && count($curr_hits)>0)
                $i=0;
                foreach($curr_hits as $key => $val){
            ?>
            <div class="w350 <?php if($i%2 == 0){ echo 'left'; }else{ echo 'right'; } ?>">
                <h2 class="mod_title title">Hits: <?php echo ucwords($key).' <small>('.$val['total'].') '.percent_diff($val['total'],$prev_hits[$key]['total']).'</small>'; ?></h2>
                <div class="tbody list">
                    <table>
                        <tr>
                            <th class="first">统计内容</th>
                            <th class="center">点击(次)</th>
                            <th class="center diff2"> </th>
                            <th class="diff2">±</th>
                        </tr>
                    <?php
                    if($val['data'] && is_array($val['data']) && count($val['data'])>0)
                    {
                        ksort($val['data']);
                        foreach($val['data'] as $k => $v){
                    ?>
                        <tr>
                            <td class="first"><?php echo $v['name']; ?></td>
                            <td class="center"><?php echo $v['hits']; ?></td>
                            <td class="center diff2 last"><?php if($prev_hits[$key]['data'][$k]['hits']){echo $prev_hits[$key]['data'][$k]['hits'];}else{echo '-';} ?></td>
                            <td class="diff2"><?php output_diff($v['hits'], $prev_hits[$key]['data'][$k]['hits']); ?></td>
                        </tr>
                    <?php
                        }
                    }
                    ?>
                    </table>
                </div>
            </div>
            <?php
                        $i++;
                }
            ?>


        </div>
        <!-- content end -->




        <div id="sidebar">
            <div id="calender">
                <?php echo $calender_str; ?>
            </div>

            <div id="select">
                <h2>选择主站</h2>
                <select onchange='location.href="<?php echo $C->SITE_URL.'/index.php?engine='.$engine.'&site='; ?>"+this.value'>
                    <?php
                        foreach($C->RECORD_URLS as $key => $val){
                            if($sitename == $key)
                            {
                                echo '<option value="'.$key.'" selected>'.ucwords($key).'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$key.'">'.ucwords($key).'</option>';
                            }

                        }
                    ?>
                </select>
                <h2>选择搜索引擎</h2>
                <select onchange='location.href="<?php echo $C->SITE_URL.'/index.php?site='.$sitename.'&engine='; ?>"+this.value'>
                    <?php
                        foreach($C->RECORD_ENGINE as $key => $val){
                            if($engine == $key)
                            {
                                echo '<option value="'.$key.'" selected>'.ucwords($key).'</option>';
                            }
                            else
                            {
                                echo '<option value="'.$key.'" disabled>'.ucwords($key).'</option>';
                            }

                        }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div id="wbox1"></div>


</body>
</html>