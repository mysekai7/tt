<?php
class template
{

    public $template_dir;       //模板路径
    private $vars = array();    //模板变量

    public function assign($name, $value=null)
    {
        if(is_array($name))
        {
            array_merge($this->vars, $name);
        }
        else
        {
            $this->vars[$name] = $value;
        }
    }

    private function includeTemplate($file)
    {
        if(file_exists($this->template_dir.$file))
        {
            //加上缓冲 ob_start
            extract($this->vars, EXTR_SKIP);    //把数组中的键名作为变量输出
            include $this->template_dir.$file;
            return true;
        }
        return false;
    }

    public function display($file)
    {
        return $this->includeTemplate($file);
    }

}

?>