<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Category extends Record
{
    const TABLE_NAME = 'category';

    public $id;
    public $pid;
    public $name;
    public $slug;
    public $path;
    public $count;

    //只有在创建和获得单个ID数据是需要实例化
    public function __construct($data=false, $value=false)
    {
        parent::__construct($data);

        if(is_string($data) && property_exists($this, $data))   //判断$data是否为该类的成员属性
            $this->findBy($data, $value);
    }

    public static function find()   //带文章数量统计的
    {
        
    }

    public static function findAll($arg=false)
    {
        $where = $arg ? 'AND '.$arg.' ORDER BY path' : 'ORDER BY path';
        //$where = 'ORDER BY path';
        return Record::findAllFrom('Category', $where);
    }
    
    public function findBy($column, $value)
    {
        //$where = "{$column} = '{$value}'";
        //$where = "id='?'";
        $where = "{$column} = '?'";
        $category_info = Record::findOneFrom('Category', $where, array($value));
        if($category_info)
            $this->setFromData($category_info);
    }

    public static function deleteId($id)
    {
        return Record::delete('Category', (int)$id);
    }

    public static function childrenOf($id)
    {
        //return self::find(array('where' => 'parent_id='.$id, 'order' => 'position, page.created_on DESC'));
    }

    public static function hasChildren($id)
    {
        //return (boolean) self::countFrom('Page', 'parent_id = '.(int)$id);
    }

    public function hasContent($part, $inherit=false) {
//        if ( isset($this->part->$part) ) {
//            return true;
//        }
//        else if ( $inherit && $this->parent ) {
//                return $this->parent->hasContent($part, true);
//            }
    }

    public function contentCount()
    {
        
    }

    public static function deleteChildrenOf($id)
    {
//        $id = (int)$id;
//
//        if(self::hasChildren($id)) {
//            $children = self::childrenOf($id);
//            if(is_array($children)) {
//                foreach ($children as $child) {
//                    if (!$child->delete()) {
//                        return false;
//                    }
//                }
//            }
//            // because Page::childrenOf return directly an object when there is only 1 child...
//            elseif ($children instanceof Page) {
//                if (!$children->delete()) {
//                    return false;
//                }
//            }
//        }
//
//        return true;
    }

    public static function cloneTree($page, $parent_id)
    {
//        /* This will hold new id of root of cloned tree. */
//        static $new_root_id = false;
//
//        /* Clone passed in page. */
//        $clone = Record::findByIdFrom('Page', $page->id);
//        $clone->parent_id = (int)$parent_id;
//        $clone->id = null;
//        $clone->title .= " (copy)";
//        $clone->slug .= "-copy";
//        $clone->save();
//
//        /* Also clone the page parts. */
//        $page_part = PagePart::findByPageId($page->id);
//        if (count($page_part)) {
//            foreach ($page_part as $part) {
//                $part->page_id = $clone->id;
//                $part->id = null;
//                $part->save();
//            }
//        }
//
//        /* Also clone the page tags. */
//        $page_tags = $page->getTags();
//        if (count($page_tags)) {
//           foreach($page_tags as $tag_id => $tag_name) {
//              // create the relation between the page and the tag
//              $tag = new PageTag(array('page_id' => $clone->id, 'tag_id' => $tag_id));
//              $tag->save();
//           }
//        }
//
//        /* This gets set only once even when called recursively. */
//        if (!$new_root_id) {
//            $new_root_id = $clone->id;
//        }
//
//        /* Clone and update childrens parent_id to clones new id. */
//        if (Page::hasChildren($page->id)) {
//            foreach (Page::childrenOf($page->id) as $child) {
//                Page::cloneTree($child, $clone->id);
//            }
//        }
//
//        return $new_root_id;
    }
}

?>
