<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Tag extends Record
{
    const TABLE_NAME = 'tag';

    public $id;
    public $name;
    public $count;
    public $hit;
    
    //只有在创建和获得单个ID数据是需要实例化
    public function __construct($data=false, $value=false)
    {
        parent::__construct($data);

        if(is_string($data) && property_exists($this, $data))   //判断$data是否为该类的成员属性
            $this->findBy($data, $value);
    }

    public function findBy($column, $value)
    {
        //$where = "{$column} = '{$value}'";
        //$where = "id='?'";
        $where = "{$column} = '?'";
        $tag_info = Record::findOneFrom('Tag', $where, array($value));
        if($tag_info)
            $this->setFromData($tag_info);
    }
    
    public static function findAll($limit = null)
    {
        $where = 'AND count > 0';
        if(isset($limit))
            $where .= " order by count desc LIMIT $limit ";
        return Record::findAllFrom('Tag', $where);
    }

    public static function deleteId($id)
    {
       
       $sql = "select b.id from ".TABLE_PREFIX."content_tag as a left join ".TABLE_PREFIX."tag as b on a.tag_id=b.id where a.content_id = $id";
       self::$__CONN__->query($sql);
       $result = self::$__CONN__->last_result;

       if(is_array($result) && count($result)>0) {
            foreach($result as $v)
            {
                $ids[] = $v->id;
            }

            $sql = "update ".TABLE_PREFIX."tag set count = count - 1 where id in (".join(',',$ids).")";
            self::$__CONN__->query($sql);

            $sql = "delete from ".TABLE_PREFIX."content_tag where content_id='{$id}'";
            self::$__CONN__->query($sql);
       }
    }

    public static function findById($id)
    {
        $tags = array();
        $sql = "select a.name from ".TABLE_PREFIX."content_tag as b left join ".TABLE_PREFIX."tag as a ON b.tag_id=a.id where b.content_id = '$id'";
        //echo $sql;
        //$this->db->query($sql);
        self::$__CONN__->query($sql);
        $result_tags = self::$__CONN__->last_result;
        if(count($result_tags) >0)
        {
            foreach($result_tags as $k => $v)
            {
               if (!empty($v->name)) {
                $tags[] = $v->name;
               }
            }
        }
        return $tags;
    }
}
?>
