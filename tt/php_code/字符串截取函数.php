<?php
/**
 * Utf-8、gb2312都支持的字符串截取函数
 * @param string $string 需要截取的字符串
 * @param int $sublen 截取子串的长度，一个中文算一个长度
 * @param int $start 截取开始位置，默认为0
 * @param string $code 截取字符串的编码，默认为UTF-8
 * @param string $pad 长度不够自动补齐的字符，默认为…
 * @return string
 */
function cutString($string, $sublen, $start = 0, $code = 'UTF-8', $pad = '…') {
    if($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if(count($t_string[0]) - $start > $sublen) {
            return join('', array_slice($t_string[0], $start, $sublen)).$pad;
        }
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start = $start*2;
        $sublen = $sublen*2;
        $strlen = strlen($string);
        $tmpstr = '';
        for($i=0; $i<$strlen; $i++) {
            if($i>=$start && $i<($start+$sublen)) {
                if(ord(substr($string, $i, 1))>129) {
                    $tmpstr.= substr($string, $i, 2);
                } else {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if(ord(substr($string, $i, 1))>129) $i++;
        }
        if(strlen($tmpstr)<$strlen ) $tmpstr.= $pad;
        return $tmpstr;
    }
}
?>