<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_fence
 *
 * @author NINETOWNS
 */
class fence {
    //put your code here

    public function refresh()
    {
        if (isset($_COOKIE['lastrequest'])) {
            list($lastrequest,$lastpath) = explode("\t",$_COOKIE['lastrequest']);
            $onlinetime = $_SERVER['REQUEST_TIME' ] - $lastrequest;
        } else {
            $lastrequest = $lastpath = '';
        }
        $REQUEST_URI  = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

        if ($REQUEST_URI == $lastpath && $onlinetime < 2)
            throw new Exception('请求次数过于频繁了!');

        setcookie('lastrequest', $_SERVER['REQUEST_TIME' ]."\t".$REQUEST_URI);
    }

    public function ip()
    {

    }
}
?>
