<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class User extends Record
{
    const TABLE_NAME='user';
    
    public $id;
    public $username;
    public $password;

    //当实例化时 根据参数创建对象
    public function __construct($data=false, $value=false)
    {
        parent::__construct($data);

        if(property_exists($this, $data))   //判断$data是否为该类的成员属性
            $this->findBy($data, $value);
    }

    public static function findAll()
    {
        return Record::findAllFrom('User');
    }

    public function findBy($column, $value)
    {
        //$where = "{$column} = '{$value}'";
        $where = $column . "='?'";
        $userinfo = Record::findOneFrom('User', $where, array($value));
        if($userinfo)
            $this->setFromData($userinfo);
    }

    public function getPermissions()
    {
        return 'administrator';
    }
}
?>
