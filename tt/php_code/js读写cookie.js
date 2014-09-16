function getCookieVal(offset) {
    var endstr = document.cookie.indexOf (";", offset);
    if(endstr == -1) {
        endstr = document.cookie.length;
    }
    return unescape(document.cookie.substring(offset, endstr));
}
function getCookie(name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    var j = 0;
    while(i < clen) {
        j = i + alen;
        if(document.cookie.substring(i, j) == arg)
            return getCookieVal(j);
        i = document.cookie.indexOf(" ", i) + 1;
        if(i == 0)
            break;
    }
    return null;
}
function deleteCookie(name) {
    var exp = new Date();
    var cval = getCookie(name);
    exp.setTime(exp.getTime() - 1);
    document.cookie = name + "=" + cval + "; expires=" + exp.toGMTString();
}
var gCookieExpDays = 80;
function setCookie(name, value) {
    var argv = setCookie.arguments;
    var argc = setCookie.arguments.length;
    var exp = (argc > 2) ? argv[2] : gCookieExpDays;
    var path = (argc > 3) ? argv[3] : null;
    var domain = (argc > 4) ? argv[4] : null;
    var secure = (argc > 5) ? argv[5] : false;
    var expires = new Date();
    deleteCookie(name);
    expires.setTime(expires.getTime() + (exp*24*60*60*1000));
    document.cookie = name + "=" + value +
        "; expires=" + expires.toGMTString() +
        ((domain == null) ? "" : ("; domain=" + domain)) +
        ((path == null) ? "" : ("; path=" + path)) +
        ((secure == true) ? "; secure" : "");
}