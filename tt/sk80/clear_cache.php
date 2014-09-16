<?php

/*
 *  配合linux crontab使用
 *  该脚本负责生成最新静态文件，清除过期缓存
 */



/*
//清除首页缓存
$index_cache = "/home/wangchao/www/index.html";
if(file_exists($index_cache))
    @unlink($index_cache);

//清除sidebar缓存
$cache_path = "/home/wangchao/www/data/cache/";
if ($handle = opendir($cache_path)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            //$files[] = $file;
            @unlink($cache_path.$file);
        }
    }
    closedir($handle);
}

//清除post
$post_path = "/home/wangchao/www/post/";
if ($handle = opendir($post_path)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            //$files[] = $file;
            @unlink($post_path.$file);
        }
    }
    closedir($handle);
}


print("clear end.\n");
*/

define('ROOT', dirname(__FILE__).'/');
require_once(ROOT.'include/common.php');

$app = new Application;

class c_cache
{
    public $recent_post;
    public $categories;
    public $tags_list;

    public $cahce_categories;   //作为处理后的分类
    public $cahce_tags;   //作为处理后的tags

    public function __construct()
    {

/*
        $cache = new FileCache();
        $cache->cachePath = DATA_DIR.'cache/';

        //侧栏分类
        $categories = $cache->get('categories');
        if($categories === false) {
            $categories = array();
            //获得所有分类
            $categories[0] = Category::findAll();
            if(is_array($categories[0]) && count($categories[0]) > 0) {
                foreach($categories[0] as $k => $v) {
                    //$this->categories[$v->id] = get_object_vars($v);
                    $categories[1][$v->id] = $v;
                    $categories[1][$v->slug] = $v;
                }
            }

            $cache->set('categories', $categories, 7200);//缓存2小时
            del_cache();
        }

        //Tags 热词
        $hot_tags = $cache->get('hot_tags');
        if($hot_tags === false) {
            $hot_tags = Tag::findAll(30);
            $cache->set('hot_tags', $hot_tags, 7200);
        }
*/

        //--------------------------------
        //首页最近文章
        $this->recent_post = Article::getPost(5, true);

        //侧栏分类
        $this->categories = Category::findAll();
        if(is_array($this->categories) && count($this->categories) > 0) {
            $temp = array();
            foreach($this->categories as $k => $v) {
                $temp[$v->id] = get_object_vars($v);
            }
            $this->cahce_categories = $temp;
            $temp = array();
        }


        //Tags 后期改进为热词形式
        $this->tags_list = Tag::findAll(30);

        $tags = Tag::findAll();

        $content_tag = Record::findAllFrom('ContentTag');

        //遍历所有tag 组合出方便调用的形式
        if(is_array($tags) && count($tags)>0) {
            $temp_tags = array();
            foreach($tags as $k => $v) {
                $temp_tags[$v->id] = $v->name;
            }
        }

       //遍历关系表
        if(is_array($content_tag) && count($content_tag)>0) {
            $this->cahce_tags = array();
            foreach($content_tag as $k=>$v) {
                if(isset($temp_tags[$v->tag_id])) {
                    $this->cahce_tags[$v->content_id][] = $temp_tags[$v->tag_id];
                }
            }
        }

        //清空临时数据
        $tags = $content_tag = $temp_tags = array();
    }


    public function create()
    {
        global $tpl;
        set_time_limit(100);


        //删除data/chache目录
        $path = DATA_DIR.'cache/'; //缓存目录
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    //$files[] = $file;
                    @unlink($path.$file);
                }
            }
            closedir($handle);
        }


        //删除首页
        del_cache();

        //文章
        $total = Cache::getTotal();

        $start = 0;
        $limit = 20;
        $start_id  = 0;
        $i = 0;
        $tablename_content = Record::tableFromClass('Article');
        $tablename_contentpart = Record::tableFromClass('ContentPart');

        $previous = null;
        while($i < $total) {
            $sql = "select * from $tablename_content where type='post' and id > $start_id order by id asc limit $limit";
            Record::$__CONN__->query($sql);
            $articles = array();
            $articles = Record::$__CONN__->last_result;
            if(is_array($articles) && count($articles) > 0) {
                foreach($articles as $k => $data) {
                    $article = new Article(get_object_vars($data));
                    //上一页
                    $previous = isset($previous) ? $previous : '';
                    $tpl->assign('previous', $previous);
                    $previous = $article;


                    //下一页
                    if(isset($articles[$k+1])) {
                        //$next = new Article(get_object_vars($articles[$k+1]));
                        $next = $articles[$k+1];
                    } else {
                        $next = $article->next();
                    }

                    $tpl->assign('next', $next);


                    $content_part = ContentPart::findById($article->id);
                    $article->content = processContent($content_part->content);
                    $article->tags = isset($this->cahce_tags[$article->id]) ? $this->cahce_tags[$article->id] : array();
                    $article->category = isset($this->cahce_categories[$article->cid]) ? $this->cahce_categories[$article->cid]['name'] : '无分类';
                    $article->category_slug = isset($this->cahce_categories[$article->cid]) ? $this->cahce_categories[$article->cid]['slug'] : 'uncategory';
                    $this->createHtml($article);

                    if($k == ($limit - 1))
                        $start_id = $article->id;
                }
            }
            $i+=$limit;
        }//while
        flush();
        echo "create cache over!\n";
    }

    public function createHtml($obj=null)
    {
        global $tpl;
        if(is_object($obj)) {
            //smarty
            $tpl->assign('post', $obj);
            $tpl->assign('recent_post', $this->recent_post);
            $tpl->assign('categories', $this->categories);
            $tpl->assign('tag_list', $this->tags_list);

            $filetpl = SYSTEM_ROOT.'templates/'.DEFAULT_TEMPLATE.'/post.html';
            $path = SYSTEM_ROOT.'post/';
            //$filename = !empty($obj->slug) ? str_replace(' ', '-', trim($obj->slug)) : $obj->id;
            $filename = $obj->id;
            $file = $path.$filename.'.html';
            file_put_contents($file, $tpl->fetch($filetpl));
            @chmod($file, 0777);
        }
    }//end func
}

$c_cache = new c_cache;
$c_cache->create();
