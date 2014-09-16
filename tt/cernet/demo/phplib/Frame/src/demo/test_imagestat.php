
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style type="text/css">
<!--
td {
	font-size: 9pt;
}
-->
</style>
</head>

<body>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center">饼 图</td>
  </tr>
  <tr> 
    <td align="center"><table width="500" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="145"><table width="100" border="0" align="center" cellpadding="0" cellspacing="2">
              <tr> 
                <td width="35" align="right">1月&nbsp;</td>
                <td width="27" bgcolor="#97BD00">&nbsp;</td>
                <td width="30" align="right">3</td>
              </tr>
              <tr> 
                <td align="right">2月&nbsp;</td>
                <td bgcolor="#009900">&nbsp;</td>
                <td align="right">2</td>
              </tr>
              <tr> 
                <td align="right">3月&nbsp;</td>
                <td bgcolor="#CC3300">&nbsp;</td>
                <td align="right">3</td>
              </tr>
              <tr> 
                <td align="right">4月&nbsp;</td>
                <td bgcolor="#FFCC00">&nbsp;</td>
                <td align="right">4</td>
              </tr>
              <tr> 
                <td align="right">5月&nbsp;</td>
                <td bgcolor="#3366CC">&nbsp;</td>
                <td align="right">8</td>
              </tr>
              <tr> 
                <td align="right">6月&nbsp;</td>
                <td bgcolor="#33CC33">&nbsp;</td>
                <td align="right">12</td>
              </tr>
              <tr> 
                <td align="right">7月&nbsp;</td>
                <td bgcolor="#FF9933">&nbsp;</td>
                <td align="right">3</td>
              </tr>
              <tr> 
                <td align="right">8月&nbsp;</td>
                <td bgcolor="#CCCC99">&nbsp;</td>
                <td align="right">5</td>
              </tr>
              <tr> 
                <td align="right">9月&nbsp;</td>
                <td bgcolor="#99CC66">&nbsp;</td>
                <td align="right">&nbsp;</td>
              </tr>
              <tr> 
                <td align="right">10月&nbsp;</td>
                <td bgcolor="#66FF99">&nbsp;</td>
                <td align="right">&nbsp;</td>
              </tr>
            </table></td>
          <td width="355"><img src="ImageStat.class.php?action=pie&a=3,2,3,4,8,12,3,5"/></td>
        </tr>
      </table>
	  <!---<img src="aa.php"/>--->
      &nbsp;</td>
  </tr>
  <tr> 
    <td align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center"><p>&nbsp;</p>
      <p>&nbsp;</p></td>
  </tr>
  <tr> 
    <td align="center">折 线 图</td>
  </tr>
  <tr> 
    <td align="center"><img src="ImageStat.class.php?action=line&a=5.4,2,32,4,0,6,7.7,38,2,3,4"/>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr align="center"> 
		<?
		for($i=1;$i<12;$i++) {
			echo "<td width=\"30\">".$i."月</td>";
		}
		?>
          <td width="30">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center"><p>&nbsp;</p>
      <p>&nbsp;</p></td>
  </tr>
  <tr> 
    <td align="center">柱　状　图</td>
  </tr>
  <tr> 
    <td align="center"><img src="ImageStat.class.php?action=bar&a=5.4,2,30.2,4,0,6,7.7,3.8,2,3,4"/>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr align="center"> 
		<?
		for($i=1;$i<12;$i++) {
			echo "<td width=\"50\">".$i."月</td>";
		}
		?>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
</body>
</html>
