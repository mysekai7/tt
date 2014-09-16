<?php

class network
{
    public $id;
    public $info;

    public function __construct()
    {
        $this->id = FALSE;
        $this->C = new stdClass;
        $this->info = new stdClass;
        $this->cache = & $GLOBALS['cache'];
        $this->db = & $GLOBALS['db'];
    }

    public function load()
    {
        if($this->id)
        {
            return FALSE;
        }

        $this->load_network_setting();
        $this->info	= (object) array(
            'id'	=> 1,
        );

        $this->is_private = FALSE;
        $this->is_public = TRUE;
        $this->id = $this->info->id;
        return $this->id;
    }

    public function load_network_setting()
    {
        $r = $this->db->query("SELECT * FROM setting", FALSE);
        while($obj = $this->db->fetch_object($r))
        {
            $this->C->{$obj->name} = $obj->value;
        }

        global $C;
        if( !isset($C->SITE_TITLE) || empty($C->SITE_TITLE) )
        {
            $C->SITE_TITLE = 'Sample';
        }
    }

    public function get_user_by_username($uname, $force_refresh=FALSE, $return_id=FALSE)
    {
        if(!$this->id)
        {
            return FALSE;
        }
        if(empty($uname))
        {
            return FALSE;
        }

        $cachekey = 'n:'.$this->id.',username:'.strtolower($uname);
        $uid = $this->cache->get($cachekey);
        if( FALSE != $uid && TRUE != $force_refresh )
        {
            return $return_id ? $uid : $this->get_user_by_id($uid);
        }
        $uid = FALSE;
        $r = $this->db->query('SELECT id FROM sk_user WHERE username="'.$this->db->e($uname).'" AND active=1 LIMIT 1', FALSE);
        if($o = $this->db->fetch_object($r))
        {
            $uid = intval($o->id);
            $this->cache->set($cachekey, $uid, $GLOBALS['C']->CACHE_EXPIRE);
            return $return_id ? $uid : $this->get_user_by_id($uid);
        }

        $this->cache->del($cachekey);
        return FALSE;
    }

    public function get_user_by_email($email, $force_refresh=FALSE, $return_id=FALSE)
    {
        if(!$this->id)
        {
            return FALSE;
        }
        if(!is_valid_email($email))
        {
            return FALSE;
        }

        $cachekey = 'n:'.$this->id.'usermail:'.strtolower($email);
        $uid = $this->cache->get($cachekey);
        if(FALSE != $uid && TRUE != $force_refresh)
        {
            return $return_id ? $uid : $this->get_user_by_id($uid);
        }
        $uid = FALSE;
        $r = $this->db->query('SELECT id FROM sk_user WHERE email="'.$this->db->e($email).'" AND active=1 LIMIT 1', FALSE);
        if($o = $this->db->fetch_object($r))
        {
            $uid = intval($o->id);
            $this->cache->set($cachekey, $uid, $BLOBALS['C']->CACHE_EXPIRE);
            return $return_id ? $uid : $this->get_user_by_id($uid);
        }
        $this->cache->del($cachekey);
        return FALSE;
    }

    public function get_user_by_id($uid, $force_refresh=FALSE)
    {
        if(!$this->id)
        {
            return FALSE;
        }
        $uid = intval($uid);
        if(0 == $uid)
        {
            return FALSE;
        }
        static $loaded = array();
        $cachekey = 'n:'.$this->id.',userid:'.$uid;
        if(isset($loaded[$cachekey]) && TRUE!=$force_refresh)
        {
            return $loaded[$cachekey];
        }
        $data = $this->cache->get($cachekey);
        if(FALSE != $data && TRUE!=$force_refresh)
        {
            $loaded[$cachekey] = $data;
            return $data;
        }
        $r = $this->db->query('SELECT * FROM sk_user WHERE id="'.$uid.'" LIMIT 1', FALSE);
        if($o = $this->db->fetch_object($r))
        {
            $o->active = intval($o->active);
            //o->about_me = $o->about_me;
            if(empty($o->photo))
            {
                $o->photo = get_avatar($o->email, 32);
            }
            $this->cache->set($cache, $o, $GLOBALS[C]->CACHE_EXPIRE);
            $loaded[$cachekey] = $o;
            return $o;
        }
        $this->cache->del($cachekey);
        return FALSE;
    }

    public function get_mostactive_users($force_refresh=FALSE)
    {
    }

    public function get_latest_users($force_refresh=FALSE)
    {
    }

    public function get_online_users($force_refresh=FALSE)
    {
    }

    public function get_group_by_name($gname, $force_refresh=FALSE, $return_id=FALSE)
    {
    }

    public function get_group_by_id($gid, $force_refresh=FALSE)
    {
    }

    public function get_group_members($gid, $force_refresh=FALSE)
    {
    }

    public function get_last_post_id()
    {
        if(!$this->id)
        {
            return 0;
        }
    }

    public function get_recent_posttags()
    {
    }

    public function get_last_comments_id()
    {
    }

}



?>