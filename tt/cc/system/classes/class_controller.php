<?php
class controller
{
    public function __construct()
    {
        $this->network = & $GLOBALS['network'];
        $this->user =   & $GLOBALS['user'];
        $this->cache = & $GLOBALS['cache'];
        $this->db = & $GLOBALS['db'];
        $this->tpl = & $GLOBALS['tpl'];
        $this->title = NULL;
        $this->html = NULL;
    }

    public function execute($action, $arguments)
    {
        if( !method_exists($this, $action) )
        {
            throw new Exception( "Action '{$action}' is not valid!" );
        }
        call_user_func(array($this, $action), $arguments);
    }
}