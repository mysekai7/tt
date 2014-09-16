var iWidth = screen.width, iHeight = screen.height;

function blockError() { return true; }
window.onerror = blockError;

$(function() {

  if($.browser.msie)
    $("#topmenu > ul > li:has(ul)").hover(function() { this.className="over"; }, function() { this.className=""; });

  $("#dropsearch").dropSearch({ basedomain: "chir.ag", inittext: "search", filter: "-intitle:search", align: "right", relative: true, width: "320" });

});

function openEmail()
{
  var emailW=600, emailH=450;
  var emailX = (iWidth/2)-(emailW/2);
  var emailY = (iHeight/2)-(emailH/2);

  EML=window.open("/contact.shtml","contact","menubar=0,nostatus,resizable=no,scrollbars=no,left="+emailX+",top="+emailY+",width="+emailW+",height="+emailH);
}

function editBlog(blogdate)
{
  var emailW=660, emailH=720;
  var emailX = (iWidth/2)-(emailW/2);
  var emailY = (iHeight/2)-(emailH/2);

  if(blogdate == "")
  {
    var dt = new Date()
    var m = "0" + (dt.getMonth() + 1);
    var d = "0" + dt.getDate();
    blogdate = "" + dt.getFullYear() + m.substr(m.length-2,2) + d.substr(d.length-2,2);
  }

  BL=window.open("/blog.shtml?"+blogdate,"blogedit","menubar=0,nostatus,resizable=no,scrollbars=no,left="+emailX+",top="+emailY+",width="+emailW+",height="+emailH);
  BL.focus();
}

function showPic(pic,caption)
{
  var emailX = (iWidth/2)-(410);
  var emailY = (iHeight/2)-(320);

  PIC=window.open("","digipic","nostatus,resizable=no,scrollbars=no,left=0,top=0,width=800,height=627");
  PIC.document.open("text/html","replace");
  PIC.document.writeln("<html><head><title>Digicam Pics - Chirag Mehta</title><link rel=stylesheet type='text/css' href='/res/main.css'></head><body leftmargin=0 rightmargin=0 marginwidth=0 marginheight=0 topmargin=0><table cellspacing=0 cellpadding=0 border=0 width=800 height=615><tr><td align=center valign=middle height=600>");
  PIC.document.writeln("    <img src='/digi/"+pic+"' border=0></td></tr><tr><td align=center valign=top bgcolor=48606E height=2><img src='/res/ws.gif' width=1 height=2></td></tr><tr><td align=center valign=top>");
  PIC.document.writeln("    <font color=48606E><b>"+caption+"</b></font>");
  PIC.document.writeln("  </td></tr></table></body></html>");
  PIC.document.close();
}