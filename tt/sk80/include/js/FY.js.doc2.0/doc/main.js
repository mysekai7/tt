function H(){var a=navigator&&navigator.userAgent&&/\bMSIE 6\./.test(navigator.userAgent);H=function(){return a};return a}(function(){function aA(q){q=q.split(/ /g);var r={};for(var v=q.length;--v>=0;){var u=q[v];if(u){r[u]=null}}return r}var ay="break continue do else for if return while ",k=ay+"auto case char const default double enum extern float goto int long register short signed sizeof static struct switch typedef union unsigned void volatile ",ai=k+"catch class delete false import new operator private protected public this throw true try ",ae=ai+"alignof align_union asm axiom bool concept concept_map const_cast constexpr decltype dynamic_cast explicit export friend inline late_check mutable namespace nullptr reinterpret_cast static_assert static_cast template typeid typename typeof using virtual wchar_t where ",ad=ai+"boolean byte extends final finally implements import instanceof null native package strictfp super synchronized throws transient ",i=ad+"as base by checked decimal delegate descending event fixed foreach from group implicit in interface internal into is lock object out override orderby params readonly ref sbyte sealed stackalloc string select uint ulong unchecked unsafe ushort var ",ac=ai+"debugger eval export function get null set undefined var with Infinity NaN ",ab="caller delete die do dump elsif eval exit foreach for goto if import last local my next no our print package redo require sub undef unless until use wantarray while BEGIN END ",B=ay+"and as assert class def del elif except exec finally from global import in is lambda nonlocal not or pass print raise try with yield False True None ",A=ay+"alias and begin case class def defined elsif end ensure false in module next nil not or redo rescue retry self super then true undef unless until when yield BEGIN END ",w=ay+"case done elif esac eval fi function in local set then until ",h=ae+i+ac+ab+B+A+w;function f(q){return q>="a"&&q<="z"||q>="A"&&q<="Z"}function aD(q,r,v,u){q.unshift(v,u||0);try{r.splice.apply(r,q)}finally{q.splice(0,2)}}var e=(function(){var q=["!","!=","!==","#","%","%=","&","&&","&&=","&=","(","*","*=","+=",",","-=","->","/","/=",":","::",";","<","<<","<<=","<=","=","==","===",">",">=",">>",">>=",">>>",">>>=","?","@","[","^","^=","^^","^^=","{","|","|=","||","||=","~","break","case","continue","delete","do","else","finally","instanceof","return","throw","try","typeof"],r="(?:(?:(?:^|[^0-9.])\\.{1,3})|(?:(?:^|[^\\+])\\+)|(?:(?:^|[^\\-])-)";for(var v=0;v<q.length;++v){var u=q[v];r+=f(u.charAt(0))?"|\\b"+u:"|"+u.replace(/([^=<>:&])/g,"\\$1")}r+="|^)\\s*$";return new RegExp(r)})(),t=/&/g,s=/</g,o=/>/g,c=/\"/g;function at(q){return q.replace(t,"&amp;").replace(s,"&lt;").replace(o,"&gt;").replace(c,"&quot;")}function ah(q){return q.replace(t,"&amp;").replace(s,"&lt;").replace(o,"&gt;")}var ak=/&lt;/g,aI=/&gt;/g,au=/&apos;/g,al=/&quot;/g,a=/&amp;/g,av=/&nbsp;/g;function am(q){var r=q.indexOf("&");if(r<0){return q}for(--r;(r=q.indexOf("&#",r+1))>=0;){var y=q.indexOf(";",r);if(y>=0){var x=q.substring(r+3,y),u=10;if(x&&x.charAt(0)==="x"){x=x.substring(1);u=16}var v=parseInt(x,u);if(!isNaN(v)){q=q.substring(0,r)+String.fromCharCode(v)+q.substring(y+1)}}}return q.replace(ak,"<").replace(aI,">").replace(au,"'").replace(al,'"').replace(a,"&").replace(av," ")}function n(q){return"XMP"===q.tagName}function ax(q,r){switch(q.nodeType){case 1:var y=q.tagName.toLowerCase();r.push("<",y);for(var x=0;x<q.attributes.length;++x){var u=q.attributes[x];if(!u.specified){continue}r.push(" ");ax(u,r)}r.push(">");for(var v=q.firstChild;v;v=v.nextSibling){ax(v,r)}if(q.firstChild||!/^(?:br|link|img)$/.test(y)){r.push("</",y,">")}break;case 2:r.push(q.name.toLowerCase(),'="',at(q.value),'"');break;case 3:case 4:r.push(ah(q.nodeValue));break}}var ag=null;function b(q){if(null===ag){var r=document.createElement("PRE");r.appendChild(document.createTextNode('<!DOCTYPE foo PUBLIC "foo bar">\n<foo />'));ag=!/</.test(r.innerHTML)}if(ag){var x=q.innerHTML;if(n(q)){x=ah(x)}return x}var v=[];for(var u=q.firstChild;u;u=u.nextSibling){ax(u,v)}return v.join("")}function aw(q){var r=0;return function(D){var C=null,x=0;for(var z=0,v=D.length;z<v;++z){var y=D.charAt(z);switch(y){case"\t":if(!C){C=[]}C.push(D.substring(x,z));var u=q-r%q;r+=u;for(;u>=0;u-="                ".length){C.push("                ".substring(0,u))}x=z+1;break;case"\n":r=0;break;default:++r}}if(!C){return D}C.push(D.substring(x));return C.join("")}}var an=/(?:[^<]+|<!--[\s\S]*?--\>|<!\[CDATA\[([\s\S]*?)\]\]>|<\/?[a-zA-Z][^>]*>|<)/g,d=/^<!--/,az=/^<\[CDATA\[/,ao=/^<br\b/i;function g(C){var D=C.match(an),z=[],y=0,u=[];if(D){for(var x=0,r=D.length;x<r;++x){var v=D[x];if(v.length>1&&v.charAt(0)==="<"){if(d.test(v)){continue}if(az.test(v)){z.push(v.substring(9,v.length-3));y+=v.length-12}else{if(ao.test(v)){z.push("\n");++y}else{u.push(y,v)}}}else{var q=am(v);z.push(q);y+=q.length}}}return{source:z.join(""),tags:u}}function aC(q,r){var v={};(function(){var z=q.concat(r);for(var D=z.length;--D>=0;){var y=z[D],C=y[3];if(C){for(var x=C.length;--x>=0;){v[C.charAt(x)]=y}}}})();var u=r.length;return function(J,L){L=L||0;var I=[L,"pln"],K="",G=0,F=J;while(F.length){var y,C=null,E,D=v[F.charAt(0)];if(D){E=F.match(D[1]);C=E[0];y=D[0]}else{for(var z=0;z<u;++z){D=r[z];var x=D[2];if(x&&!x.test(K)){continue}E=F.match(D[1]);if(E){C=E[0];y=D[0];break}}if(!C){y="pln";C=F.substring(0,1)}}I.push(L+G,y);G+=C.length;F=F.substring(C.length);if(y!=="com"&&/\S/.test(C)){K=C}}return I}}var aB=aC([],[["pln",/^[^<]+/,null],["dec",/^<!\w[^>]*(?:>|$)/,null],["com",/^<!--[\s\S]*?(?:--\>|$)/,null],["src",/^<\?[\s\S]*?(?:\?>|$)/,null],["src",/^<%[\s\S]*?(?:%>|$)/,null],["src",/^<(script|style|xmp)\b[^>]*>[\s\S]*?<\/\1\b[^>]*>/i,null],["tag",/^<\/?\w[^<>]*>/,null]]);function ap(q){var r=aB(q);for(var z=0;z<r.length;z+=2){if(r[z+1]==="src"){var y,v;y=r[z];v=z+2<r.length?r[z+2]:q.length;var x=q.substring(y,v),u=x.match(/^(<[^>]*>)([\s\S]*)(<\/[^>]*>)$/);if(u){r.splice(z,2,y,"tag",y+u[1].length,"src",y+u[1].length+(u[2]||"").length,"tag")}}}return r}var j=aC([["atv",/^\'[^\']*(?:\'|$)/,null,"'"],["atv",/^\"[^\"]*(?:\"|$)/,null,'"'],["pun",/^[<>\/=]+/,null,"<>/="]],[["tag",/^[\w:\-]+/,/^</],["atv",/^[\w\-]+/,/^=/],["atn",/^[\w:\-]+/,null],["pln",/^\s+/,null," \t\r\n"]]);function aE(q,r){for(var C=0;C<r.length;C+=2){var z=r[C+1];if(z==="tag"){var v,y;v=r[C];y=C+2<r.length?r[C+2]:q.length;var u=q.substring(v,y),x=j(u,v);aD(x,r,C,2);C+=x.length-2}}return r}function aG(q){var r=[],z=[];if(q.tripleQuotedStrings){r.push(["str",/^(?:\'\'\'(?:[^\'\\]|\\[\s\S]|\'{1,2}(?=[^\']))*(?:\'\'\'|$)|\"\"\"(?:[^\"\\]|\\[\s\S]|\"{1,2}(?=[^\"]))*(?:\"\"\"|$)|\'(?:[^\\\']|\\[\s\S])*(?:\'|$)|\"(?:[^\\\"]|\\[\s\S])*(?:\"|$))/,null,"'\""])}else{if(q.multiLineStrings){r.push(["str",/^(?:\'(?:[^\\\']|\\[\s\S])*(?:\'|$)|\"(?:[^\\\"]|\\[\s\S])*(?:\"|$)|\`(?:[^\\\`]|\\[\s\S])*(?:\`|$))/,null,"'\"`"])}else{r.push(["str",/^(?:\'(?:[^\\\'\r\n]|\\.)*(?:\'|$)|\"(?:[^\\\"\r\n]|\\.)*(?:\"|$))/,null,"\"'"])}}z.push(["pln",/^(?:[^\'\"\`\/\#]+)/,null," \r\n"]);if(q.hashComments){r.push(["com",/^#[^\r\n]*/,null,"#"])}if(q.cStyleComments){z.push(["com",/^\/\/[^\r\n]*/,null])}if(q.regexLiterals){z.push(["str",/^\/(?:[^\\\*\/\[]|\\[\s\S]|\[(?:[^\]\\]|\\.)*(?:\]|$))+(?:\/|$)/,e])}if(q.cStyleComments){z.push(["com",/^\/\*[\s\S]*?(?:\*\/|$)/,null])}var y=aA(q.keywords);q=null;var v=aC(r,z),x=aC([],[["pln",/^\s+/,null," \r\n"],["pln",/^[a-z_$@][a-z_$@0-9]*/i,null],["lit",/^0x[a-f0-9]+[a-z]/i,null],["lit",/^(?:\d(?:_\d+)*\d*(?:\.\d*)?|\.\d+)(?:e[+\-]?\d+)?[a-z]*/i,null,"123456789"],["pun",/^[^\s\w\.$@]+/,null]]);function u(N,M){for(var L=0;L<M.length;L+=2){var F=M[L+1];if(F==="pln"){var I,K,J,G;I=M[L];K=L+2<M.length?M[L+2]:N.length;J=N.substring(I,K);G=x(J,I);for(var D=0,P=G.length;D<P;D+=2){var O=G[D+1];if(O==="pln"){var E=G[D],C=D+2<P?G[D+2]:J.length,Q=N.substring(E,C);if(Q==="."){G[D+1]="pun"}else{if(Q in y){G[D+1]="kwd"}else{if(/^@?[A-Z][A-Z$]*[a-z][A-Za-z$]*$/.test(Q)){G[D+1]=Q.charAt(0)==="@"?"lit":"typ"}}}}}aD(G,M,L,2);L+=G.length-2}}return M}return function(D){var C=v(D);C=u(D,C);return C}}var af=aG({keywords:h,hashComments:true,cStyleComments:true,multiLineStrings:true,regexLiterals:true});function aq(C,D){for(var z=0;z<D.length;z+=2){var y=D[z+1];if(y==="src"){var u,x;u=D[z];x=z+2<D.length?D[z+2]:C.length;var r=af(C.substring(u,x));for(var v=0,q=r.length;v<q;v+=2){r[v]+=u}aD(r,D,z,2);z+=r.length-2}}return D}function m(K,L){var J=false;for(var I=0;I<L.length;I+=2){var E=L[I+1],G,D;if(E==="atn"){G=L[I];D=I+2<L.length?L[I+2]:K.length;J=/^on|^style$/i.test(K.substring(G,D))}else{if(E==="atv"){if(J){G=L[I];D=I+2<L.length?L[I+2]:K.length;var F=K.substring(G,D),C=F.length,z=C>=2&&/^[\"\']/.test(F)&&F.charAt(0)===F.charAt(C-1),r,v,y;if(z){v=G+1;y=D-1;r=F}else{v=G+1;y=D-1;r=F.substring(1,F.length-1)}var x=af(r);for(var u=0,q=x.length;u<q;u+=2){x[u]+=v}if(z){x.push(y,"atv");aD(x,L,I+2,0)}else{aD(x,L,I,2)}}J=false}}}return L}function aF(q){var r=ap(q);r=aE(q,r);r=aq(q,r);r=m(q,r);return r}function ar(F,G,E){var D=[],y=0,C=null,x=null,z=0,v=0,u=aw(8);function q(J){if(J>y){if(C&&C!==x){D.push("</span>");C=null}if(!C&&x){C=x;D.push('<span class="',C,'">')}var I=ah(u(F.substring(y,J))).replace(/(\r\n?|\n| ) /g,"$1&nbsp;").replace(/\r\n?|\n/g,"<br />");D.push(I);y=J}}while(true){var r;r=z<G.length?(v<E.length?G[z]<=E[v]:true):false;if(r){q(G[z]);if(C){D.push("</span>");C=null}D.push(G[z+1]);z+=2}else{if(v<E.length){q(E[v]);x=E[v+1];v+=2}else{break}}}q(F.length);if(C){D.push("</span>")}return D.join("")}var aj={};function aH(q,r){for(var v=r.length;--v>=0;){var u=r[v];if(!aj.hasOwnProperty(u)){aj[u]=q}else{if("console" in window){console.log("cannot override language handler %s",u)}}}}aH(af,["default-code"]);aH(aF,["default-markup","html","htm","xhtml","xml","xsl"]);aH(aG({keywords:ae,hashComments:true,cStyleComments:true}),["c","cc","cpp","cs","cxx","cyc"]);aH(aG({keywords:ad,cStyleComments:true}),["java"]);aH(aG({keywords:w,hashComments:true,multiLineStrings:true}),["bsh","csh","sh"]);aH(aG({keywords:B,hashComments:true,multiLineStrings:true,tripleQuotedStrings:true}),["cv","py"]);aH(aG({keywords:ab,hashComments:true,multiLineStrings:true,regexLiterals:true}),["perl","pl","pm"]);aH(aG({keywords:A,hashComments:true,multiLineStrings:true,regexLiterals:true}),["rb"]);aH(aG({keywords:ac,cStyleComments:true,regexLiterals:true}),["js"]);function l(q,r){try{var z=g(q),y=z.source,v=z.tags;if(!aj.hasOwnProperty(r)){r=/^\s*</.test(y)?"default-markup":"default-code"}var x=aj[r].call({},y);return ar(y,v,x)}catch(u){if("console" in window){console.log(u);console.trace()}return q}}function p(q){var r=H(),C=[document.getElementsByTagName("pre"),document.getElementsByTagName("code"),document.getElementsByTagName("xmp")],z=[];for(var v=0;v<C.length;++v){for(var y=0;y<C[v].length;++y){z.push(C[v][y])}}C=null;var u=0;function x(){var N=(new Date).getTime()+250;for(;u<z.length&&(new Date).getTime()<N;u++){var M=z[u];if(M.className&&M.className.indexOf("code")>=0){var G=M.className.match(/\blang-(\w+)\b/);if(G){G=G[1]}var J=false;for(var L=M.parentNode;L;L=L.parentNode){if((L.tagName==="pre"||L.tagName==="code"||L.tagName==="xmp")&&L.className&&L.className.indexOf("prettyprint")>=0){J=true;break}}if(!J){var K=b(M);K=K.replace(/(?:\r\n?|\n)$/,"");var I=l(K,G);if(!n(M)){M.innerHTML=I}else{var E=document.createElement("PRE");for(var P=0;P<M.attributes.length;++P){var O=M.attributes[P];if(O.specified){E.setAttribute(O.name,O.value)}}E.innerHTML=I;M.parentNode.replaceChild(E,M);E=M}if(r&&M.tagName==="PRE"){var F=M.getElementsByTagName("br");for(var D=F.length;--D>=0;){var Q=F[D];Q.parentNode.replaceChild(document.createTextNode("\r\n"),Q)}}}}}if(u<z.length){setTimeout(x,250)}else{if(q){q()}}}x()}window.PR_normalizedHtml=ax;window.prettyPrintOne=l;window.code=p;window.PR={createSimpleLexer:aC,registerLangHandler:aH,sourceDecorator:aG,PR_ATTRIB_NAME:"atn",PR_ATTRIB_VALUE:"atv",PR_COMMENT:"com",PR_DECLARATION:"dec",PR_KEYWORD:"kwd",PR_LITERAL:"lit",PR_PLAIN:"pln",PR_PUNCTUATION:"pun",PR_SOURCE:"src",PR_STRING:"str",PR_TAG:"tag",PR_TYPE:"typ"}})();