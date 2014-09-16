var DigGold = (function(){
    var words_status=[], kw, plist, timer, result, url='diggold.php';

    var get_userinfo = function ()
    {
		var http_request = init_ajax();
        var data = 'do=myinfo'+'&'+new Date().getTime();
        http_request.onreadystatechange=function(){
            if(http_request.readyState==4){
                if(http_request.status==200){
                    var msg = http_request.responseText;
                    if(msg)
                    {
                        var tmp_arr = eval("("+msg+")");
                        var s = '您好 <b>'+tmp_arr.username+'</b><br>您挖到的积分数：<b>'+tmp_arr.points+'</b>';
                        if(tmp_arr.points > 0)
                        {
                            s += ' <img id="charge" title="将积分充入mytootoo帐户" alt="将积分充入mytootoo帐户" onclick="DigGold.charge()" src="charge.png" />';
                        }
                        $('userinfo').innerHTML = s;

                        var s1 = '<div><h3>玩家信息</h3>';
                        s1 += '<p>';
                        s1 += '您好：'+ tmp_arr.username +'<br/>';
                        s1 += '您这是第'+ tmp_arr.visitnum +'次参与游戏<br/>';
                        s1 += '累计挖到的积分：'+ tmp_arr.allpoints +'<br/>';
                        var charged_points = (tmp_arr.allpoints - tmp_arr.points) > 0 ? (tmp_arr.allpoints - tmp_arr.points) : 0;
                        s1 += '已充入tootoo帐户积分：'+ charged_points +'<br/>';
                        s1 += '</p></div>';
                        $('userinfo_r').innerHTML = s1;
                    }
                }
            }
        };
        http_request.open("POST",url,true);
        http_request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
        http_request.setRequestHeader("If-Modified-Since",0);
        http_request.send(data);
    }

    var get_active = function ()
    {
		var http_request = init_ajax();
        var data = 'do=active_rank'+'&'+new Date().getTime();
        http_request.onreadystatechange=function(){
            if(http_request.readyState==4){
                if(http_request.status==200){
                    var msg = http_request.responseText;
                    var tmp_arr = eval("("+msg+")");
                    var s='';
                    for(key in tmp_arr)
                    {
                        //s += key+':  '+tmp_arr[key]+"\n";
                        s += '<li>玩家: <b class="name">'+tmp_arr[key].username+'</b><br />开采次数: <b>'+tmp_arr[key].visitnum+'</b></li>'
                    }
                    $('weekrank').innerHTML = s;
                }
            }
        };
        http_request.open("POST",url,true);
        http_request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
        http_request.setRequestHeader("If-Modified-Since",0);
        http_request.send(data);
    }

    var get_all = function ()
    {
        var http_request = init_ajax();
        var data = 'do=all_rank'+'&'+new Date().getTime();
        http_request.onreadystatechange=function(){
            if(http_request.readyState==4){
                if(http_request.status==200){
                    var msg = http_request.responseText;
                    var tmp_arr = eval("("+msg+")");
                    var s='';
                    for(key in tmp_arr)
                    {
                        //s += key+':  '+tmp_arr[key]+"\n";
                        s += '<li>玩家: <b class="name">'+tmp_arr[key].username+'</b><br />积分总数: <b>'+ tmp_arr[key].allpoints +'</b></li>'
                    }
                    $('allrank').innerHTML = s;
                }
            }
        };
        http_request.open("POST",url,true);
        http_request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
        http_request.setRequestHeader("If-Modified-Since",0);
        http_request.send(data);
    }

    //开始游戏
    var begin = function () {
        var http_request = init_ajax();
        if(!http_request)
        {
            alert('创建ajax失败,游戏无法进行');
            return false;
        }

        var data = 'do=begin'+'&'+new Date().getTime();
        http_request.open("POST",url,false);
        http_request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
        http_request.setRequestHeader("If-Modified-Since",0);
        http_request.send(data);
        var msg = http_request.responseText;
        var data_tmp = msg.split('#####');

        if(data_tmp[0] > 0)
        {
            alert(data_tmp[1]);
            if(data_tmp[0] == 1)
            {
                document.location='http://www.tootoo.com/user/login.html?ru='+location.href;
            }
            return false;
        }

        //获得词该用户词信息
        var tmp_arr = eval("("+data_tmp[1]+")");
        words_status = tmp_arr;


        var s='';
        for(key in tmp_arr)
        {
            //s += key+':  '+tmp_arr[key]+"\n";

            var num = rand(0, 10);
            if(tmp_arr[key].dig==1)
            {
                s += '<a hidefocus="true" id="'+key+'" class="null cloud_'+num+'" href="javascript:void(0);">'+tmp_arr[key].word+'</a> ';
            }
            else
            {
                s += '<a hidefocus="true" id="'+key+'" class="cloud_'+num+'" href="javascript:void(0);">'+tmp_arr[key].word+'</a> ';
            }

        }
        $('tagcloud').innerHTML = s;
        $('gameRules').style.display = 'none';
        $('tagcloud').style.display = 'block';

        //统计挖金子次数
        check_remain();

		(function(){
			var nodes = $('tagcloud').getElementsByTagName('a');
			for (var i=0; i<nodes.length; i++) {
				nodes[i].onclick = function ()
				{
					dig(this, this.id);
				}
			}
		})();

        if($('userinfo').innerHTML == '')
        {
            get_userinfo();
        }
    }

    var dig = function (obj, id)
    {
        var http_request = init_ajax();

        kw = obj.firstChild.nodeValue;
        if(words_status[id].dig == 1)
        {
            Dialog.error('抱歉，这里已被挖过，请试试其他地方!');
            return false;
        }

        words_status[id].dig = 1;
        obj.className += ' null';

        /**
        ####更新玩家词的状态信息####
        **/
        clearTimeout(timer);
        timer = setTimeout(function(){
            var data = 'do=dig&tag='+encodeURIComponent(id)+'&'+new Date().getTime();
            http_request.onreadystatechange=function(){
                if(http_request.readyState==4){
                    if(http_request.status==200){
                        var msg = http_request.responseText;
                        if(typeof msg == 'string' && msg != 'no')
                        {
                            var data_tmp = msg.split('#####');

                            if(data_tmp[0] > 0)
                            {
                                //alert(data_tmp[1]);
                                Dialog.error(data_tmp[1]);
                                return false;
                            }
                            else
                            {
                                //alert(data_tmp[1]);
                                Dialog.success(data_tmp[1]);
                                setTimeout(init, 3000);
                            }
                        }
                    }
                }
            };

            http_request.open("POST",url,true);
            http_request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
            http_request.setRequestHeader("If-Modified-Since",0);
            http_request.send(data);
        }, 300);


        //加载列表页
        flush_list();

        setTimeout(close_load, 3000);

        setTimeout(check_remain, 3000);

    }

    //计算剩余词
    var check_remain = function ()
    {
        var total=0;
        for(key in words_status)
        {
            if(words_status[key].dig == 0)
            {
                total++;
            }
        }
        if(total == 0)
        {
            $('remain').innerHTML = '<b>今天的开采任务已结束，明天继续!</b>';
        }
        else
        {
            $('remain').innerHTML = '您还剩下 <b>'+total+'</b> 次挖金子的机会';
        }
    }

    var charge_mytootoo = function ()
    {
		var http_request = init_ajax();
        var data = 'do=charge_mytootoo'+'&'+new Date().getTime();
        http_request.onreadystatechange=function(){
            if(http_request.readyState==4){
                if(http_request.status==200){
                    var msg = http_request.responseText;
                    var data_tmp = msg.split('#####');

                    if(data_tmp[0]>0)
                    {
                        alert(data_tmp[1]);
                        return false;
                    }
                    else
                    {
                        alert(data_tmp[1]);
                        init();
                    }
                }
            }
        };
        http_request.open("POST",url,true);
        http_request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
        http_request.setRequestHeader("If-Modified-Since",0);
        http_request.send(data);
    }

    var flush_list = function ()
    {
        //打开loading页面
        open_load();

        //关键词处理这里
        var src_url = 'http://www.tootoo.com/buy-'+kw.replace(/ /g, '_')+'/';
        op(src_url);
    }

    var open_load = function ()
    {
        $('loading').style.display='block';
    }

    var close_load = function ()
    {
        $('loading').style.display='none';
    }

    var rand = function (under, over){
        switch(arguments.length){
            case 1:
                return parseInt(Math.random()*under+1);
            case 2:
                return parseInt(Math.random()*(over-under+1)+under);
            default:
                return 0;
        }
    }

    var $ = function (objectId)
    {
        if( document.getElementById && document.getElementById(objectId) )
        {
            return document.getElementById(objectId);
        } else if( document.all && document.all[objectId] ) {
            return document.all[objectId];
        } else if( document.layers && document.layers[objectId] ) {
            return document.layers[objectId];
        } else {
            return false;
        }
    }

    var init_ajax = function () {
        if(window.ActiveXObject){
            try{
                http_request=new ActiveXObject("Msxml2.XMLHTTP");
            }catch(execption){
                try{
                    http_request=new ActiveXObject("Microsoft.XMLHTTP");
                }catch(execption){
                    http_reqeust=false;
                }
            }
        }else{
            http_request=new XMLHttpRequest();
            if(http_request.overrideMimeType){
                http_request.overrideMimeType="text/xml";
            }
        }
        return http_request;
    }

    //初始化词的信息
    var init = function () {
        get_active();
		get_all();
        get_userinfo();
    }

    return {
        init:init,
        dig:dig,
        begin:begin,
        charge:charge_mytootoo
    };
})();

var splashWin;
function unload(url)
{
    var popUpSizeX=11;
    var popUpSizeY=11;

    var popUpLocationX=2;
    var popUpLocationY=2;


    var popUpURL=url;
    splashWin = window.open(popUpURL,'','fullscreen=0,height=100, width=100, left='+Math.round(window.screen.height+500)+', top='+Math.round(window.screen.width+500)+', toolbar=no, menubar=no, scrollbars=no, resizable=0,location=0, status=0');
    splashWin.blur();
    window.focus();

}

function op(url)
{
        unload(url);
        setTimeout("co();",2500);
}
function co()
{
        splashWin.close();
}


var Dialog = function()
{
    var _pt2;
    var _h;
    var _w;
    var t;
    var _pt1;
    var is_vaild=false;

    function show(msg, type) {
        var box_w = _w = 400;
        var box_h = _h = 200;
        var win_wh = getWinSize();
        is_vaild = true;
        var bg = type!=1 ? '#E33100' : '#C3D3F7';
        var bord = type!=1 ? '#E33100' : '#4D7BE0';

    if(!_pt1)
    {
        var m = document.createElement('div');
        m.id ='diaslog_box_lay';
        m.style.position = 'absolute';
        m.style.left = '0';
        m.style.top = '0';
        m.style.zIndex = '1000';
        m.style.background = '#555';
        m.style.filter = 'alpha(opacity=50)';
        m.style.opacity = '0.5';
        m.style.width = document.body.scrollWidth +'px';
        m.style.height = document.body.scrollHeight +'px';
        document.getElementsByTagName('body')[0].appendChild(m);
        _pt1 = m;
    }
    _pt1.style.display = 'block';



    var n = document.createElement('div');
    n.id = 'dialog_box';
    n.className = 'drag';
    n.style.position = 'absolute';
    n.style.zIndex = '1001';
    n.style.left = win_wh[0] - box_w/2 +'px';
    n.style.top = win_wh[1] - box_h/2 +'px';
    n.style.width = box_w + 'px';
    n.style.height = box_h + 'px';

    var n1 = document.createElement('div');
    n1.style.position = 'absolute';
    n1.style.background = bg;
    n1.style.filter = 'alpha(opacity=40)';
    n1.style.opacity = '0.4';
    n1.style.left = '0';
    n1.style.top = '0';
    n1.style.width = box_w + 'px';
    n1.style.height = box_h + 'px';
    n.appendChild(n1);

    var n2 = document.createElement('div');
    n2.style.position = 'absolute';
    n2.style.background = '#FFF';
    n2.style.left = '8px';
    n2.style.top = '8px';
    n2.style.width = (box_w - 18) + 'px';
    n2.style.height = (box_h - 18) + 'px';
    n2.style.border = '1px solid '+bord;
    n2.innerHTML = '<p style="font:bold 12px/1 Verdana,arial,sans-serif; text-align:right;"><a style="color:#000;" href="javascript:void(0)" onclick="Dialog.cancel()">关闭</a></p><div style="padding-top:50px; text-align:center;"><p><b style="font-size:14px;">'+msg+'</b></p></div>';
    n.appendChild(n2);
    _pt2 = n;

    document.getElementsByTagName('body')[0].appendChild(n);

    if(window.addEventListener){
        window.addEventListener('scroll',_sc,false);
        window.addEventListener('resize',_sc,false);
    }else if(window.attachEvent){
        window.attachEvent('onscroll',_sc);
        window.attachEvent('onresize',_sc);
    }
    }


    function cancel()
    {
    is_vaild = false;
    _pt1.style.display = 'none';
    document.getElementsByTagName('body')[0].removeChild(_pt2);
    }


    function _sc() {

    if(!is_vaild) return;
        try {
            _pt2.style.top = (window.innerHeight / 2 + pageYOffset) - _h / 2 + "px";
            _pt2.style.left = (window.innerWidth / 2 + pageXOffset) - _w / 2 + "px";
        } catch(e) {
            var _docElement = document.documentElement;
            if (!document.body.scrollTop) {
                _pt2.style.top = (_docElement.offsetHeight / 2 + _docElement.scrollTop) - _h / 2 + "px";
                _pt2.style.left = (_docElement.offsetWidth / 2 + _docElement.scrollLeft) - _w / 2 + "px";
            } else {
                _pt2.style.top = (_docElement.offsetHeight / 2 + document.body.scrollTop) - _h / 2 + "px";
                _pt2.style.left = (_docElement.offsetWidth / 2 + document.body.scrollLeft) - _w / 2 + "px";
            }
        }
        setTimeout(_sc, 500);
    }

    function addEvent (obj, eventType, cfn) {
        if (obj.addEventListener) {
            obj.addEventListener(eventType, cfn, false);
        } else if ( obj.attachEvent ) {
            obj.attachEvent("on" + eventType, cfn);
        }
    }

     function getWinSize() {
        var left = 0, top = 0;

        try {
            top = window.innerHeight / 2 + pageYOffset;
            left = window.innerWidth / 2 + pageXOffset;
        } catch(e) {
            var _docElement = document.documentElement;
            if (!document.body.scrollTop) {
                top = _docElement.offsetHeight / 2 + _docElement.scrollTop;
                left = _docElement.offsetWidth / 2 + _docElement.scrollLeft;
            } else {
                top = _docElement.offsetHeight / 2 + document.body.scrollTop;
                left = _docElement.offsetWidth / 2 + document.body.scrollLeft;
            }
        }

        return [left, top];
    }

    function error(msg)
    {
        show(msg, 0);
    }
    function success(msg)
    {
        show(msg, 1);
    }


    return {
        error:error,
        success:success,
        cancel:cancel
    };
}();
