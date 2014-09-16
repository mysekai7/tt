<?php

    function __autoload($class_name)
    {
        require_once( $GLOBALS['C']->INCPATH.'classes/class_'.$class_name.'.php' );
    }

    function my_session_name($domain)
    {
        return $GLOBALS['C']->RNDKEY.str_replace(array('.', '-'), '', $domain); //1205cccom
    }

    function cookie_domain()
    {
        $tmp = $GLOBALS['C']->DOMAIN;
        if(substr($tmp, 0, 2) == 'm.'){
            $tmp = substr($tmp, 2);
        }
        $pos = strpos($tmp, '.');
        if(FALSE === $pos){
            return '';
        }
        if(preg_match('|^[0-9\.]+$|', $tmp)){
            return $tmp;
        }
        return '.'.$tmp;    //.cc.com
    }

    function my_exception_handler(Exception $e) {
        //print "Uncaught exception of type " . get_class($e) . "\n";
        print($e->getMessage());
        exit;
    }

    //todo
    function get_request_method()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')//查看ajax发送方式
            return 'AJAX';
        else
            return $_SERVER['REQUEST_METHOD'];
    }

    function fix_input_quotes() {
        $in = array(&$_GET, &$_POST, &$_COOKIE);
        while (list($k,$v) = each($in)) {
            foreach ($v as $key => $val) {
                if (!is_array($val)) {
                    $in[$k][$key] = stripslashes($val); continue;
                }
                $in[] =& $in[$k][$key];
            }
        }
        unset($in);
    }

    function rm()
    {
        $files = func_get_args();
        foreach($files as $filename)
        {
            if( is_file($filename) && is_writable($filename) )
                unlink( $filename );
        }
    }

    function is_valid_email($email)
    {
        return preg_match('|^[a-z0-9._%-]+@([a-z0-9.-]+\.)+[a-z]{2,4}$|iu', $email);
    }

	function my_copy($source, $dest)
	{
		$res	= @copy($source, $dest);
		if( $res ) {
			chmod($dest, 0777);
			return TRUE;
		}
		if( function_exists('curl_init') && preg_match('/^(http|https|ftp)\:\/\//u', $source) ) {
			global $C;
			$dst	= fopen($dest, 'w');
			if( ! $dst ) {
				return FALSE;
			}
			$ch	= curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_FILE	=> $dst,
				CURLOPT_HEADER	=> FALSE,
				CURLOPT_URL		=> $source,
				CURLOPT_CONNECTTIMEOUT	=> 3,
				CURLOPT_TIMEOUT	=> 5,
				CURLOPT_MAXREDIRS	=> 5,
				CURLOPT_REFERER	=> $C->SITE_URL,
				CURLOPT_USERAGENT	=> isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1',
			));
			@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			$res	= curl_exec($ch);
			fclose($dst);
			if( ! $res ) {
				curl_close($ch);
				return FALSE;
			}
			if( curl_errno($ch) ) {
				curl_close($ch);
				return FALSE;
			}
			curl_close($ch);
			chmod($dest, 0777);
			return TRUE;
		}
		return FALSE;
	}

    function generate_password($len=8, $let='abcdefghkmnpqrstuvwxyzABCDEFGHKLMNPRSTUVWXYZ23456789')
    {
        $return = '';
        for($i=0; $i<$len; $i++) {
            $return .= $let{ rand(0, strlen($let)-1) };
        }
        return $return;
    }

	function get_ip()
	{
        static $realip;
        if (isset($_SERVER)){
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }

        return $realip;
	}

    function fence_ip()
    {

    }

    function msgbox()
    {

    }

    function okbox()
    {

    }

    function errorbox()
    {

    }

    function show_filesize($bytes)
    {
        $kb = ceil($bytes/1024);
        if($kb<1024)
            return $kb.'KB';
        $mb = round($kb/1024,1);
        return $mb.'MB';
    }

    function str_cut($str, $mx)
    {
        return mb_strlen($str) > $mx ? mb_substr($str, 0, $mx-1).'..' : $str;
    }

    function str_cut_link($str, $mx)
    {
        return mb_strlen($str)>$mx ? (mb_substr($str,0,$mx-6).'...'.mb_substr($str, -4)) : $str;
    }

    function nowrap($str)
    {
        return str_replace(' ', '&nbsp;', $str);
    }

    function br2nl($str)
    {
        return str_replace(array('<br />', '<br/>', '<br>'), "\r\n", $str);
    }

    function strip_url($url)
    {
        $url = preg_replace('/^(http|https):\/\/(www\.)?/iu', '', trim($url));
        $url = preg_replace('/\/$/u', '', $url);
        return trim($url);
    }

    function get_avatar($email, $size = 0)
    {
        global $options;
        if (!$options['show_avatar']) {
            $avatardb = array();
        } else {
            if (!$size) {
                if (!$options['avatar_size'] || !is_numeric($options['avatar_size'])) {
                    $size = '36';
                } else {
                    $size = $options['avatar_size'];
                }
            }

            $default = 'mystery';

            $host = 'http://www.gravatar.com';

            if ( 'mystery' == $default ) {
                $default = $host.'/avatar/ad516503a11cd5ca435acc9bb6523536?s='.$size;
                // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
            } elseif ( !empty($email) && 'gravatar_default' == $default ) {
                $default = '';
            } elseif ( 'gravatar_default' == $default ) {
                $default = "$host/avatar/s={$size}";
            } elseif ( empty($email) ) {
                $default = "$host/avatar/?d=$default&amp;s={$size}";
            }

            if ($email) {
                $src = $host.'/avatar/';
                $src .= md5(strtolower($email));
                $src .= '?s='.$size;
                $src .= '&amp;d='.urlencode($default);
                if ($options['avatar_level']) {
                    $src .= '&amp;r='.$options['avatar_level'];
                }
            } else {
                $src = $default;
            }

            $avatardb = array(
                'size' => $size,
                'src' => $src
            );
        }
        return $avatardb;

    }

    //格式化时间
    function skdate($format, $timestamp='', $convert=0){
        global $options, $timeoffset;
        !$timestamp && $timestamp = time();
        $s = gmdate($format, $timestamp + $timeoffset * 3600);

        if ($options['dateconvert'] && $convert) {
            $now = time();
            $interval = $now - $timestamp;

            //分钟内
            if ($interval < 60) {
                return '<span title="'.$s.'">'.$interval.'秒前</span>';
            }
            //小时内
            if ($interval < 3600) {
                return '<span title="'.$s.'">'.intval($interval / 60).'分钟前</span>';
            }
            //一天内
            if ($interval < 86400) {
                return '<span title="'.$s.'">'.intval($interval / 3600).'小时前</span>';
            }
            //两天内
            if ($interval < 172800) {
                return '<span title="'.$s.'">昨天 '.gmdate('H:i', $timestamp + $timeoffset * 3600).'</span>';
            }
            //一星期内
            if ($interval < 604800) {
                return '<span title="'.$s.'">'.intval($interval / 86400).'天前 '.gmdate('H:i', $timestamp + $timeoffset * 3600).'</span>';
            }
        }
        return $s;

    }

    //获得某年某月的时间戳
    function gettimestamp($year, $month) {
        $start = strtotime($year.'-'.$month.'-1');
        if ($month == 12) {
            $endyear  = $year + 1;
            $endmonth = 1;
        } else {
            $endyear  = $year;
            $endmonth = $month+1;
        }
        $end = strtotime($endyear.'-'.$endmonth.'-1');
        return $start.'-'.$end;
    }

    //获取请求来路
    function getreferer() {
        global $options;
        if(!$referer && !$_SERVER['HTTP_REFERER']) {
            $referer = $options['url'];
        } elseif (!$referer && $_SERVER['HTTP_REFERER']) {
            $referer = $_SERVER['HTTP_REFERER'];
        } else {
            $referer = htmlspecialchars($referer);
        }
        if(strpos($referer, 'post.php')) {
            $referer = $options['url'];
        }
        return $referer;
    }

    function isrobot() {
        $kw_spiders = 'Bot|Crawl|Spider|Slurp|sohu|Twiceler|lycos|robozilla|Google|baidu|msn|yahoo|sogou';
        $kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
        if(preg_match("/($kw_spiders)/i", $_SERVER['HTTP_USER_AGENT'])) {
            return 1;
        } elseif(preg_match("/($kw_browsers)/i", $_SERVER['HTTP_USER_AGENT'])) {
            return 0;
        } else {
            return 0;
        }
    }

    // 登录记录
    function loginresult($username = '', $result) {
        global $timestamp,$onlineip;
        writefile(SABLOG_ROOT.'data/log/loginlog.php', "<?PHP exit('Access Denied'); ?>\t$username\t$timestamp\t$onlineip\t$result\n", 'a');
    }

    function submitcheck($var, $cp = 0) {
        if(empty($GLOBALS[$var])) {
            return false;
        } else {
            if ($cp) {
                $msgfunc = 'redirect';
            } else {
                $msgfunc = 'message';
            }
            global $options, $seccode;

            if($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || $GLOBALS['formhash'] != formhash() || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))) {
                $msgfunc('您的请求来路不正确,无法提交.');
            } else {
                if($options['seccode']) {
                    $clientcode = $GLOBALS['clientcode'];
                    if (!$clientcode || strtolower($clientcode) != strtolower($seccode)) {
                        $seccode = random(6, 1);
                        updatesession();
                        $msgfunc('验证码错误,请返回重新输入.', $referer);
                    }
                }
                return true;
            }
        }
    }

    /**
     * 递归创建一个完整的目录(整个目录中的子目录不存在都创建)
     *
     * Will attempt to set permissions on folders.
     *
     * @since 2.0.1
     *
     * @param string $target Full path to attempt to create.
     * @return bool Whether the path was created or not. True if path already exists.
     */
    function wp_mkdir_p( $target ) {
        // from php.net/mkdir user contributed notes
        $target = str_replace( '//', '/', $target );
        if ( file_exists( $target ) )
            return @is_dir( $target );

        // Attempting to create the directory may clutter up our display.
        if ( @mkdir( $target ) ) {
            $stat = @stat( dirname( $target ) );
            $dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
            @chmod( $target, $dir_perms );
            return true;
        } elseif ( is_dir( dirname( $target ) ) ) {
                return false;
        }

        // If the above failed, attempt to create the parent node, then try again.
        if ( ( $target != '/' ) && ( wp_mkdir_p( dirname( $target ) ) ) )
            return wp_mkdir_p( $target );

        return false;
    }

    /**
     * 根据扩展名获得文件类型
     *
     * @package WordPress
     * @since 2.5.0
     * @uses apply_filters() Calls 'ext2type' hook on default supported types.
     *
     * @param string $ext The extension to search.
     * @return string|null The file type, example: audio, video, document, spreadsheet, etc. Null if not found.
     */
    function wp_ext2type( $ext ) {
        $ext2type = apply_filters('ext2type', array(
            'audio' => array('aac','ac3','aif','aiff','mp1','mp2','mp3','m3a','m4a','m4b','ogg','ram','wav','wma'),
            'video' => array('asf','avi','divx','dv','mov','mpg','mpeg','mp4','mpv','ogm','qt','rm','vob','wmv'),
            'document' => array('doc','docx','pages','odt','rtf','pdf'),
            'spreadsheet' => array('xls','xlsx','numbers','ods'),
            'interactive' => array('ppt','pptx','key','odp','swf'),
            'text' => array('txt'),
            'archive' => array('tar','bz2','gz','cab','dmg','rar','sea','sit','sqx','zip'),
            'code' => array('css','html','php','js'),
        ));
        foreach ( $ext2type as $type => $exts )
            if ( in_array($ext, $exts) )
                return $type;
    }

    /**
     * 输出调试信息.
     *
     * @copyright ninetowns
     * @param mixed $arr 输出对象
     * @param string $title 标题提示信息
     * @param int $T 输出类型
     */
    function mprint_r( $arr, $title='', $T = 0 )
    {
        global $count_mprint_r;
        $count_mprint_r++;
        if ( $count_mprint_r == 1 )
        {

            ?>
    <style type="text/css">
    <!--
    *{
        margin:0px;
        padding:0px;
    }
    .m_fileldset {
        margin: 0px;
        padding: 2px;
        background-color: #FFF;
        border: 1px dashed #09c;
        word-break:break-all;
        overflow:auto;
    }
    .m_legend {
        background-color: #06c;
        margin: 5px;
        padding: 2px;
        border: 1px solid #fff;
        color: #ffe;
        font-weight: bold;
        font-size:12px;
    }
    .m_button {
        border:1px solid #f96;
        background-color: #ffc;
    }
    .m_pre {
        text-align:left;
        font-size:11px;
    }
    -->
    </style>
    <script>
    var m_sign = true;
    function m_toggle() {
        var cs = document.getElementsByTagName("pre");
        var r = new Array();
        for(var i = 0;i<cs.length;i++)
        {
            var e = cs[i];
            if("m_pre" == e.className)
            {
                e.style.display = (m_sign == false ? "block" : "none");
                r.push( e);
            }
        }

        var cs = document.getElementsByTagName("button");

        for(var i = 0;i<cs.length;i++)
        {
            var e = cs[i];
            if("m_button" == e.className)
            {
                e.innerHTML = (m_sign == false ? "-" : "+");
            }
        }
        m_sign = !m_sign;
    }
    </script>
    <button onclick="m_toggle()">Expand/Collapse All</button>
    <?php
        }
        $temp_name = substr( md5( microtime() . $arr . $title . $T ), 0, 3 );

        ?>
    <fieldset class="m_fileldset" >
    <legend class="m_legend">
    <label style="cursor:pointer">
    <?=$title?>
    <?php
        if ( $arr )
        {

            ?>
    <button class="m_button" onclick="
        var target = document.getElementById('<?=$temp_name?>');
    if (target.style.display != 'none' )
    {
      target.style.display = 'none';
      this.innerHTML='+';
    }
    else
    {
      target.style.display = 'block';
      this.innerHTML='-';
    }">-</button>
    </label>
    <?php
        }

        ?>
    </legend>
    <?php

        if ( $arr )
        {

            ?>
    <pre id="<?=$temp_name?>" class="m_pre"><?php
            if ( 0 == $T )
            {
                print_r ( $arr );
            }
            else
            {
                var_export ( $arr );
            }

            ?>
    </pre>
    <?php
        }

        ?>
    </fieldset>
    <?php
    }

?>
