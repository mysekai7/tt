Jet().$package(function(h){var e=window.location.host;h.cookie={set:function(a,c,b,f,d){if(d){var i=new Date,g=new Date;g.setTime(i.getTime()+36E5*d)}window.document.cookie=a+"="+c+"; "+(d?"expires="+g.toGMTString()+"; ":"")+(f?"path="+f+"; ":"path=/; ")+(b?"domain="+b+";":"domain="+e+";");return true},get:function(a){a=window.document.cookie.match(new RegExp("(?:^|;+|\\s+)"+a+"=([^;]*)"));return!a?"":a[1]},remove:function(a,c,b){window.document.cookie=a+"=; expires=Mon, 26 Jul 1997 05:00:00 GMT; "+
(b?"path="+b+"; ":"path=/; ")+(c?"domain="+c+";":"domain="+e+";")}}});
