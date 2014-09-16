<?php
class Article extends Record
{
    const TABLE_NAME = 'content';

    public $id;
    public $title;
    public $slug;
    public $keywords;
    public $description;
    public $type;
    public $status;
    public $position;
    public $cid;
    public $created_uid;
    public $updated_uid;
    public $created_date;
    public $updated_date;
    public $comment_count;
    public $is_comment;
    public $password;
    public $content;
    public $tags;
    public $category;
    public $category_slug;

    public function __construct($data=false, $value=false)
    {
        parent::__construct($data);

        if(is_string($data) && property_exists($this, $data))   //判断$data是否为该类的成员属性
            $this->findBy($data, $value);
    }

    //-------------

    //增加sql模式 3种

    public static function find($args=null)
    {
        //收集参数
        $field = isset($args['field']) ? trim($args['field']) : '';
        $where  = isset($args['where']) ? trim($args['where']) : '';//改进& where 1
        $order_by = isset($args['order']) ? trim($args['order']) : '';
        $offset = isset($args['offset']) ? (int)$args['offset'] : 0;
        $limit = isset($args['limit']) ? (int)$args['limit'] : 0;
        $page_result = isset($args['page_result']) ? 1 : 0;

        //处理请求部分
        $sField = empty($field) ? '*' : $field;
        $sWhere = empty($where) ? '' : "WHERE $where";
        $sOrder = empty($order_by) ? '' : "ORDER BY $order_by";
        $sLimit = $limit > 0 ? "LIMIT $offset, $limit" : '';

        $tablename = self::tableFromClass('Article');

        $sql = "SELECT $sField FROM $tablename $sWhere $sOrder $sLimit";

        if($page_result) {
            $return = array();
            self::$__CONN__->get_page_results($sql);
            $return['count'] = self::$__CONN__->num_rows_all;
            $return['res'] = self::$__CONN__->last_result;
            return $return;
        } else {
            self::$__CONN__->query($sql);
            if($limit == 1)
                return self::$__CONN__->last_result[0];
            return self::$__CONN__->last_result;
        }
    }

    public static function getPost($limit = 1, $rand=false)
    {
        $order = $rand == true ? 'rand()' : 'created_date desc';
        if($limit > 1){
            $args = array('field' => 'id, title', 'order'=>$order, 'limit'=>$limit, 'where'=>"status='publish'");
        } else {
            $args = array('order'=>'created_date desc, position desc', 'limit'=>'1', 'where'=>"status='publish'");
        }
        return self::find($args);
    }
    
    public static function listPost($page)
    {
        return self::find(array(
            'where' => "status='publish'",
            'limit' => $page->PerPage,
            'offset' => $page->getBegin(),
            'order' => 'created_date DESC',
            'page_result' => '1'
        ));
    }

    public static function findByCid($cid, $page)
    {
        return self::find(array(
            'where' => "status='publish' AND cid=".(int)$cid,
            'limit' => $page->PerPage,
            'offset' => $page->getBegin(),
            'order' => 'created_date DESC',
            'page_result' => '1'
        ));
    }

    public static function findByTag($tag_name, $page)
    {
        $start = $page->getBegin();
        $limit = $page->PerPage;
        $tag = new Tag('name', $tag_name);
        $tablename = self::tableFromClass('Article');
        $tablename_tag = self::tableFromClass('ContentTag');
        $sql = "SELECT article.* FROM $tablename AS article LEFT JOIN $tablename_tag AS content_tag ON article.id=content_tag.content_id WHERE article.status='publish' AND content_tag.tag_id=".(int)$tag->id." ORDER BY created_date DESC LIMIT $start, $limit";
        //self::$__CONN__->query($sql);
        //return self::$__CONN__->last_result;
        $return = array();
        self::$__CONN__->get_page_results($sql);
        $return['count'] = self::$__CONN__->num_rows_all;
        $return['res'] = self::$__CONN__->last_result;
        return $return;
    }


    //改进函数
//    public static function findById($id) {
//        return self::find(array(
//        'where' => self::tableNameFromClassName('User').'.id='.(int)$id,
//        'limit' => 1
//        ));
//    }
    //-----------------------

    /*
    public static function findAll($arg=false)
    {
        $where = $arg ? 'AND '.$arg.' ORDER BY created_date DESC' : 'ORDER BY created_date DESC';
        //$where = 'ORDER BY path';
        return Record::findAllFrom('Article', $where);
    }
     *
     */
    public static function findAll($page, $args=null)
    {
        $where = isset($args['cid']) && $args['cid'] ? 'cid='.$args['cid'] : '';
        return self::find(array(
            'where' => $where,
            'limit' => $page->PerPage,
            'offset' => $page->getBegin(),
            'order' => 'created_date DESC',
            'page_result' => '1'
        ));
    }

    public function findBy($column, $value)
    {
        //$where = "{$column} = '{$value}'";
        //$where = "id='?'";
        $where = "{$column} = '?'";
        $article_info = Record::findOneFrom('Article', $where, array($value));
        if($article_info)
            $this->setFromData($article_info);
        return $article_info;
    }

    public static function deleteId($id)
    {
        return Record::delete('Article', (int)$id);
    }

    public static function deleteIds($ids)
    {
        return Record::deleteBatch('Article', $ids);
    }

    public static function moveIds($ids, $cid)
    {
        $tablename = self::tableFromClass('Article');
        $sql = "UPDATE $tablename SET cid={$cid} WHERE id IN(".join(',', $ids).")";
        return self::$__CONN__->query($sql);
    }

    public function previous() {
        if ($this->id)
            return $this->find(array(
            'field' => 'id, title',
            'limit' => 1,
            'where' => "status='publish' AND id < ". $this->id,
            'order' => 'created_date DESC'
            ));
    }

    public function next() {
        if ($this->id)
            return $this->find(array(
            'field' => 'id, title',
            'limit' => 1,
            'where' => "status='publish' AND id >" . $this->id,
            'order' => 'created_date ASC'
            ));
    }

    private function _loadTags() {
//        global $__CMS_CONN__;
//        $this->tags = array();
//
//        $sql = "SELECT tag.id AS id, tag.name AS tag FROM ".TABLE_PREFIX."page_tag AS page_tag, ".TABLE_PREFIX."tag AS tag ".
//            "WHERE page_tag.page_id={$this->id} AND page_tag.tag_id = tag.id";
//
//        if ( ! $stmt = $__CMS_CONN__->prepare($sql))
//            return;
//
//        $stmt->execute();
//
//        // Run!
//        while ($object = $stmt->fetchObject())
//            $this->tags[$object->id] = $object->tag;
    }

    public function tags() {
//        if ( ! $this->tags)
//            $this->_loadTags();
//
//        return $this->tags;
    }

    public function getTags()
    {
        $tags = array();
        $sql = "select a.name from ".TABLE_PREFIX."content_tag as b left join ".TABLE_PREFIX."tag as a ON b.tag_id=a.id where b.content_id = '$this->id'";
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
        //$this->tags = join(',', $tags);
        return $tags;
    }

    public function saveTags($tags)
    {
        if (is_string(trim($tags)))
        {
            $pattern = "/[\~@#\$%\^&\*\.\(\)\[\]\{\}<>\?'\"\\\\\/]+/";//允许中杠和下划线
            $replace = '';
            $tags = preg_replace($pattern, $replace, $tags);
            $tags = str_replace(array(',', ' ', '，'), ',', $tags);
            //$this->tags = $tags;

            $tags = explode(',', $tags);
            //$this->tags = $tags;
        }

        $tags = array_map('trim', $tags);
        $tags = array_unique($tags);
        foreach($tags as $k=>$v) {
            if(empty($v))
                unset($tags[$k]);
        }
        $this->tags = $tags;

        $current_tags = $this->getTags();   //db_tags
        //$this->tags = join(',', $tags);

        if(count($tags) == 0 && count($current_tags) == 0)
            return;

        if(count($tags) == 0)
        {
            //这里把所有的tags数值递减
            $tag_ids = array();
            foreach($current_tags as $v)
            {
                $tag = self::findOneFrom('Tag', "name = '?'", array($v));
                $tag_ids[] = $tag->id;
            }
            $current_tags_id = join(',', $tag_ids);

            //tags count - 1
            $sql = "update ".TABLE_PREFIX."tag set count = count -1 where id IN ( $current_tags_id )";
            self::$__CONN__->query($sql);

            $sql = "delete from ".TABLE_PREFIX."content_tag where content_id = $this->id and tag_id IN ($current_tags_id)";
            self::$__CONN__->query($sql);
        }
        else
        {
            $new_tags = array();
            $old_tags = array();
            $exist_tags = array();
            $exist_tags_id = array();
            $noexist_tags = array();

            $new_tags = array_diff($tags, $current_tags);
            $old_tags = array_diff($current_tags, $tags);

            if(count($new_tags) > 0)
            {
                $relationship_val = array();
                foreach($new_tags as $k => $v)
                {
                    if(!empty($v))
                    {
                        $tag =self::findOneFrom('Tag', "name='?'", array($v));
                        if($tag)
                        {
                            $exist_tags_id[$k] = $tag->id; //已存在的tag_id集合
                            $exist_tags[$k] = $v;  //已存在的tag_name集合
                            $relationship_val[] = "($this->id, $tag->id)";
                        }
                        else
                        {
                            $noexist_tags[] = $v;
                        }
                    }
                }

                //update
                if(count($exist_tags) > 0)
                {
                    $tags_id = array();
                    $tags_id = implode(',', $exist_tags_id);
                    $sql = "update ".TABLE_PREFIX."tag set count = count + 1 where id IN ($tags_id)";
                    self::$__CONN__->query($sql);

                    //insert
                    $relationship_vals = join(',', $relationship_val);
                    $sql = "insert into ".TABLE_PREFIX."content_tag (content_id, tag_id) values $relationship_vals";
                    self::$__CONN__->query($sql);
                }

                //insert tags
                if(count($noexist_tags) > 0)
                {
                    $relationship_values = array();
                    foreach($noexist_tags as $v)
                    {
                        $tag = new Tag(array('name'=>$v));
                        $tag->save();
                        $relationship_values[] = "($this->id, $tag->id)";
                    }
                    $sql = "insert into ".TABLE_PREFIX."content_tag (content_id, tag_id) values ".join(',', $relationship_values);
                    self::$__CONN__->query($sql);
                }
            }

            //remove old_tags
            if(count($old_tags) > 0)
            {
                $old_tags_id = array();
                foreach($old_tags as $v)
                {
                    if(!empty($v))
                    {
                        $tag =self::findOneFrom('Tag', "name='?'", array($v));
                        //$sql = "select itemid from item where name = '$v' and type = 'tag' limit 1";
                        //$this->db->query($sql);
                        $old_tags_id[] = $tag->id;
                    }
                }
                $old_tags_ids = join(',', $old_tags_id);

                //tags count - 1
                $sql = "update ".TABLE_PREFIX."tag set count = count - 1 where id IN ( $old_tags_ids )";
                self::$__CONN__->query($sql);

                $sql = "delete from ".TABLE_PREFIX."content_tag where content_id = '$this->id' and tag_id IN ($old_tags_ids)";
                self::$__CONN__->query($sql);
            }
        }
    }//end func

    public function url() {
        ////return BASE_URL . $this->url . ($this->url != '' ? URL_SUFFIX: '');
    }

    public function keywords() { return $this->keywords; }
    public function description() { return $this->description; }
    public function breadcrumb() { return $this->breadcrumb; }
}
?>