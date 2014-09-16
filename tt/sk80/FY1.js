/*
 * FY Library 1.0.1
 * Copyright(c) 2010 FengYin
 * Blog: http://fengyin.name/
 * Date: 2010-3-11 11:16:34
 * Update: http://code.google.com/p/fyjs/
*/
(function(_w,_d){
    var _FY,$,FY;
    _w._FY=_w.$=function(i){
        return new FY(i)
        };

    FY=function(i){
        _=this.id=_d.getElementById(i)
        };

    FY.prototype={
        ready:function(f){
            var o="onload",l=_w[o];
            typeof(l)=="function"?_w[o]=function(){
                l();
                f()
                }:_w[o]=f
            },
        html:function(c){
            var r="innerHTML";
            return c?_[r]=c:_[r]
            },
        val:function(c){
            var r="value";
            return c?_[r]=c:_[r]
            },
        text:function(c){
            var r="innerText" in _?"innerText":"textContent";
            return c?_[r]=c:_[r]
            },
        show:function(f){
            _.style.display="block";
            return f?f():null
            },
        hide:function(f){
            _.style.display="none";
            return f?f():null
            },
        bind:function(t,f){
            var e=_.attachEvent;
            e?_.attachEvent("on"+t,f):_.addEventListener(t,f,false)
            },
        setcookie:function(s){
            var p=new Date();
            p.setTime(p.getTime()+(s.expires||24)*60*60*1000);
            _d.cookie=s.name+"="+escape(s.value)+";expires="+p.toGMTString()+";path=/"
            },
        getcookie:function(n){
            var c=_d.cookie.match(new RegExp("(^| )"+n+"=([^;]*)(;|$)"));
            return c?unescape(c[2]):null
            },
        ajax:function(s){
            var a=_w.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):new XMLHttpRequest();
            with(a){
                open("POST",s.url,true);
                setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
                send(s.data);
                onreadystatechange=function(){
                    readyState==4&&status==200?s.success(responseText):null
                    }
                }
        }
    }
})(window,document);