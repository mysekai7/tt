function getIE(E){
    var t=e.offsetTop;
    var l=e.offsetLeft;
    while(e=e.offsetParent){
        t+=e.offsetTop;
        l+=e.offsetLeft;
    }
    alert("top="+t+"/nleft="+l);
}