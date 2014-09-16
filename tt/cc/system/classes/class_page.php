<?php
class page
{
    public function __construct()
    {
        //$this->network = & $GLOBALS['network'];
        //$this->user = & $GLOBALS['user'];
        //$this->cache = & $GLOBALS['cache'];
        //$this->db = & $GLOBALS['db'];
        //$this->title = NULL;
        //$this->html = NULL;
        $this->request = array();
        $this->params = array();
        $this->controllers = $GLOBALS['C']->INCPATH.'controllers/';
        $this->tpl = NULL;
    }

    public function load()
    {
        $this->_parse_input();

        //如果没登陆，以游客身份访问

        $this->_set_template();
        $this->_send_headers();
        $this->_load_controller();
    }

    private function _parse_input()
    {
        $request = $_GET;

        if(count($request) == 0)
        {
            $this->request['c'] = 'home';
            $this->request['m'] = 'index';
            return;
        }

        $this->request['c'] = isset($request['c']) && !empty($request['c']) ? $request['c'] : 'home';
        $this->request['m'] = isset($request['m']) && !empty($request['m']) ? $request['m'] : 'index';

        unset($request['c'], $request['m']);
        $this->params = $request;

        //if(!file_exists($this->controllers.implode('_', $this->request).'.php'))
        if( !file_exists($this->controllers.$this->request['c'].'.php') )
        {
            $this->request = array();
            $this->request['c'] = 'home';
            $this->request['m'] = 'index';
        }
    }

    private function _send_headers()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s'). ' GMT');

        if( get_request_method() == 'AJAX' ) {
            //if( $this->param('ajaxtp') == 'xml' ) {
              //  header('Content-type: application/xml; charset=utf-8');
            //}
            //else {
                header('Content-type: text/plain; charset=utf-8');
            //}
        }
        else {
            header('Content-type: text/html; charset=utf-8');
        }
    }

    private function _set_template()
    {
        require_once($GLOBALS['C']->INCPATH.'classes/ThirdParty/smarty2.67/Smarty.class.php');
        $this->tpl = new Smarty;
        $this->tpl->template_dir = $GLOBALS['C']->INCPATH.'../themes/'.$GLOBALS['C']->THEME.'/';
        $this->tpl->compile_dir = $GLOBALS['C']->TMP_DIR;
        $this->tpl->left_delimiter = '<{';
        $this->tpl->right_delimiter = '}>';
    }

    private function _load_controller()
    {
        //以下变量已迁移到class_controller
        //$db = & $this->db;
        //$cache = & $this->cache;
        //$user = & $this->user;
        //$network = & $this->network;

        $GLOBALS['tpl'] = & $this->tpl;


        //require_once( $this->controllers.implode('_', $this->request).'.php' );
        require_once($this->controllers.$this->request['c'].'.php');
        $controller = new $this->request['c'];
        if( !$controller instanceof controller)
        {
            throw new Exception( "Class '{$this->request['c']}' does not extends Controller class!" );
        }
        $controller->execute($this->request['m'], $this->params);
    }

    public function redirect($loc, $abs=FALSE)
    {
        global $C;
        if( ! $abs && preg_match('/^http(s)?\:\/\//', $loc) ) {
            $abs	= TRUE;
        }
        if( ! $abs ) {
            if( $loc{0} != '/' ) {
                $loc	= $C->SITE_URL.$loc;
            }
        }
        if( ! headers_sent() ) {
            header('Location: '.$loc);
        }
        echo '<meta http-equiv="refresh" content="0;url='.$loc.'" />';
        echo '<script type="text/javascript"> self.location = "'.$loc.'"; </script>';
        exit;
    }

    public function set_lasturl($url='')
    {
        if( ! empty($url) ) {
            $_SESSION['LAST_URL']	= $url;
        }
        else {
            $_SESSION['LAST_URL']	= 'http://'.$_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI'];
        }
        $_SESSION['LAST_URL']	= rtrim($_SESSION['LAST_URL'], '/');
    }
    public function get_lasturl()
    {
        return isset($_SESSION['LAST_URL']) ? $_SESSION['LAST_URL'] : '/';
    }
}