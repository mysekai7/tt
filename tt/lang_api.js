


var TTJS = TTJS || {};
TTJS.lang = function() {
    var srcLang, destLang, results=[], flag=1, q=[], http_request, params=[];

    var walk_the_DOM = function walk(node, func) {
        func(node);
        node = node.firstChild;
        //alert(node.nodeName);
        while (node) {
            walk(node, func);
            node = node.nextSibling;
        }
    };

    var getElementsByAttribute = function (att, value) {
        walk_the_DOM(document.body, function (node) {

            var actual=null; if(node.nodeType === 1){ actual = node.getAttribute(att);}
            if((typeof actual === 'string') && (actual === value || typeof value !== 'string'))
            {
                results.push(node);
                //if(node.nodeType === 3){
                    //results.push(node)
                //}
            }
        });
        return results;
    };

    var init_ajax = function () {
        try { http_request = new XMLHttpRequest(); }
        catch(e) {
            try { http_request = new ActiveXObject("Microsoft.XMLHTTP"); }
            catch(e) {
                try { http_request = new ActiveXObject('Msxml2.XMLHTTP'); }
                catch(e) {}
            }
        }
    }

    var translate = function (lang) {
        init_ajax();
        //results = results != null ? results : getElementsByAttribute('lang','lang');
        results = getElementsByAttribute('lang','lang');

        var s='';
        for(key in results)
        {
            s += key+':  '+results[key]+"\n";
        }
        //alert(s);


        for(var i=0, j=results.length; i<j; i++)
        {
            flag++;

            q[i] = 'q=' + encodeURIComponent(results[i].innerHTML);

            if(flag>2)
                break;
            if(flag == 2 || ((j-i) <= 2))
            {
                flag = 1;

                params[1] = 'v=1.0';
                params[2] = q.join('&');
                params[3] = 'langpair=en|zh-CN';
                params[4] = 'format=html';



                if(!http_request) return false;

                http_request.onreadystatechange=function(){
                    if(http_request.readyState==4){
                        if(http_request.status==200){
                            alert(http_request.responseText);
                            var rs = eval(http_request.responseText);
                            var s='';
                            for(key in rs)
                            {
                                s += key+':  '+rs[key]+"\n";
                            }
                            alert(s);
                        }
                    }
                };
                var url = 'http://ajax.googleapis.com/ajax/services/language/translate?'+params.join('&')+'callback=call';
                //alert(url);
                http_request.open("GET",url,true);
                //http_request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                //http_request.setRequestHeader("If-Modified-Since",0);
                http_request.send(null);
            }
        }

    }

    var callback = function(e)
    {
        //alert(e);
        alert('1111');
    }

    return{
        tran:translate,
        callback:callback
    };
}();

function call()
{
    alert('111111111');
}