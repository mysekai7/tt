function cc()
{
    var e = event.srcElement;
    var r =e.createTextRange();
    r.moveStart(`character`,e.value.length);
    r.collapse(true);
    r.select();
}