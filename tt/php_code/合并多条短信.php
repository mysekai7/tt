<?php
$resultContent = '';
if (intval($_GET['total'])>1) {// 如果总数超过三条进行处理，否则本条即完整信息。
    $flag = true;//标志
    $fp = fopen('conf/' . $_GET['groupid'] . '_' . $_GET['num'].'.txt', 'w');//将段保存下来以groupid_num.txt方式命名
    fputs($fp,$_GET['content']);
    fclose($fp);
    for ($i=1; $i<=$_GET['total']; $i++) { //循环total次检查所有临时文件是否都存在了
          if (!is_file('conf/' . $_GET['groupid'] . '_' . $i.'.txt')) {
                 $flag = false;
                 break;// 只要有一个不存在即信息还不完整
          }
     }
     if ($flag) {// 所有临时文件都有了就可以把所以文本文件读出来连成一个字符串,即完整信息
            $resultContent = '';
             for ($i=1; $i<=$_GET['total']; $i++) {
                   $resultContent .= file_get_contents('conf/' . $_GET['groupid'] . '_' . $i.'.txt');
                    @unlink('conf/' . $_GET['groupid'] . '_' . $i.'.txt');
              }
     } else {
             exit;//不完整退出没有任何操作
     }
} else {
    $resultContent = $_GET['content'];
}
?>