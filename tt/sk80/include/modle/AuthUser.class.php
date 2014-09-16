<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AuthUser
{
    const SESSION_KEY = 'simpleblog';
    const COOKIE_KEY = 'simpleblog';
    const COOKIE_LIFE = 1209600; //2周

    protected static $is_logged_in = false;
    protected static $user_id = false;
    protected static $is_admin = false;
    protected static $record = false;
    protected static $permissions = array();

    public static function load()
    {
        global $app;
        //IP判断=====================================
        $onlineip = getIP();
        $ipCity = new IpLocation(INCLUDE_DIR.'ipdata/QQWry.Dat');
        $uCity = $ipCity->getlocation($onlineip);

        try{
            if(strcmp(trim($uCity['country']), '北京市') != 0 )
                throw new Exception('对不起您的IP不符合要求');
        } catch (Exception $e) {
            $app->error($e->getMessage(), SITE_URL);
        }
        //===========================================


        if(isset($_SESSION[self::SESSION_KEY]) && isset($_SESSION[self::SESSION_KEY]['username'])) {
            if(isset($_SESSION[self::SESSION_KEY]['record']))
                $user = $_SESSION[self::SESSION_KEY]['record'];
            else
                $user = new User('username', $_SESSION[self::SESSION_KEY]['username']);
        } else if(isset($_COOKIE[self::COOKIE_KEY])) {
            $user = self::checkCookie($_COOKIE[self::COOKIE_KEY]);
        } else {
            return false;
        }

        if(!$user)
            return self::logout();

        self::setInfos($user);
        return true;
    }

    public static function setInfos(User $user)
    {
        //此处改为 SESSION
        $_SESSION[self::SESSION_KEY]['username'] = $user->username;
        $_SESSION[self::SESSION_KEY]['record'] = $user;
        self::$record = $user;
        self::$is_logged_in = true;

        //$this->permissions = $user->getPermissions();
        //$this->is_admin = $this->hasPermission('administrtor');
    }

    public static function hasPermission($permissions)
    {
        if($permissions == null || $permissions == '')
            return true;

        foreach(explode(',', $permissions) as $permission)
        {
            if(in_array(strtolower($permission), self::$permissions))
                return true;
        }

        return false;
    }

    public static function isLoggedIn()
    {
        return self::$is_logged_in;
    }

    public static function getRecord()
    {
        return self::$record ? self::$record : false;
    }

    public static function getId()
    {
        return self::$record ? self::$record->id : false;
    }

    public static function getUserName()
    {
        return self::$record ? self::$record->username : false;
    }

    public static function getPermission()
    {
        return self::$permissions;
    }

    public static function logout()
    {
        unset($_SESSION[self::SESSION_KEY]);

        self::delCookie();
        self::$record = false;
        self::$user_id = false;
        self::$is_admin = false;
        self::$permissions = array();

    }

    public static function login($username, $password, $set_cookie=false)
    {
        self::logout();

        $user = new User('username', $username);
        /*
        $where = "username = '{$username}'";
        $data = Record::findOneFrom('user', $where);
        $data = get_object_vars($data); //将对象转换为数组
        var_dump($data);
        $user = new User($data);
         * *
         */
        //var_dump($user);

        //if($user instanceof User && $user->password == md5($password))
        if($user instanceof User && !empty($password) && $user->password == $password)   //先不做md5加密
        {
            //$user->last_login = time();
            //$user->save();

            if($set_cookie)
            {
                $time = $_SERVER['REQUEST_TIME'] + self::COOKIE_LIFE;
                set_cookie(self::COOKIE_KEY, self::bakeUserCookie($time, $user), $time, '/');
            }

            self::setInfos($user);

            //echo $user->getPermissions();
            return true;
        }
        return false;

    }

    public static function checkCookie($cookie)
    {
        $params = self::explodeCookie($cookie);
        if (isset($params['exp'], $params['id'], $params['digest'])) {
            if ( !$user = new User('id', $params['id']))
                return false;

            if (self::bakeUserCookie($params['exp'], $user) == $cookie && $params['exp'] > $_SERVER['REQUEST_TIME'])
                return $user;
        }
        return false;
    }

    public static function explodeCookie($cookie)
    {
        $pieces = explode('&', $cookie);
        if (count($pieces) < 2)
            return array();
        foreach ($pieces as $piece) {
            list($key, $value) = explode('=', $piece);
            $params[$key] = $value;
        }
        return $params;

    }

    public static function delCookie()
    {
        setcookie(self::COOKIE_KEY, false, $_SERVER['REQUEST_TIME']-self::COOKIE_LIFE, '/');
    }

    public static function bakeUserCookie($time, $user)
    {
        return 'exp='.$time.'&id='.$user->id.'&digest='.md5($user->username.$user->password);
    }
}

?>
