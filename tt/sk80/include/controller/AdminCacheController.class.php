<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminCacheController extends Controller
{
    public $recent_post;
    public $categories;
    public $tags_list;

    public $cahce_categories;   //作为处理后的分类
    public $cahce_tags;   //作为处理后的tags
    
    public function __construct()
    {

        AuthUser::load();
        if(!AuthUser::isLoggedIn()) {
            echo 'Please Login';
            header('location:index.php?job=login');
        }

        //首页最近文章
        //$this->recent_post = Article::getPost(5, true);

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
        $hot_tags = Tag::findAll(20);
        //mprint_r($hot_tags, '$hot_tags');
        if(count($hot_tags) > 0) {
            $first = current($hot_tags);
            $last = end($hot_tags);
            foreach($hot_tags as $k => $v) {
                $tags_list[$k]['word'] = $v->name;
                $tags_list[$k]['size'] = tagClouds($v->count, $first->count, $last->count);
            }
        }
        $this->tags_list = $tags_list;

        $tags = Tag::findAll();
        //var_dump($tags);

        $content_tag = Record::findAllFrom('ContentTag');
        //var_dump($content_tag);

        //关系表中存在的文章ID以及tag集合到一个数组中tag_cache  避免在遍历生成文章静态页时重复读取数据库

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

    public function indexAction()
    {
//        global $tpl;
//        $articles = Article::findAll();
//        if(!is_array($articles) || count($articles)<1)
//            $articles = array();
//        $tpl->assign('articles', $articles);
//
//        $tpl->display('admin/article_list.html');


        //$arr = get_object_vars($this->categories);
        //var_dump($arr);



        echo 'Create Cache !';
        echo '<a href="index.php?job=admin_cache&action=create">GO</a>';
    }

    public function createAction()
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
        echo 'create cache over!';
        echo '<a href="index.php">Index</a>';
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

?>
