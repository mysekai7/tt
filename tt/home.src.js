function $(objectId)
{
    if (document.getElementById && document.getElementById(objectId))
    {
        return document.getElementById(objectId);
    }else if (document.all && document.all[objectId])
    {
        return document.all[objectId];
    }else if (document.layers && document.layers[objectId])
    {
        return document.layers[objectId];
    }else{
        return false;
    }
}

function c(q,type)
{
    var img=window["BD_PS_C"+(new Date()).getTime()]=new Image();
    img.src="/tt?id="+q+"&type="+type;
    return true;
}

function get_rand()
{
    return Math.random();
}

function toggle(id) {
    var obj = document.getElementById(id);
    if(obj.style.display == 'none'){
        obj.style.display = 'block';
    } else {
        obj.style.display = 'none';
    }
}

function select_src(obj, src)
{
    var url;
    var replace;
    var parent = obj.parentNode;
    var li = parent.getElementsByTagName('li');
    for(var i=0; i<li.length; i++){
        li[i].className = '';
    }
    obj.className = 'on';
    $('src1').value=src;

    switch(src) {
        case 'post':
            replace = '+';
            url = 'http://post.tootoo.com/sell-';
            break;
        case 'inquire':
            replace = '-';
            url = '/inquire/';
            break;
        case 'company':
            replace = '_';
            url = '/company/';
            break;
        default:
            replace = '_';
            url = '/buy-';
            break;
    }

    var links = $('hot_search').getElementsByTagName('a');
    for(var j=0; j<links.length; j++){
        var word = links[j].firstChild.nodeValue;
        word = word.toLowerCase();
        word = StrCode(word.replace(/ /g, replace));
        links[j].setAttribute('href', url+word+'/');
    }

}
/* search */
function StrCode(str)
{
    if(encodeURIComponent)
    {
        return encodeURIComponent(str);
    }
    if(escape)
    {
        return escape(str);
    }
}

function set_kw(src, str)
{
    var tmp_str = '';
    switch(src) {
        case 'post':
            tmp_str = str.replace(/ /g,'+');
            break;
        case 'inquire':
            tmp_str = StrCode(str.replace(/ /g,'-'));
            break;
        default:
            tmp_str = StrCode(str.replace(/ /g,'_'));
            break;
    }
    tmp_str = tmp_str.replace(/%/g,"%25");
    return tmp_str;
}

function jsSubmit(src, kw, code)
{
    var kw_string = $(kw).value;
    kw_string = kw_string.replace(/(^\s*)|(\s*$)/g, "");

    if(kw_string.length == 0) {
        alert("Invalid input !");
        return false;
    }

    var static_url = set_kw($(src).value, kw_string);
    var seoUrl = 'http://www.tootoo.com/' ;



    if($(src).value == 'inquire') {
        seoUrl = 'http://www.tootoo.com/inquiresearch/inquirelist.html?kw='+static_url;
    } else if($(src).value == 'post') {
        seoUrl = 'http://post.tootoo.com/list_post.html?type=seller&pa='+static_url;
    } else {
        seoUrl += "list.html?kw=" + static_url + "&src="+ $(src).value;
    }

    if($(code)!=null && $(code).value != '')
    {
        seoUrl +="&code="+ $(code).value;
    }

    window.location.href = seoUrl;
    return true;
}

function search_submit()
{
    return jsSubmit('src1', 'kw1', 'code1');
}

/* suggest */
var http_request = null;

function loadajax(url){

    try { http_request = new XMLHttpRequest(); }
    catch(e) {
        try { http_request = new ActiveXObject("Microsoft.XMLHTTP"); }
        catch(e) {
            try { http_request = new ActiveXObject('Msxml2.XMLHTTP'); }
            catch(e) {}
        }
    }

    if(http_request){

        http_request.onreadystatechange=process;
        http_request.open("GET",url,true);
        http_request.setRequestHeader("If-Modified-Since",0);
        http_request.send(null);
    }
}


function ajax(url,data){

    try { http_request = new XMLHttpRequest(); }
    catch(e) {
        try { http_request = new ActiveXObject("Microsoft.XMLHTTP"); }
        catch(e) {
            try { http_request = new ActiveXObject('Msxml2.XMLHTTP'); }
            catch(e) {}
        }
    }

    if(http_request){
        http_request.onreadystatechange=process;
        http_request.open("POST",url,true);
        http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
        http_request.setRequestHeader("If-Modified-Since",0);
        http_request.send(data);
    }
}

var j=-1;
var temp_str;
var timer;

function ajax_keyword(obj){
    var kw = obj.value;
    var url = "http://www.tootoo.com/suggest_word.php?kw="+kw+'&'+new Date().getTime();
    if(kw != ""){
        loadajax(url);
    }
}

function keydowndeal(keyc, obj) {
    var kw = obj.id;
    var suggest = $('suggest');

    $('suggest_wrap').appendChild(suggest);

    var li = suggest.getElementsByTagName('li');

    if(keyc == 40 || keyc == 38){
        if(keyc == 40){

            if(j < li.length){
                j++;
                if(j >= li.length){
                    j=-1;
                }
            }
            if(j >= li.length){
                j=-1;
            }
        }
        if(keyc == 38){
            if(j >= 0){
                j--;
                if(j <= -1){
                    j = li.length;
                }
            } else {
                j = li.length-1;
            }
        }

        set_style(j);

        if(j >= 0 && j < li.length){
            obj.value = li[j].childNodes[0].nodeValue;
        }else{
            obj.value = temp_str;
        }
    }

    if(keyc == 13){
        search_submit();
        c('suggest', 'suggest');
    }
}

function mo(nodevalue){
    j=nodevalue;
    set_style(j);
}

function set_style(j){
    var suggest = $('suggest');
    var li = suggest.getElementsByTagName('li');

    for(var i=0; i< li.length; i++){
        var li_node = li[i];
        li_node.className="";
    }
    if(j >= 0 && j < li.length){
        var li_node = li[j];
        li[j].className="active";
    }
}

function process() {
    if(http_request.readyState==4){
        if(http_request.status==200){
            str=http_request.responseText;
            $("suggest").innerHTML=str;
        }
    }
}

function form_submit(obj) {
    var j = obj.value;
    var suggest = $('suggest');
    var li = suggest.getElementsByTagName('li');

    var parent_node = obj.parentNode.parentNode.parentNode;

    if(j >= 0 && j < li.length){
         $('kw1').value = li[j].childNodes[0].nodeValue;
    }else{
         $('kw1').value = temp_str;
    }

    search_submit();
    c('suggest', 'suggest');
}


function suggest_close(){
    $('suggest').style.display = 'none';
}

function keyupdeal(keyc, obj){
    if(keyc != 40 && keyc != 38 && keyc != 37 && keyc != 39)
    {
        excludeCn(obj);
        if(obj.value == ''){
            $('suggest').innerHTML = '';
        }
        listener = function() {
            ajax_keyword(obj);
        }
        clearTimeout(timer);
        timer = setTimeout(listener, 500);
        temp_str = obj.value;
        j=-1;
        var suggest_list = $('suggest');
        suggest_list.style.display = 'block';
    }
}

function excludeCn(input)
{
    if(/[^\w\s\.\-\+\?\\\/\|\[\]\{\}\'\"\`\~\!\#\$\@\%\^\&\*\(\)\=\<\>\:\,;]/.test(input.value))
    {
        input.value=input.value.replace(/[^\w\s\.\-\+\?\\\/\|\[\]\{\}\'\"`~!@#$%^&*()=<>:,;]/g,'');
    }
}

document.onclick=function(){suggest_close();}

/*打印分类*/
function output_cats()
{
    var cats = [];
    cats['01000000'] = 'Agriculture';
    cats['02000000'] = 'Apparel & Fashion';
    cats['03000000'] = 'Automobile';
    cats['03000000'] = 'Automobile';
    cats['04000000'] = 'Business Services';
    cats['05000000'] = 'Chemicals';
    cats['06000000'] = 'Computer Hardware & Software';
    cats['07000000'] = 'Construction & Real Estate';
    cats['08000000'] = 'Electrical Equipment';
    cats['09000000'] = 'Electronic Components';
    cats['10000000'] = 'Energy';
    cats['11000000'] = 'Environment';
    cats['12000000'] = 'Excess Inventory';
    cats['13000000'] = 'Food & Beverage';
    cats['14000000'] = 'Furniture & Furnishings';
    cats['15000000'] = 'Gifts & Crafts';
    cats['16000000'] = 'Health & Beauty';
    cats['17000000'] = 'Home Appliances';
    cats['18000000'] = 'Home Supplies';
    cats['19000000'] = 'Industrial Supplies';
    cats['20000000'] = 'Lights & Lighting';
    cats['21000000'] = 'Luggage, Bags & Cases';
    cats['22000000'] = 'Minerals, Metals & Materials';
    cats['23000000'] = 'Office Supplies';
    cats['24000000'] = 'Packaging & Paper';
    cats['25000000'] = 'Printing & Publishing';
    cats['26000000'] = 'Security & Protection';
    cats['27000000'] = 'Sports & Entertainment';
    cats['28000000'] = 'Telecommunications';
    cats['29000000'] = 'Textiles & Leather Products';
    cats['30000000'] = 'Timepieces, Jewelry, Eyewear';
    cats['31000000'] = 'Toys';
    cats['32000000'] = 'Transportation';
    cats['99000000'] = 'Others';

    var code1 = $('code1');
    for(var key in cats){
        code1.options.add(new Option(cats[key], key));
    }
}

function slideLine(ul, delay, speed, lh) {
    var slideBox1 = $(ul);
    if(!slideBox1) return;
    var tid = null, pause = false;
    var start = function() {
        tid=setInterval(slide, speed);
    }
    var slide = function() {
        if (pause) return;
        slideBox1.scrollTop += 1.5;
        if (slideBox1.scrollTop % lh == 0) {
            clearInterval(tid);
            slideBox1.appendChild(slideBox1.getElementsByTagName('li')[0]);
            slideBox1.scrollTop = 0;
            setTimeout(start, delay);
        }
    }
    slideBox1.onmouseover=function(){pause=true;}
    slideBox1.onmouseout=function(){pause=false;}
    setTimeout(start, delay);
}

function leida()
{
    var text = $('userstatus').innerHTML;
    var ads = '<a class="tt" rel="nofollow" target="_blank" href="/user/chinasupplier.html">沱沱是什么?</a><a class="ld" rel="nofollow" target="_blank" href="http://rd.tootoo.com/">询盘雷达</a>';
    $('userstatus').innerHTML = ads+text;

}

/*自动加载*/
function auto_load()
{
    try{
        slideLine('scrollmessage1', 2000, 25, 15);
        output_cats('');
        leida();
    }catch(e){};
}

if (window.addEventListener)
	window.addEventListener("load", auto_load, false);
else if (window.attachEvent)
	window.attachEvent("onload", auto_load);
else
	window.onload = auto_load;