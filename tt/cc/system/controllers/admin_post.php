<?php
class admin_post extends controller
{

    public function __construct()
    {
        parent::__construct();

        if(!$this->user->is_logged)
        {
            //echo '请先登录';
            //exit;
        }


    }

    public function index()
    {
        $this->list_post();
    }

    public function addpost()
    {
        $this->tpl->display('html/admin_addpost.html');
    }

    public function listpost()
    {
        $this->tpl->display('html/admin_listpost.html');
    }
}