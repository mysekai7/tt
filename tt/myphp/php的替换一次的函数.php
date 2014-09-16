<?php
function str_replace_once($needle, $replace, $haystack) {

    return preg_replace('/' . preg_quote($needle,'/') . '/', $replace,$haystack,1);

}
?>