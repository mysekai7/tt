<?php

/**
 * Description of class_cache_filesystem
 *
 * @author NINETOWNS
 */
class cache_filesystem
{
    private $path;
    private $keys_prefix;

    public function __construct()
    {
        global $C;
        $this->path = $C->CACHE_FILESYSTEM_PATH;
        $this->keys_prefix = $C->CACHE_KEYS_PREFIX;
    }

    private function find_filename($key)
    {
        return $this->path.'/'.md5($this->keys_prefix).'-'.md5($key);
    }

    public function get($key)
    {
        $file = find_filename($key);
        $time = microtime(TRUE);
        $res = FALSE;
        if(file_exists($file) && is_readable($file))
        {
            $data = file($file);
            if($data && is_array($data) && count($data) == 2)
            {
                if(intval($data[0]) >= $time)
                {
                    $res = unserialize($data[1]);
                }
            }
            if($res === FALSE)
            {
                $this->del($key);
            }
        }
        return $res;
    }

    public function set($key, $data, $lifetime)
    {
        $file = find_filename($key);
        $time = microtime(TRUE);
        $this->del($key);
        $data =(time() + $lifetime)."\n".serialize($data);
        $res = file_put_contents($file, $data);
        chmod($file, 0777);
        return $res;
    }

    public function del($key)
    {
        $file = $this->find_filename($key);
        if(file_exists($file) && is_writeable($file))
        {
            unlink($file);
        }
    }

    public function garbage_collector()
    {
        $prefix = md5($this->key_prefix).'-';
        $prefixlen = strlen($prefix);
        $time = microtime(TRUE);
        $dir = opendir( $this->path );
        $i = 0;
        while($filename = readdir($dir))
        {
            if($filename == '.' || $filename == '..')
            {
                continue;
            }
            if( substr($filename, 0, $prefixlen) != $prefix )
            {
                continue;
            }
            $file = $this->path.'/'.$filename;
            $fp = fopen($file, 'r');
            $tm = fread($fp, 10);
            fclose($fp);
            if( intval($tm) < time() && is_writeable($file) )
            {
                unlink($file);
                $i++;
            }

        }
        return $i;
    }

}

?>
