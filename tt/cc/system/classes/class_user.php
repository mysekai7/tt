<?php
class user
{
    public $id;
    public $network;
    public $is_logged;
    public $info;
    public $sess;

    public function __construct()
    {
        $this->id = FALSE;
        $this->network = &$GLOBALS['network'];
        $this->cache = &$GLOBALS['cache'];
        $this->db = &$GLOBALS['db'];
        $this->info = new stdClass;
        $this->is_logged = FALSE;
        $this->sess = array();
    }

    public function load()
    {
		if( !$this->network->id )
		{
			return FALSE;
		}

		global $C;
		$this->_session_start();
		if( isset($this->sess['IS_LOGGED'], $this->sess['LOGGED_USER']) && $this->sess['IS_LOGGED'] && $this->sess['LOGGED_USER'] )
		{
			$u = & $this->sess['LOGGED_USER'];
			$u = $this->network->get_user_by_id($u->id);
			if(!$u)
			{
				return FALSE;
			}
			if($this->network->id && $this->network->id == $u->network_id)
			{
				$this->logged = TRUE;
				$this->info = &$u;
				$this->id = $this->info->id;
				$this->db->query('UPDATE sk_user SET lastclick_date="'.time().'" WHERE id="'.$this->id.'" LIMIT 1');

				if($this->info->active == 0)
				{
					$this->logout();
					return FALSE;
				}
				return $this->id;
			}
		}

		if($this->try_autologin())
		{
			$this->load();
		}
		return FALSE;
    }

    private function _session_start()
    {
		if(!$this->network->id)
		{
			return FALSE;
		}
		if(!isset($_SESSION['NETWORKS_USER_DATA']))
		{
			$_SESSION['NETWORKS_USER_DATA'] = array();
		}
		if(!isset($_SESSION['NETWORKS_USER_DATA'][$this->network->id]))
		{
			$_SESSION['NETWORKS_USER_DATA'][$this->network->id] = array();
		}
		$this->sess = &$_SESSION['NETWORKS_USER_DATA'][$this->network->id];
    }

    public function login($login, $pass, $rememberme=FALSE)
    {
		global $C;
		if(!$this->network->id)
		{
			return FALSE;
		}
		if($this->is_logged)
		{
			return FALSE;
		}
		if(empty($login))
		{
			return FALSE;
		}
		$login = $this->db->escape($login);
		$pass = $this->db->escape($pass);
		$this->db->query('SELECT id FROM sk_users WHERE (email="'.$login.'" OR username="'.$login.'") AND password="'.$pass.'" AND active=1 LIMIT 1');
		if(!$obj=$this->db->fetch_object())
		{
			return FALSE;
		}
		$this->info = $this->network->get_user_by_id($obj->id, TRUE);
		if(!$this->info)
		{
			return FALSE;
		}
		$this->is_logged = TRUE;
		$this->sess['IS_LOGGED'] = TRUE;
		$this->sess['LOGGED_USER'] = &$this->info;
		$this->id = $this->info->id;

		$ip = $this->db->escape(ip2long(get_ip()));
		$this->db->query('UPDATE sk_user SET lastlogin_date="'.time().'", lastlogin_ip="'.$ip.'", lastclick_date="'.time().'" WHERE id="'.$this->id.'" LIMIT 1');
		if(TRUE == $rememberme)
		{
			$tmp = $this->id.'_'.md5($this->info->username.'~~'.$this->info->password.'~~'.$_SERVER['HTTP_USER_AGENT']);
			setcookie('rememberme', $tmp, time()+60*24*60*60, '/', cookie_domain());
		}
		return TRUE;
    }

    public function try_autologin()
    {
		if(!$this->network->id)
		{
			return FALSE;
		}
		if($this->is_logged)
		{
			return FALSE;
		}
		if(!isset($_COOKIE['rememberme']))
		{
			return FALSE;
		}
		$tmp = explode('_', $_COOKIE['rememberme']);
		$this->db->query('SELECT username, password, email FROM sk_user WHERE id="'.intval($tmp[0]).'" AND active=1 LIMIT 1');
		if(!$obj = $this->db->fetch_object())
		{
			return FALSE;
		}
		if( $tmp[1] == md5($obj->username.'~~'.$obj->password.'~~'.$_SERVER['HTTP_USER_AGENT']) ) {
			return $this->login($obj->username, $obj->password, TRUE);
		}
		setcookie('rememberme', NULL, time()+30*24*60*60, '/', cookie_domain());
		$_COOKIE['rememberme']	= NULL;
		return FALSE;
    }

    public function logout()
    {
		if( ! $this->is_logged ) {
			return FALSE;
		}
		setcookie('rememberme', NULL, time()+60*24*60*60, '/', cookie_domain());
		$_COOKIE['rememberme']	= NULL;
		$this->sess['IS_LOGGED']	= FALSE;
		$this->sess['LOGGED_USER']	= NULL;
		unset($this->sess['IS_LOGGED']);
		unset($this->sess['LOGGED_USER']);
		$this->id	= FALSE;
		$this->info	= new stdClass;
		$this->is_logged	= FALSE;
    }
}

?>
