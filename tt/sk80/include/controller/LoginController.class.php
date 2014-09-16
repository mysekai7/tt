<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class LoginController extends Controller
{  
    function __construct()
    {
        global $tpl, $app;
        $app->refresh();
        
        AuthUser::load();
    }

    function indexAction()
    {
        global $tpl;
        if(AuthUser::isLoggedIn())
        {
            //echo 'You have logged in!';
            header('location:index.php?job=admin_article');
        }
        else
        {
            $tpl->display('admin/login.html');
        }
    }

    function loginAction()
    {
        //如果已经登陆 跳转

        //判断登陆
        if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if(AuthUser::login($_POST['username'], $_POST['password']))
            {
                //监听 记录日志

                //如果有记录URL则跳转
                //跳转到来源页面
                //header('location:index.php?job=admin_article');    //暂时测试用的跳转
                go_redirect('index.php?job=admin_article');

                //echo '<font color=green>Success</font>';
                
            }
            else
            {
                //否则记录用户名
                //提示登陆失败
                //echo '<font color=red>Please try again</font>';
                header('location:index.php?job=login');
            }

            //如果没找到url活密码
            
        }
        
    }//end func

    function logoutAction()
    {
        AuthUser::logout();
        header('location:index.php');
    }
}

?>
