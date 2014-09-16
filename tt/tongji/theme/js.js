function show_record_chart(engine, sitename, url, time)
{
    //alert(engine+'##'+sitename+'##'+'##'+url+'##'+time);

    var d = time.split('-');
    var date = new Date(d[0], d[1], d[2]);

    var request_url = 'ajax_record.php?engine='+encodeURIComponent(engine)+'&sitename='+encodeURIComponent(sitename)+'&url='+url+'&date='+encodeURIComponent(time)+'&'+new Date().getTime();
    var wBox=$("#wbox1").wBox({
        title: engine+"收录: "+decodeURIComponent(url)+"  时间: "+date.getFullYear()+'年'+date.getMonth()+'月',
        requestType: "ajax",
        target:request_url
        //html: "<div class='demo'>点击弹出设置名字的wBox</div>"
    });
   wBox.showBox();
}

function show_crawl_chart(engine, sitename, url, key, time)
{
    //alert(engine+'##'+sitename+'##'+'##'+url+'##'+time);

    var d = time.split('-');
    var date = new Date(d[0], d[1], d[2]);

    var request_url = 'ajax_crawl.php?engine='+encodeURIComponent(engine)+'&sitename='+encodeURIComponent(sitename)+'&url='+url+'&key='+key+'&date='+encodeURIComponent(time)+'&'+new Date().getTime();
    var wBox=$("#wbox1").wBox({
        title: engine+"抓取: "+decodeURIComponent(url)+"  时间: "+date.getFullYear()+'年'+date.getMonth()+'月',
        requestType: "ajax",
        target:request_url
        //html: "<div class='demo'>点击弹出设置名字的wBox</div>"
    });
   wBox.showBox();
}