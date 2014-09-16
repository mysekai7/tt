/*
 * FY Library 2.0
 * Copyright(c) 2010 FengYin
 * Blog: http://fengyin.name/
 * Date: 2010-3-5 14:00:05
 * Update: http://code.google.com/p/fyjs/
*/
(function(){
    var _FY=this;
    window._FY=window.$=function(i){
        return new FY(i)
    };

    var FY=function(i){
        this.id=document.getElementById(i)
    };

    FY.prototype={
        ready:function(f){
            var l=window.onload;
            if(typeof(l)=="function"){
                window.onload=function(){
                    l();
                    f()
                }
            }else{
                window.onload=f
            }
        },
        html:function(c){
            if(c){
                this.id.innerHTML="";
                this.id.innerHTML+=c;
                return this
            }else{
                return this.id.innerHTML
            }
        },
        val:function(c){
            if(c){
                this.id.value="";
                this.id.value+=c;
                return this
            }else{
                return this.id.value
            }
        },
        text:function(c){
            if(this.id.innerText){
                if(c){
                    this.id.innerText="";
                    this.id.innerText+=c;
                    return this
                }else{
                    return this.id.innerText
                }
            }else{
                if(c){
                    this.id.textContent="";
                    this.id.textContent+=c;
                    return this
                }else{
                    return this.id.textContent
                }
            }
        },
        show:function(f){
            this.id.style.display="block";
            f?f():null
        },
        hide:function(f){
            this.id.style.display="none";
            f?f():null
        },
        click:function(f){
            this.id.attachEvent?this.id.attachEvent("onclick",f):this.id.addEventListener("click",f,false)
        },
        setcookie:function(s){
            var expDays=s.expires*24*60*60*1000;
            var expDate=new Date();
            expDate.setTime(expDate.getTime()+expDays);
            var expString=s.expires?"; expires="+expDate.toGMTString():"";
            var pathString="; path="+(s.path||"/");
            var domain=s.domain?"; domain="+s.domain:"";
            document.cookie=s.name+"="+escape(s.value)+expString+s.domain+pathString+(s.secure?"; secure":"")
        },
        getcookie:function(g){
            var a="; "+document.cookie+"; ";
            var b=a.indexOf("; "+g+"=");
            if(b!=-1){
                var s=a.substring(b+g.length+3,a.length);
                return unescape(s.substring(0,s.indexOf("; ")))
            }else{
                return this
            }
        },
        ajax:function(s){
            var a=window.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):new XMLHttpRequest();
            with(a){
                open("POST",s.url,true);
                setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
                send(s.data);
                onreadystatechange=function(){
                    if(readyState==4&&status==200){
                        s.success(responseText)
                    }
                }
                }
        }
    }
})();