

var TTJS = TTJS || {};
TTJS.lang = function() {
    var results, src_lang, http_request, timer, url='lang_test.php';

    //遍历节点
    var walk_the_DOM = function walk(node, func) {
        func(node);
        node = node.firstChild;
        //alert(node.nodeName);
        while (node) {
            walk(node, func);
            node = node.nextSibling;
        }
    };

    //获得指定属性里的文本
    var getElementsByAttribute = function (att, value) {
        var results=[];

        walk_the_DOM(document.body, function (node) {

            var actual=null; if(node.nodeType === 1){ actual = node.getAttribute(att);}
            if((typeof actual === 'string') && (actual === value || typeof value !== 'string'))
            {
                //results.push(node);
                walk_the_DOM(node, function (node) {
                    if(node.nodeType === 3){
                        if(trim(node.data) != '')
                        {
                            results.push(node);
                        }
                    }
                });
            }
        });
        return results;
    };

    //删除左右两端的空格
    var trim = function (str) {
    　　return str.replace(/(^\s*)|(\s*$)/g, "");
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
    };

    var tanslate = function (lang) {
        if(!lang)
            return false;
        //alert(typeof(results));

        if(typeof results === 'undefined' && typeof src_lang === 'undefined')
        {
            src_lang = results = getElementsByAttribute('lang','lang');
        }
        if(typeof src_lang == 'object')
        {
            results = getElementsByAttribute('lang','lang');
        }

        var q=[];
        for(var i=0, j=src_lang.length; i<j; i++)
        {
            q[i] = i+'####'+src_lang[i].data;
        }

        init_ajax();
        if(!http_request) {
            return false;
        }

        clearTimeout(timer);
        timer = setTimeout(function(){
            var data = 'lang='+encodeURIComponent(lang)+'&data=' + encodeURIComponent(q.join('^^^^'));
            http_request.onreadystatechange=function(){
                //等待效果
                document.getElementById("loading").style.display = "block";
                if(http_request.readyState==4){
                    if(http_request.status==200){
                        document.getElementById("loading").style.display = "none";
                        var json = http_request.responseText;
                        //alert(json);
                        var tmp_arr = eval(json);

                        //var s='';
                        //for(key in tmp_arr)
                        {
                            //s += key+':  '+tmp_arr[key]+"\n";
                        }
                        //alert(s);

                        for(var k in results)
                        {
                            results[k].data = tmp_arr[k];
                        }
                    }
                }
            };

            http_request.open("POST",url,true);
            //http_request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=utf-8");
            http_request.setRequestHeader("If-Modified-Since",0);
            http_request.send(data);
        }, 300);
    };

    return {
        tran:tanslate
    };

}();