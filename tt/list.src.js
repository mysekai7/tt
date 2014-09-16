function $(objectId)
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

function c(q, type)
{
    var img = window["BD_PS_C"+(new Date()).getTime()]=new Image();
    img.src = "/tt?id="+ q +"&type="+ type;
    return true;
}

function get_rand()
{
    return Math.random();
}

function toggle(obj, id, num)
{
    var status = obj.firstChild.nodeValue;
    var li=document.getElementById(id).getElementsByTagName('li');
    var show_num = num != null ? num : 30;
    var show_txt;
    status = status.toLowerCase();
    if( status == '[+]more' )
    {
        for( var i=0; i<li.length; i++ )
        {
            li[i].style.display = 'block';
        }
        show_txt = '[-]less';
    }else{
        for( var i=show_num; i<li.length; i++)
        {
            li[i].style.display = 'none';
        }
        show_txt='[+]more';
    }
    obj.firstChild.nodeValue=show_txt;
}

function menu_src(src)
{
    if(src==null||src=='')return;
    var num;
    var src_name;
    switch(src){
        case 'product':
            num = 0;
            src_name = 'Products';
            break;
        case 'company':
            num = 1;
            src_name = 'Companies';
            break;
        case 'inquire':
            num = 2;
            src_name = 'Inquires';
            break;
    }
    var menu = $('byt');
    var btns = menu.getElementsByTagName('a');
    for(var i=0; i<btns.length; i++)
    {
        if(num == i){
            btns[i].className = 'on';
        }else{
            btns[i].className = null;
        }
    }
    $('src1').value = src;
    $('src2').value = src;
    $('src2_name').firstChild.nodeValue = src_name;
}

function toggle_src(obj, status)
{
    var dd = obj.getElementsByTagName('dd');
    if(status == 'open'){
        dd[0].style.display = 'block';
    }else{
        dd[0].style.display = 'none';
    }
}

function select_src(obj, src, type)
{
    var parent;
    if(type == 'top')
    {
        parent = obj.parentNode;
        var btns = parent.getElementsByTagName('a');
        for(var i=0; i<btns.length; i++)
        {
            btns[i].className = '';
        }
        obj.className = 'on';
        $('src1').value = src;
        search_top();
    }else{
        parent = obj.parentNode.parentNode;
        var span = parent.getElementsByTagName('span');
        span[0].firstChild.nodeValue = obj.firstChild.nodeValue;
        $('src2').value = src;
    }
}
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

function set_kw(src,str)
{
    var tmp_str = '';
    switch(src){
        case 'post':
            tmp_str=str.replace(/ /g, '+');
            break;
        case 'inquire':
            tmp_str=StrCode(str.replace(/ /g, '-'));
            break;
       default:
           tmp_str=StrCode(str.replace(/ /g, '_'));
           break;
    }
    tmp_str = tmp_str.replace(/%/g, "%25");
    return tmp_str;
}

function jsSubmit(src, kw, code)
{
    var kw_string = $(kw).value;
    kw_string = kw_string.replace(/(^\s*)|(\s*$)/g, "");
    if(kw_string.length == 0)
    {
        alert("Invalid input !");
        return false;
    }
    var static_url = set_kw($(src).value, kw_string);
    var seoUrl = 'http://www.tootoo.com/';
    if($(src).value == 'inquire'){
        seoUrl = 'http://www.tootoo.com/inquiresearch/inquirelist.html?kw='+ static_url;
    }else if($(src).value == 'post'){
        seoUrl = 'http://post.tootoo.com/list_post.html?type=seller&pa='+ static_url;
    }else{
        seoUrl += "list.html?kw="+ static_url +"&src="+ $(src).value;
    }
    if($(code) != null && $(code).value != '')
    {
        seoUrl += "&code=" + $(code).value;
    }
    window.location.href = seoUrl;
    return true;
}

function search_top()
{
    return jsSubmit('src1', 'kw1', 'code1');
}

function search_btm()
{
    return jsSubmit('src2', 'kw2', 'code2');
}

var http_request = null;
function loadajax(url)
{
    try {
        http_request = new XMLHttpRequest();
    }catch(e){
        try{
            http_request = new ActiveXObject("Microsoft.XMLHTTP");
        }catch(e){
            try{
                http_request = new ActiveXObject('Msxml2.XMLHTTP');
            }catch(e){}
        }
    }
    if(http_request)
    {
        http_request.onreadystatechange = process;
        http_request.open("GET",url,true);
        http_request.setRequestHeader("If-Modified-Since", 0);
        http_request.send(null);
    }
}

function ajax(url,data)
{
    try{
        http_request=new XMLHttpRequest();
    }catch(e){
        try{
            http_request=new ActiveXObject("Microsoft.XMLHTTP");
        }catch(e){
            try{
                http_request=new ActiveXObject('Msxml2.XMLHTTP');
            }catch(e){}
        }
    }
    if(http_request){
        http_request.onreadystatechange = process;
        http_request.open("POST", url, true);
        http_request.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
        http_request.setRequestHeader("If-Modified-Since",0);
        http_request.send(data);
    }
}
var j=-1;
var temp_str;
var timer;
function ajax_keyword(obj)
{
    var kw = obj.value;
    var url = "/suggest_word.php?kw="+ kw +'&' + new Date().getTime();
    if(kw != "")
    {
        loadajax(url);
    }
}

function keydowndeal(keyc, obj)
{
    var kw = obj.id;
    var suggest = $('suggest');
    if(kw == 'kw1'){
        $('suggest_top').appendChild(suggest);
    }else{
        $('suggest_btm').appendChild(suggest);
    }
    var li = suggest.getElementsByTagName('li');
    if(keyc==40 || keyc==38)
    {
        if(keyc == 40)
        {
            if(j < li.length)
            {
                j++;
                if(j >= li.length)
                {
                    j=-1;
                }
            }
            if(j >= li.length)
            {
                j=-1;
            }
        }
        if(keyc==38)
        {
            if(j >= 0)
            {
                j--;
                if(j <= -1)
                {
                    j = li.length;
                }
            }else{
                j = li.length - 1;
            }
        }
        set_style(j);
        if(j>=0 && j<li.length){
            obj.value=li[j].childNodes[0].nodeValue;
        }else{
            obj.value=temp_str;
        }
    }
    if(keyc==13)
    {
        if(kw == 'kw1'){
            search_top();
        }else{
            search_btm();
        }
        c('suggest','suggest');
    }
}

function mo(nodevalue){
    j = nodevalue;
    set_style(j);
}

function set_style(j)
{
    var suggest = $('suggest');
    var li = suggest.getElementsByTagName('li');
    for(var i=0; i<li.length; i++)
    {
        var li_node = li[i];
        li_node.className = "";
    }
    if(j>=0 && j<li.length)
    {
        var li_node = li[j];
        li[j].className = "active";
    }
}

function process()
{
    if(http_request.readyState == 4)
    {
        if(http_request.status==200)
        {
            str = http_request.responseText;
            $("suggest").innerHTML = str;
        }
    }
}

function form_submit(obj)
{
    var j = obj.value;
    var suggest = $('suggest');
    var li = suggest.getElementsByTagName('li');
    var parent_node = obj.parentNode.parentNode.parentNode;
    if(j>=0 && j<li.length)
    {
        if(parent_node.id == 'suggest_top'){
            $('kw1').value = li[j].childNodes[0].nodeValue;
        }else{
            $('kw2').value = li[j].childNodes[0].nodeValue;
        }
    } else {
        if(parent_node.id == 'sugget_top'){
            $('kw1').value = temp_str;
        }else{
            $('kw2').value = temp_str;
        }
    }
    if(parent_node.id == 'suggest_top'){
        search_top();
    }else{
        search_btm();
    }
    c('suggest', 'suggest');
}

function suggest_close()
{
    $('suggest').style.display = 'none';
}

function keyupdeal(keyc,obj)
{
    if(keyc!=40 && keyc!=38 && keyc!=37 && keyc!=39)
    {
        excludeCn(obj);

        if(obj.value == ''){
            $('suggest').innerHTML = '';
        }
        listener = function(){
            ajax_keyword(obj);
        }
        clearTimeout(timer);
        timer = setTimeout(listener,500);
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

document.onclick = function()
{
    suggest_close();
}

function google_ad_request_done(google_ads)
{
    var s='';
    var i;
    if(google_ads.length == 0)
    {
        return;
    }
    if(google_ads[0].type == "flash")
    {
        s += '<a href=\"'+
        google_info.feedback_url+'\" style="color:#555" target="_blank">Ads by Google</a><br>'+'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" WIDTH="'+
        google_ad.image_width+'" HEIGHT="'+
        google_ad.image_height+'"> <PARAM NAME="movie" VALUE="'+
        google_ad.image_url+'">'+'<PARAM NAME="quality" VALUE="high">'+'<PARAM NAME="AllowScriptAccess" VALUE="never">'+'<EMBED src="'+
        google_ad.image_url+'" WIDTH="'+
        google_ad.image_width+'" HEIGHT="'+
        google_ad.image_height+'" TYPE="application/x-shockwave-flash"'+' AllowScriptAccess="never" '+' PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED></OBJECT>';
    }
    else if(google_ads[0].type == "image")
    {
        if(google_ads[0].image_width == "468")
        {
            s+='<div><a href="'+
            google_ads[0].url+'" target="_top" title="go to '+
            google_ads[0].visible_url+'" onmouseout="window.status=\'\'" onmouseover="window.status=\'go to '+
            google_ads[0].visible_url+'\';return true"><img border="0" src="'+
            google_ads[0].image_url+'"width="'+
            google_ads[0].image_width+'"height="'+
            google_ads[0].image_height+'" ></a><a href=\"'+
            google_info.feedback_url+'\" style="color:#555;" target="_blank">Ads by Google</a></div>';
        }else{
            s+='<div style="margin:0 auto; width:728px;">'+'<table height="11" width="100%" cellspacing="0" cellpadding="0" border="0">'+'<tbody>'+'<tr>'+'<td><a target="_blank" style="display:block" href="'+
            google_ads[0].url+'" target="_top" title="go to '+
            google_ads[0].visible_url+'" onmouseout="window.status=\'\'" onmouseover="window.status=\'go to '+
            google_ads[0].visible_url+'\';return true"><img style="display:block" border="0" src="'+
            google_ads[0].image_url+'"width="'+
            google_ads[0].image_width+'"height="'+
            google_ads[0].image_height+'"></a></td>'+'</tr>'+'<tr>'+'<td align="right"><font face="arial,sans-serif" color="#555" style="line-height: 8px; font-size: 9px;"><a href=\"'+
            google_info.feedback_url+'\">Ads by Google</a></font></td>'+'</tr>'+'</tbody>'+'</table></div>';
        }
    }
    else if(google_ads[0].type == "html")
    {
        s += google_ads[0].snippet;
    }
    else
    {
        if(google_ads.length == 1)
        {
            s += '<p class="gone_by"><a style="color:#555" target="_blank" href=\"'+
            google_info.feedback_url+'\">Ads by Google</a></p><p class="gone_tit"><a style="display:block" href="'+
            google_ads[0].url+'"><b>'+
            google_ads[0].line1+'</b></a></p><p class="gone_txt">'+
            google_ads[0].line2+' '+
            google_ads[0].line3+'</p><p class="gone_url"><a style="display:block" href="'+
            google_ads[0].url+'">'+
            google_ads[0].visible_url+'</a></p>';
        }else if(google_ads.length<3){
            s += '<p align="left"><a target="_blank" style="color:#555;" href=\"'+
            google_info.feedback_url+'\">Ads by Google</a></p>';
            for(i=0; i<google_ads.length; ++i)
            {
                s+='<a target="_blank" style="text-decoration:none" href="'+
                google_ads[i].url+'" onmouseout="window.status=\'\'" onmouseover="window.status=\'go to '+
                google_ads[i].visible_url+'\';return true"> <span style="text-decoration:underline;text-align:left;"> <b style="font-family:Arial;font-size:16px;">'+
                google_ads[i].line1+'</b><br></span></a> <span style="color:#000000;text-align:left;">'+
                google_ads[i].line2+''+
                google_ads[i].line3+'<br></span> <span style="text-align:left;"><a target="_blank" style="color:#008000;text-decoration:none" href="'+
                google_ads[i].url+'" onmouseout="window.status=\'\'" onmouseover="window.status=\'go to '+
                google_ads[i].visible_url+'\';return true">'+
                google_ads[i].visible_url+'</span></a><br>';
            }
        }else if(google_ads.length >= 3){
            s+='<p class="g_by"><a target="_blank" href=\"'+google_info.feedback_url+'\" style="color:#555; text-decoration:none;">Ads by Google</a></p>'
            for(i=0;i<google_ads.length;++i)
            {
                s+='<p class="g_tit"><a target="_blank" href="'+
                google_ads[i].url+'"><b>'+
                google_ads[i].line1+'</b></a></p><p class="g_txt">'+
                google_ads[i].line2+' '+
                google_ads[i].line3+
                '<br/><a target="_blank" href="'+google_ads[i].url+'">'+ google_ads[i].visible_url+'</a>'+'</p>';

            }
        }
    }
    document.write(s);return;
}

function GetParam(name){
    var match = new RegExp(name+"=([^&]+)","i").exec(location.search);
    return (match) ? decodeURIComponent(match[1]) : null;
}

function fRandomBy(under, over){
    switch(arguments.length){
        case 1:
            return parseInt(Math.random()*under+1);
        case 2:
            return parseInt(Math.random()*(over-under+1)+under);
        default:
            return 0;
    }
}

function changeColor(color){document.getElementById('yoyo').style.background = color;document.getElementById('yoyo2').style.background = color;}

function getColor(theCode){var colors=[];colors[0]='#F8F8F8';colors[1]='#FBFFF1';colors[2]='#F8FFF0';colors[3]='#FBFFF1';return colors[theCode];}

function getChannel(theCode)
{
    var channel = [];
    channel[0] = '2122252845';
    channel[1] = '6118214555';
    channel[2] = '9161140606';
    channel[3] = '3412958365';
    return channel[theCode];
}

function yoyo_url(index)
{
    var uu = decodeURIComponent(yoyo[index]);
    window.open(uu);
}

var yoyo=[];
function google_afs_request_done(google_ads)
{
    var google_num_ads = google_ads.length;

    if(google_num_ads <= 0)
    {
        return;
    }
    var wideAds = "";
    var wideAds2 = "";
    var narrowAds = "";
    for(i=0; i<google_num_ads; i++)
    {
        if(google_ads[i].type == "text/wide")
        {
            //记录广告url
            yoyo[i] = encodeURIComponent( google_ads[i].url );
            if(i<4)
            {
                wideAds += '<div class="gtit gtt"><a href="javascript:void(0)" onclick="yoyo_url('+i+')">'+'<span>'+google_ads[i].line1+'</span></a></div>'+'<div class="gtxt">'+'<span class="gdesc">'+google_ads[i].line2+'</span>'+'<a target="_blank" class="gurl" href="javascript:void(0)" onclick="yoyo_url('+i+')">'+'<span>'+google_ads[i].visible_url+'</span></a>'+'</div>';
            } else {
                wideAds2 += '<div class="gtit gtt"><a href="javascript:void(0)" onclick="yoyo_url('+i+')">'+'<span>'+google_ads[i].line1+'</span></a></div>'+'<div class="gtxt">'+'<span class="gdesc">'+google_ads[i].line2+'</span>'+'<a target="_blank" class="gurl"  href="javascript:void(0)" onclick="yoyo_url('+i+')">'+'<span>'+google_ads[i].visible_url+'</span></a>'+'</div>';
            }
        }
        else
        {
            narrowAds += '<a target="_blank" href="">'+'<span>'+google_ads[i].line1+'</span></a>'+'<span>'+google_ads[i].line2+'</span>'+'<span>'+google_ads[i].line3+'</span>'+'<a target="_blank" href="'+google_ads[i].url+'">'+'<span>'+google_ads[i].visible_url+'</span></a>';
        }
    }
    if(narrowAds != "")
    {
        narrowAds = '<div><a '+'href="https://www.google.com/adsense/support/bin/request.py?contact=afs_violation">'+'<span style="color:#555;">Ads by Google</span></a></div>'+narrowAds;
    }
    if(wideAds != "")
    {
        wideAds = '<div><a style="text-decoration:none" '+'href="https://www.google.com/adsense/support/bin/request.py?contact=afs_violation">'+'<span style="text-align:left; color:#555;">Ads by Google</span></a></div>'+wideAds;
    }
    if(wideAds2 != "")
    {
        wideAds2 = '<div><a style="text-decoration:none" '+'href="https://www.google.com/adsense/support/bin/request.py?contact=afs_violation">'+'<span style="color:#555;">Ads by Google</span></a></div>'+wideAds2;
    }
    if($("yoyo2"))
    {
        $("yoyo2").innerHTML = wideAds2;
    }
    if($("yoyo")){
        $("yoyo").innerHTML = wideAds;
    }
}


function cpScrollMessage()
{
    var m1 = $('scrollmessage1');
    var m2 = $('scrollmessage2');
    if(!m1)
        return;
    m2.innerHTML = m1.innerHTML;
}

function slideLine(ul,delay,speed,lh)
{
    var slideBox1 = $(ul);
    if(!slideBox1)
        return;
    if(!slideBox1.getElementsByTagName('li')[0])
    {
        return;
    }
    var tid = null, pause = false;
    var start = function(){
        tid=setInterval(slide, speed);
    }
    var slide = function(){
        if(pause)return;
        slideBox1.scrollTop += 1.5;
        if(slideBox1.scrollTop%lh == 0){
            clearInterval(tid);
            slideBox1.appendChild(slideBox1.getElementsByTagName('li')[0]);
            slideBox1.scrollTop=0;
            setTimeout(start,delay);
        }
    }
    slideBox1.onmouseover = function(){
        pause = true;
    }
    slideBox1.onmouseout = function(){
        pause = false;
    }
    setTimeout(start, delay);
}

function resize_img(obj, nw, nh)
{
    var objImg = obj;
    if(objImg){
        var img_w = objImg.width;
        var img_h = objImg.height;
        var maxW = 80;
        var maxH = 80;
        var pW, pH;
        //var s = "img_w:"+img_w + " ### img_ht:"+img_h;
        //alert(s);
        var w = parseInt(img_w);
        var h = parseInt(img_h);
        if(nw){
            maxW = nw;
        }
        if(nh){
            maxH = nh;
        }
        if(objImg.getAttribute('src') == '')
        {
            objImg.setAttribute('src','http://img.tootoo.com/ebs3/images/no_photo_small.gif');
            return;
        }
        if(img_w<1 || img_h<1)
        {
            objImg.setAttribute('src','http://img.tootoo.com/ebs3/images/no_photo_small.gif');
            return;
        }
        if(w > maxW)
        {
            pW = maxW/w;
            pH = parseInt(h*pW);
            h = pH;
            w = maxW;
            img_w = maxW;
            img_h = pH;
            objImg.setAttribute('width',img_w);
        }
        if(h > maxH)
        {
            pH = maxH/h;
            pW = parseInt(w*pH);
            img_w = pW;
            img_h = maxH;
            objImg.setAttribute('height',img_h);
        }
        //objImg.setAttribute('width',img_w);
        //objImg.setAttribute('height',img_h);
    }
}

function set_img_size(id)
{
    var obj = $(id);
    if(!obj)
        return;
    img_items = obj.getElementsByTagName("img");
    if(img_items)
    {
        for(i=0; i<img_items.length; i++)
        {
            if(img_items[i].getAttribute('name') == 'photo')
            {
                resize_img(img_items[i]);
            }
        }
    }
}

function refineSearch(kw)
{
    var country = $('country');
    var time = $('time');
    var sort = $('sort');
    var code = $('code1');
    kw_str = StrCode(kw.replace(/ /g,'-'));
    var searchUrl = 'http://www.tootoo.com/inquire/'+kw_str;
    if(country.value != '')
        searchUrl += '_C-' + country.value;
    if(time.value != '')
        searchUrl += '_T-' + time.value;
    if(sort.value != '')
        searchUrl += '_S-' + sort.value;
    if(code.value != '')
        searchUrl += '_code-' + code.value;searchUrl+='/';
    window.location.href = searchUrl;
}

function output_cats(code)
{
    var cats=[];
    cats['01000000']='Agriculture';
    cats['02000000']='Apparel & Fashion';
    cats['03000000']='Automobile';
    cats['03000000']='Automobile';
    cats['04000000']='Business Services';
    cats['05000000']='Chemicals';
    cats['06000000']='Computer Hardware & Software';
    cats['07000000']='Construction & Real Estate';
    cats['08000000']='Electrical Equipment';
    cats['09000000']='Electronic Components';
    cats['10000000']='Energy';
    cats['11000000']='Environment';
    cats['12000000']='Excess Inventory';
    cats['13000000']='Food & Beverage';
    cats['14000000']='Furniture & Furnishings';
    cats['15000000']='Gifts & Crafts';
    cats['16000000']='Health & Beauty';
    cats['17000000']='Home Appliances';
    cats['18000000']='Home Supplies';
    cats['19000000']='Industrial Supplies';
    cats['20000000']='Lights & Lighting';
    cats['21000000']='Luggage, Bags & Cases';
    cats['22000000']='Minerals, Metals & Materials';
    cats['23000000']='Office Supplies';
    cats['24000000']='Packaging & Paper';
    cats['25000000']='Printing & Publishing';
    cats['26000000']='Security & Protection';
    cats['27000000']='Sports & Entertainment';
    cats['28000000']='Telecommunications';
    cats['29000000']='Textiles & Leather Products';
    cats['30000000']='Timepieces, Jewelry, Eyewear';
    cats['31000000']='Toys';
    cats['32000000']='Transportation';
    cats['99000000']='Others';

    var code1 = $('code1');
    var code2;
    if($('code2')){
        code2 = $('code2');
    }else{
        code2 = null;
    }

    for(var key in cats)
    {
        if(key == code){
            code1.options.add(new Option(cats[key], key, true));
            code2.options.add(new Option(cats[key], key, true));
        }else{
            code1.options.add(new Option(cats[key], key));
            code2.options.add(new Option(cats[key], key));
        }
    }
}

function highlight(kw)
{
    var kws = kw.split(' ');
    var p = $('plist').getElementsByTagName('p');
    for(var j=0; j<p.length; j++)
    {
        var text = p[j].innerHTML;
        for(var i=0; i<kws.length; i++)
        {
            re = new RegExp(kws[i], "ig");
            var retext = "<em>"+kws[i]+"</em>";
            text = text.replace(re,retext);
        }
        p[j].innerHTML = text;
    }
}

var TTJS=TTJS||{};
TTJS.lazyload = (function(){
    var timer,elems,count,delay=30,init_src_def='init_src',arr_df_tag=['img'],doc_body,doc_element;
    function _onChange()
    {
        !timer&&(timer=setTimeout(_load, delay));
    }

    function _isVisible(e)
    {

        var offset=0;
        if( typeof( window.pageYOffset ) == 'number' ) {
            offset	= window.pageYOffset;
        } else if( document.body && document.body.scrollTop ) {
            offset	= document.body.scrollTop;
        } else if( document.documentElement && document.documentElement.scrollTop ) {
            offset	= document.documentElement.scrollTop;
        }

        var bottom = offset + doc_element.clientHeight;
        var eOffsetTop = e.offsetTop;
        while(e=e.offsetParent)
        {
            eOffsetTop+=e.offsetTop;
        }
        return eOffsetTop<=bottom;
    }

    function _load(force)
    {
        if(count<1)
        {
            if(window.removeEventListener){
                window.removeEventListener('scroll',_onChange,false);
                window.removeEventListener('resize',_onChange,false);
            }else if(window.detachEvent){
                window.detachEvent('onscroll',_onChange);
                window.detachEvent('onresize',_onChange);
            }else{
                return false;
            }
            return;
        }

        for(var i=0,j=elems.length; i<j; i++)
        {
            if(!elems[i]){
                continue;
            }
            if(_isVisible(elems[i])){
                var i_src = elems[i].getAttribute(init_src_def);
                elems[i].src = i_src != '' ? i_src : 'http://img.tootoo.com/ebs3/images/no_photo_small.gif';
                resize_img(elems[i], 80, 80);//等比缩放
                delete elems[i];
                count--;
            }
        }

        timer=0;
    }

    function init()
    {
        doc_body=document.body;
        doc_element=document.compatMode=='BackCompat'?doc_body:document.documentElement;
        var tagNames = arr_df_tag;
        timer=0;
        elems=[];
        count=0;
        for(var i=0,j=tagNames.length;i<j;i++)
        {
            var es = document.getElementsByTagName(tagNames[i]);
            for(var n=0,m=es.length; n<m; n++)
            {
                if(typeof es[n]=='object' && es[n].getAttribute('name')=='photo'){
                    elems.push(es[n]);
                    count++;
                }
            }
        }
        if(window.addEventListener){
            window.addEventListener('scroll',_onChange,false);
            window.addEventListener('resize',_onChange,false);
        }else if(window.attachEvent){
            window.attachEvent('onscroll',_onChange);
            window.attachEvent('onresize',_onChange);
        }

        _load();
    }
    return{init:init};
})();

function check_ref()
{
    if(is_so())
    {
        var ele = document.getElementById('plist').getElementsByTagName('li');
        var child;
        var ele_time;
        for(var i=0, j=ele.length; i<j; i++)
        {
            if(i>3){break}
            child = ele[i].children;
            for(var m=0; m<child.length; m++)
            {
                if(child[m].className == 'pic')
                {
                    child[m].style.display='none';
                    if(ele[i].getElementsByTagName('span')[0])
                    {
                        ele_time = ele[i].getElementsByTagName('span')[0];
                        if(ele_time.className == 'time')
                        {
                           ele_time.style.display='none';
                        }
                    }
                }
            }
        }
        if(document.getElementById('yoyo'))
        {
            document.getElementById('yoyo').style.background = 'none';
        }
        if(document.getElementById('yoyo2'))
        {
            document.getElementById('yoyo2').style.background = 'none';
        }
    }
}

function is_so()
{
    var so = ['google.com', 'yahoo.com', 'msn.com',];
    var http_ref = document.referrer;
    var msg = false;
    for(var i=0, j=so.length; i<j; i++)
    {
        if(http_ref.indexOf(so[i]) >= 0)
        {
            msg = true;
            break;
        }
    }
    return msg;
}