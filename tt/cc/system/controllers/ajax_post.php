<?php
class ajax_post extends controller
{
    public function __construct()
    {
        parent::__construct();

        if(get_request_method() != 'AJAX')
        {
            die('error request');
        }
    }

    public function index()
    {
    }

    public function save()
    {
        $arr = $_POST['post'];

        //print_r($arr);
        //sleep(3);
        echo json_encode($arr);
        //echo get_request_method();
    }
}