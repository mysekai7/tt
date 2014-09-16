<?php
function xxxx($str) {
        $return = '';
        for ($i = 0; ; $i++) {
                @$s = $str{$i};
                if ($s) {
                        $return = $s . $return;
                } else {
                        break;
                }
        }
        return $return;
}

?>