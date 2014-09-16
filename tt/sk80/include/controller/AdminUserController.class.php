<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminUserController extends Controller
{   
    public function __construct()
    {
        AuthUser::load();
        if(!AuthUser::isLoggedIn()) {
            echo 'Please Login';
            header('location:index.php?job=login');
        }
    }

    public function indexAction()
    {
        $result = User::findAll();
    }
}

?>
