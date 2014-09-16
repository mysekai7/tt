<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Last-Modified: '.gmdate('D, d M Y H:i:s'). ' GMT');
header('Content-type:text/html; charset=utf-8');

//include
require_once('../../global.php');
require_once('../mysql.config.php');
require_once('user.func.php');
require_once('operate_points_function.php');

/*
前端状态值意思
0正常
>0都是不正常的
*/

//判断是否为ajax请求

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    echo '2#####This is not an AJAX request E.g. display normal page content.';
    exit;
}
//
$action = isset($_POST['do']) ? $_POST['do'] : '';
$userdb = fetchUserInfo();
$uid = $userdb['u_id'];

//零点时间戳
$time = getdate(time());
$zero_timestamp = mktime(0, 0, 30, $time['mon'], $time['mday'], $time['year']);

//开始游戏
if($action == 'begin')
{
    //统一检查
    unify_check();

    $sql = "select * from dig_gold_userip where userid='{$uid}'";
    $result = $DB->fetch_arrays($sql);
	$last_play = $result[0]['time'] && isset($result[0]['time']) ? $result[0]['time'] : 0;

    //每日赠送积分最大限额
    if(stat_points() > 20000 && $last_play < $zero_timestamp)
    {
        echo "2#####今日挖金子游戏已达上限\n请明日再进行游戏";
        exit;
    }

    $words = array();
    $ip = get_ip();
    $time = time();
    if($result == null)//如果玩家是首次登陆
    {
        if(exists_ip())
        {
            echo "3#####对不起，你使用的IP地址已有帐户存在!\n您不能进行游戏";
            exit;
        }
        $data_tmp = get_rand_words();
        $words = $data_tmp['word'];
        $total_points = $data_tmp['total_points'];
        $username = $userdb['user_name'];

        //初始化新的玩家信息
        $userinfo = serialize($words);
        $sql = "insert dig_gold_userip (state, ip, userid, time, userinfo, points, visitnum, username, allpoints) values ('$total_points', '$ip', '$uid', '$time', '$userinfo', '0', '1', '$username', '0')";
        $DB->query($sql);
    }
    else
    {
        //玩家信息已存在，开始游戏
        $lasttime = $result[0]['time'];
        $userinfo = $result[0]['userinfo'];
        $words = unserialize($result[0]['userinfo']);
        
        //sql (IP 时间 访问次数更新)
        $sql = "update dig_gold_userip set ip='{$ip}', time='{$time}', visitnum=visitnum+1 where userid='{$uid}'";

        //20分钟内 点击开始游戏不更新访问次数
        if((time() - $lasttime) < 1200)
        {
            $sql = "update dig_gold_userip set ip='{$ip}', time='{$time}' where userid='{$uid}'";
        }
        //时间超过一天的
        else if(check_date($lasttime))
        {
            //获得新词 (老用户了)
            $data_tmp = get_rand_words( $result[0]['allpoints'] );
            $words = $data_tmp['word'];
            $total_points = $data_tmp['total_points'];//分配的金子总数

            $userinfo = serialize($words);
            $sql = "update dig_gold_userip set state='{$total_points}', ip='{$ip}', time='{$time}', userinfo='{$userinfo}', visitnum=visitnum+1 where userid='{$uid}'";
        }

        //跟新玩家信息
        $DB->query($sql);
    }
    
	$return = array();
	foreach($words as $key => $val)
	{
		$id = base64_encode($key);
		$return[$id]['word'] = $val['word'];
		$return[$id]['dig'] = $val['dig'];
	}

    echo '0#####'.json_encode($return);
}


if($action == 'dig')
{
    global $DB;

    //统一验证
    unify_check();
	
    if(isset($_POST['tag']) && !empty($_POST['tag']))
    {
		$wid = intval(base64_decode( urldecode($_POST['tag']) ));
		if($wid == 0)
		{
			echo '1#####词异常错误2';
			exit;
		}
    }
	else
	{
        echo '1#####词异常错误2';
        exit;
	}
    

    $sql = "select userinfo from dig_gold_userip where userid='{$uid}'";
    $rs = $DB->fetch_arrays($sql);
    $userinfo = unserialize( $rs[0][0] );

    if(isset($userinfo[$wid]) && $userinfo[$wid]['dig'] == 0)
    {
        $userinfo[$wid]['dig'] = 1;
        $upoints = $userinfo[$wid]['points'];
    }

    $userinfo = serialize($userinfo);
    $sql = "update dig_gold_userip set userinfo='{$userinfo}' where userid='{$uid}'";

    //如果挖到金子
    if($upoints > 0 && $upoints <=10)
    {
        $sql = "update dig_gold_userip set userinfo='{$userinfo}', points=points+'$upoints', allpoints=allpoints+'$upoints' where userid='{$uid}'";
    }
    $DB->query($sql);

    if($upoints > 0 && $upoints <=10)
    {
        echo "0#####恭喜您获得积分 {$upoints} 枚";
        exit;
    }
    else
    {
        echo 'no';
    }
}

if($action == 'active_rank')
{
    $sql = "select username, visitnum from dig_gold_userip order by visitnum desc, time desc limit 10";
    $result = $DB->fetch_arrays($sql);
    //var_dump($result);
    echo json_encode($result);
}

if($action == 'all_rank')
{
    $sql = "select username, allpoints from dig_gold_userip order by allpoints desc limit 10";
    $result = $DB->fetch_arrays($sql);
    echo json_encode($result);
}

if($action == 'myinfo')
{
    $userdb = fetchUserInfo();
    $uid = $userdb['u_id'];
	if(!$uid)
		return false;
    $sql = "select username, points, time, points, visitnum, allpoints from dig_gold_userip where userid='{$uid}' limit 1";
    $result = $DB->fetch_arrays($sql);
    echo json_encode($result[0]);
}

if($action == 'charge_mytootoo')
{
    //统一验证
	//检查用户是否已登录
	if( !checkUserState() )
	{
		echo '1#####请您先用mytootoo帐号登录';
		exit;
	}
    $userdb = fetchUserInfo();
	if(!$userdb['u_id'])
    {
        echo "2#####帐号异常";
        exit;
    }

    $sql = "select points from dig_gold_userip where userid='{$uid}' limit 1";
    $result = $DB->fetch_arrays($sql);
    $points = $result[0][0];
    
    if($points > 0)
    {
        $sql = "update dig_gold_userip set points=0 where userid='{$uid}'";
        if($DB->query($sql))
        {
            operate_points_by_uid($uid ,$points,1,'挖金子'); 
            echo '0#####金子已充入您的mytootoo帐户!';
            exit;
        }
    }
    //echo '2';
}

//-------------------
//统一控制
function unify_check()
{

	//检查用户是否已登录
	if( !checkUserState() )
	{
		echo '1#####请您先用mytootoo帐号登录';
		exit;
	}
    $userdb = fetchUserInfo();
	if(!$userdb['u_id'])
    {
        echo "2#####帐号异常";
        exit;
    }

	$end_time = mktime(0, 0, 30, 9, 18, 2010);
	if(time() > $end_time)
	{
        echo "2#####挖金子送积分活动已暂停...!\n请你继续关注沱沱网其他活动!";
        exit;
	}

    //仅限付费用户使用
	/*
    if($userdb['member_level']==0)
    {
        $ip = get_ip();
        if($ip == '114.251.133.189')
        {
            return;
        }
        else
        {
            echo "2#####游戏内测阶段仅限付费用户参与!\n等待正式开放请注意近期Tootoo,EDM邮件";
            exit;
        }
    }
	*/
}

//是否为同一IP
function exists_ip()
{
    global $DB;
    $ip = get_ip();
    if($ip == '114.251.133.189')
    {
        return false;
    }
    $sql = "select ip from dig_gold_userip where ip='{$ip}' limit 1";
    $rs = $DB->fetch_arrays($sql);
    if(count($rs) > 0)
        return true;
    return false;
}


//统计每日的出金数
function stat_points()
{
    global $DB, $zero_timestamp;
    $sql = "select sum(state) from dig_gold_userip where time > '$zero_timestamp'";
    $rs = $DB->fetch_arrays($sql);
    $total_points = $rs[0][0];
    return $total_points;
}

//检查词是否过期
function check_date($logintime)
{
	global $zero_timestamp;
	if($zero_timestamp > $logintime)
	{
		return true;
	}
	return false;
}

//出积分分值, 分值越大概率越小
function getRand($n)
{
    $max = $n + 1;
    $bigend = ((1+$max)*$max)/2;
    $rand = rand();
    $x = abs(intval($rand)%$bigend);
    $sum = 0;
    for($i = 1; $i<$max; $i++)
    {
        $sum += ($max - $i);
        if($sum > $x) {
            return $i;
        }
    }
    return 1;
}

function get_rand_words($level=0)
{
    global $DB;

    $words = array();

    //次数
    $gold_total = rand(3, 7);

	$bigend = 5;
	if($level >= 200) {
		$bigend = 1;
	} else if($level >= 100) {
		$bigend = 2;
	} else if($level >= 50) {
		$bigend = 3;
	} else if($level >= 30) {
		$bigend = 4;
	}
    
    //金币点数值大小
    $gold_points = array();
    for($i=0; $i<$gold_total; $i++)
    {
        //$gold_points[$i] = $u == true ? getRand(5) : rand(1, 5);
		$gold_points[$i] = rand(1, $bigend);
    }
    //所有金子总和
    $total_points1 = array_sum($gold_points);
    
    //找几个有points的词
    $total_points=0;
    foreach($gold_points as $key => $val)
    {

        $sql = "select * from dig_gold_words where points={$val} order by rand() limit 1";
        $rs = $DB->fetch_arrays($sql);
        if($rs)
        {
			$id = $rs[0]['id'];
			$words[$id]['word'] = dealwith_kw($rs[0]['word']);
            $words[$id]['points'] = $rs[0]['points'];
            $words[$id]['dig'] = 0;
            $total_points +=$rs[0]['points'];
        }
    }
   
    //找100-$gold_total 个 points=0的词
    
    $sql = "select count(id) from dig_gold_words where points='0'";
    $rs = $DB->fetch_arrays($sql);

    //从表1中取点
    $limit = 100 - $gold_total - 20;
    $max = $rs[0][0] - 300;
    $min = 500;
    $begin = rand($min, $max);
    $sql = "select * from dig_gold_words where points='0' limit $begin, $limit";
    $rs = $DB->fetch_arrays($sql);
    
    if($rs && is_array($rs) && count($rs)>0)
    {
        foreach($rs as $key => $val)
        {
			$words[$val['id']]['word'] = dealwith_kw($val['word']);
            $words[$val['id']]['points'] = 0;
            $words[$val['id']]['dig'] = 0;
        }
    }

    //从表2里去点
    $begin = rand(1, 480);
    $sql = "select * from dig_gold_words_2 limit {$begin}, 20";
    $rs = $DB->fetch_arrays($sql);
    if($rs && is_array($rs) && count($rs)>0)
    {
        foreach($rs as $key => $val)
        {
			$words[$val['id']]['word'] = dealwith_kw($val['word']);
            $words[$val['id']]['points'] = 0;
            $words[$val['id']]['dig'] = 0;
        }
    }

    return array(
        'word' => array_shuffle($words),
        'total_points' => $total_points
    );
}

function array_shuffle($arr)
{
    $arr_new = array();
    $tmp = array_rand($arr, count($arr));
    shuffle($tmp);
    foreach($tmp as $val){
        $arr_new[$val] = $arr[$val];
    }
    return $arr_new;
}


function dealwith_kw($kw)
{
    $kw = preg_replace('|([^A-Za-z0-9.])+|', " ", strtolower($kw));
    $kw = str_replace(array('. ', ' .'), ' ', $kw);
    $kw = trim(trim($kw), '.');
    return $kw;
}

// 获得IP地址
function get_ip()
{
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}

?>