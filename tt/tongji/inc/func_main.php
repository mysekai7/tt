<?php

    function __autoload($classname)
    {
        require_once( $GLOBALS['C']->INCPATH.'inc/class_'.$classname.'.php' );
    }

    function get_sources($url)
    {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_USERAGENT, isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1');
        curl_setopt ($ch, CURLOPT_REFERER, 'http://www.google.com/');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $sources = curl_exec ($ch);
        curl_close($ch);
        return $sources;
    }

    function mkdir_p( $target ) {
        // from php.net/mkdir user contributed notes
        $target = str_replace( '//', '/', $target );
        if ( file_exists( $target ) )
            return @is_dir( $target );

        // Attempting to create the directory may clutter up our display.
        if ( @mkdir( $target ) ) {
            $stat = @stat( dirname( $target ) );
            $dir_perms = 0777;  // Get the permission bits.
            @chmod( $target, $dir_perms );
            return true;
        } elseif ( is_dir( dirname( $target ) ) ) {
                return false;
        }

        // If the above failed, attempt to create the parent node, then try again.
        if ( ( $target != '/' ) && ( mkdir_p( dirname( $target ) ) ) )
            return mkdir_p( $target );

        return false;
    }


    /**
     * 图表输出
     * $filters <array> 月份 array('yr'=>2010, 'mo'=>7)
     *
     */
    function chart_days($filters=array(), $curr_data=array(), $prev_data=array()) {
        global $i18n, $is_handheld;
        $timestamp = strtotime('last day');

        if(!$filters)
        {
            return FALSE;
        }

        $rand_id = rand(1000,10000);

        $width = ( $is_handheld ) ? 340 : 700;

        $curr = ( array_key_exists( 'dy', $curr_data ) ) ? $curr_data['dy'] : array();
        $prev = ( array_key_exists( 'dy', $prev_data ) ) ? $prev_data['dy'] : array();

        $prev_max_dy = days_in_month( $filters['mo'] - 1, $filters['yr'] );
        $curr_max_dy = days_in_month( $filters['mo'], $filters['yr'] );
        $x_max = max( $curr_max_dy, $prev_max_dy );
        if ( $filters['mo'] == date( 'n', $timestamp) && $filters['yr'] == date( 'Y', $timestamp) ) {
            $curr_max_dy = date( 'j', $timestamp);
        }
        $max_dy = max( $curr_max_dy, $prev_max_dy );

        $curr_points = array();
        $max = 0;
        $min = -1;
        $max_index = 1;
        $min_index = 1;
        $prev_points = array();

        for ( $dy=1; $dy<=$x_max; $dy++ ) {
            if ( array_key_exists( $dy, $curr ) ) {
                $curr_points[] = $curr[$dy];
                if ( $curr[$dy] > $max ) {
                    $max = $curr[$dy];
                    $max_index = $dy;
                }
                if ( $curr[$dy] < $min || $min == -1 ) {
                    $min = $curr[$dy];
                    $min_index = $dy;
                }
            } elseif ( $dy <= $curr_max_dy ) {
                $curr_points[] = 0;
                if ( $min != 0 ) {
                    $min = 0;
                    $min_index = $dy;
                }
            } else {
                $curr_points[] = -1;
            }
        }
        $curr_max = $max;
        $curr_min = $min;

        for ( $dy=1; $dy<=$x_max; $dy++ ) {
            if ( array_key_exists( $dy, $prev ) ) {
                $prev_points[] = $prev[$dy];
                if ( $prev[$dy] > $max ) {
                    $max = $prev[$dy];
                }
                if ( $prev[$dy] < $min || $min == -1 ) {
                    $min = $prev[$dy];
                }
            } elseif ( $dy <= $prev_max_dy ) {
                $prev_points[] = 0;
                if ( $min != 0 ) {
                    $min = 0;
                }
            } else {
                $prev_points[] = -1;
            }
        }

        $scale_min = max( 0, floor( $min - ( $max * 0.05 ) ) );
        $scale_max = max( 1, ceil( $max * 1.05 ) );

        echo '<div class="grid12">';
        //echo '<h3>'.$i18n->hsc( 'titles', 'hits' ).' ∕ '.$i18n->hsc( 'details', 'day' ).'</h3>';
        //echo '<h3>'.ucwords($engine).'收录/天 <small style="font:11px Verdana; color:green;">'.$url.'</small></h3>';
        echo '<div class="tbody">';
        echo '<img border="0" src="http://chart.apis.google.com/chart?';
        echo 'chs='.$width.'x198';
        echo '&amp;chd=t:'.implode( ',', $prev_points ).'|'.implode( ',', $curr_points );
        echo '&amp;chds='.$scale_min.','.$scale_max.','.$scale_min.','.$scale_max;
        echo '&amp;chco=CCCCCC,333333';
        echo '&amp;chls=1|2.5';
        echo '&amp;chma=0,0,10,0';
        echo '&amp;chxt=x,y';
        echo '&amp;chxs=0,333333,10,0,t|1,333333,10,1,t';
        echo '&amp;chm=o,333333,1,-1,9|o,FFFFFF,1,-1,5|o,CCCCCC,0,-1,5,-1|o,FF3333,1,'.( $min_index - 1 ).',5|o,00CC00,1,'.( $max_index - 1 ).',5';
        echo '&amp;chxl=0:';
        for ( $dy=1; $dy<=$x_max; $dy++ ) {
            echo '|'.$dy;
        }
        echo '&amp;chxr=1,'.$scale_min.','.$scale_max;
        echo '&amp;cht=lc';
        echo '" alt="" width="'.$width.'" height="198" usemap="#daysmap_'.$rand_id.'" />';
        echo '</div></div>';
        echo '<map name="daysmap_'.$rand_id.'">'."\n";
        $dy = 1;
        $n_points = max( sizeof( $prev_points ), sizeof( $curr_points ) );
        foreach ( $curr_points as $point ) {
            if ( $point == -1 ) {
                continue;
            }

            $x = round( $dy / $n_points * $width );
            $y = round( ( $scale_max - $point ) / ( $scale_max - $scale_min ) * 181 );

            echo '<area shape="circle" coords="'.$x.','.$y.',15" title="'.$point.'';
            //echo '" href="./'.filter_url( array_merge( $filters, array( 'dy' => $dy ) ) ).'" />'."\n";
            echo '" href="javascript:void(0)" />'."\n";

            $dy++;
        }
        echo '</map>'."\n";
    }

    function days_in_month( $_mo, $_yr ) {
        return date( 'j', mktime( 12, 0, 0, $_mo + 1, 0, $_yr ) );
    }

    function to1dp( $_number ) {
        return number_format( $_number, 1, '.', '' );
    }

    function format_number( $_number, $_dp=1 )
    {
        $str = number_format( $_number, $_dp, '.', ',' );
        if ( $str == '0.0' && $_dp == 1 ) {
            $str2 = number_format( $_number, 2, '.', ',' );
            if ( $str2 != '0.00' ) {
                return $str2;
            }
        }
        return $str;
    }

    function format_percent( $_percent )
    {
        if ( $_percent < 100 ) {
            return format_number( $_percent );
        } else {
            return round( $_percent );
        }
    }

    function output_diff($curr=0, $prev=0)
    {
        if($curr > $prev){
            $diff_tmp = $curr - $prev;
            echo '<span style="color:green">↑ '.number_format($diff_tmp).'</span>';
        } else if($curr < $prev) {
            $diff_tmp = $curr - $prev;
            echo '<span style="color:red">↓ '.number_format($diff_tmp).'</span>';
        } else {
            echo '—';
        }
    }

    function percent_diff($curr=0, $prev=0)
    {
        if($curr > $prev && $prev)
        {

            $diff_tmp = format_percent(( ( $curr / $prev ) - 1 ) * 100);
            $r = '<span style="color:green;">↑ '.$diff_tmp.'%</span>';
        }
        else if($curr < $prev && $prev)
        {

            $diff_tmp = format_percent(( 1 - ( $curr /$prev ) ) * 100);
            $r = '<span style="color:red;">↓ '.$diff_tmp.'%</span>';
        }
        else
        {
            $r = '—';
        }
        return $r;
    }

    function summary_diff($curr=0, $prev=0)
    {
        $crawl_summary['curr'] = $curr;
        $crawl_summary['last'] = $prev;
        if($crawl_summary['curr']>$crawl_summary['last'] && $crawl_summary['last'])
        {

            $diff_tmp = format_percent(( ( $crawl_summary['curr'] / $crawl_summary['last'] ) - 1 ) * 100);
            $crawl_summary['diff'] = '<span style="color:green;font-weight:bold;">↑ '.$diff_tmp.'%</span>';
        }
        else if($crawl_summary['curr']<$crawl_summary['last'] && $crawl_summary['last'])
        {

            $diff_tmp = format_percent(( 1 - ( $crawl_summary['curr'] / $crawl_summary['last'] ) ) * 100);
            $crawl_summary['diff'] = '<span style="color:red;font-weight:bold;">↓ '.$diff_tmp.'%</span>';
        }
        else
        {
            $crawl_summary['diff'] = '—';
        }
        return $crawl_summary;
    }