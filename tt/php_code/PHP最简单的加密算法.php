<?php
function de($str)
{
for($i=0;$i<strlen($str);$i++)
  {

  switch($i%6)
  {
    case 0:
      $temp.=chr(ord($str{$i})-1);
      break;
    case 1:
      $temp.=chr(ord($str{$i})-5);
      break;
    case 2:
      $temp.=chr(ord($str{$i})-7);
      break;
    case 3:
      $temp.=chr(ord($str{$i})-2);
      break;
    case 4:
      $temp.=chr(ord($str{$i})-4);
      break;
      case 5:
      $temp.=chr(ord($str{$i})-9);
      break;
  }



  }
return  $temp;
}
/*
替换解密算法
*/
function ed($str)
  {
  for($i=0;$i<strlen($str);$i++)
  {

  switch($i%6)
  {
    case 0:
      $temp.=chr(ord($str{$i})+1);
      break;
    case 1:
      $temp.=chr(ord($str{$i})+5);
      break;
    case 2:
      $temp.=chr(ord($str{$i})+7);
      break;
    case 3:
      $temp.=chr(ord($str{$i})+2);
      break;
    case 4:
      $temp.=chr(ord($str{$i})+4);
      break;
      case 5:
      $temp.=chr(ord($str{$i})+9);
      break;
  }



  }


return  $temp;


  }
?>