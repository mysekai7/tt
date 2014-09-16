<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdminArticleController extends Controller
{
    public function __construct()
    {
        AuthUser::load();
        if(!AuthUser::isLoggedIn()) {
            echo 'Please Login';
            header('location:index.php?job=login');
        }
    }

    public function indexAction($args)
    {
        global $tpl;

        $page = new Page;
        $page->Page = isset($args['page']) ? (int)$args['page'] : 1;
        $page->PerPage = 12;
        $page->Condition = '';
        $page->Url = SITE_URL.'index.php?job=admin_article&action=index';
        if($args['cid']) {
            $page->Url .= '&cid='.(int)$args['cid'];
            $tpl->assign('cid', $args['cid']);
        }
        $page->Url .= '&page=';
//        if(isset($args['status']))
//            $page->Condition .= 'status='.trim($args['status']);

        $result = Article::findAll($page, $args);
        $page->Count = $result['count'];

//        if(!is_array($result['res']) || count($result['res'])<1)
//            $articles = array();

        //mprint_r($result, '$result');
        //var_dump($result['res']);
        $post = $result['res'];
        if(is_array($post) && count($post)>0){
            foreach($post as $key => $val) {
                $post[$key]->res = base64_encode(serialize($val));
            }
        }
        //var_dump($post);

        $categories = Category::findAll();
        $args['debug'] && mprint_r($categories, '$categories');
        $tpl->assign('categories', $categories);
        $tpl->assign('articles', $post);
        $tpl->assign('page_nav', $page->getPage());
        $tpl->display('admin/article_list.html');
    }

    public function addAction()
    {
        global $tpl;

        //如果是分表则通过$_POST['content'], $_POST['content_part']区分

        //$data = isset($_POST['article']) ? $_POST['article'] : array();
        $data = array();
        if(isset($_POST['add']) || isset($_POST['save'])) {
            $data = $_POST['content'];

            //判断ID是否存在
            if(!$data['id'])
            {
                //add
                $data['slug'] = !empty($data['slug']) ? dealWords($data['slug']) : null;
                $data['created_uid'] = AuthUser::getId();
                $data['created_date'] = time();
                $article = new Article($data);
                if($article->save())
                {
                    $data_part = array();
                    $data_part = $_POST['content_part'];
                    $data_part['id'] = $article->id;
                    $content_part = new ContentPart($data_part);
                    $content_part->insert();
                    $article->content = $content_part->content;

                    //save tags
                    $article->saveTags(trim($_POST['tags']));

                    //update category
                    $category = new Category('id', $article->cid);
                    $category->count++;
                    $category->save();

                    $article->category = $category->name;
                    $article->category_slug = $category->slug;

                    //删除上一篇缓存
                    $prev = $article->previous();
                    $this->delPostHtml($prev->id);
                }

            }
            else
            {
                //update
                $data['updated_uid'] = AuthUser::getId();
                $data['updated_date'] = time();

                $old_article = new Article('id', $data['id']);

                $article = new Article($data);
                if($article->save())
                {
                    $data_part = array();
                    $data_part = $_POST['content_part'];
                    $data_part['id'] = $article->id;
                    $content_part = new ContentPart($data_part);
                    $content_part->save();
                    $article->content = $content_part->content;
                    $article->saveTags($_POST['tags']);
                    //更新文章分类统计
                    if($old_article->cid != $article->cid) {
                        $c1 = new Category('id', $article->cid);
                        $c1->count++;
                        $c1->save();
                        $c2 = new Category('id', $old_article->cid);
                        $c2->count--;
                        $c2->save();
                    }
                }
            }
            //$_POST = array();
            $tpl->assign('article', $article);

            //生成静态页面
            $status = isset($data['status']) ? $data['status'] : 'draft';
            if($status == 'publish') {
                $this->createPostHtml($article);
            } else if($status == 'draft') {
                $this->delPostHtml($article->id);
            }

            del_cache();

            if(isset($_POST['add']))
                header('location:index.php?job=admin_article');
        }

        $categories = Category::findAll();
        $tpl->assign('cats', $categories);
        $tpl->display('admin/article_add.html');
    }

    public function editAction($args=null)
    {
        global $tpl;

        //$id = isset($_GET['id']) ? $_GET['id'] : '';    //这里以后改进成$params['id']
        $id = isset($args['id']) ? (int)$args['id'] : '';    //如果为空 提示出错
        $article = new Article('id', $id);
        $content_part = ContentPart::findById($id);
        $article->content = $content_part->content;
        $article->tags = $article->getTags();
        $tpl->assign('article', $article);

        //查出所有分类
        $categories = Category::findAll();
        $tpl->assign('cats', $categories);

        $tpl->display('admin/article_add.html');

        del_cache();

    }//end func

    public function delAction($args=null)
    {
        //$id = (int)$_GET['id'];     //参数改进
        $id = isset($args['id']) ? (int)$args['id'] : null;    //如果为空 提示出错
        $cid = isset($args['cid']) ? (int)$args['cid'] : null;
        //$article = new Article('id', $id);
        if(isset($id) && isset($cid)) {
            //删除文章
            if(Article::deleteId($id)) {
                //删除content_part
                ContentPart::deleteId($id);

                //删除tag
                Tag::deleteId($id);

                //分类数量减1
                //$c = new Category('id', $article->cid);
                //$c->count--;
                //$c->save();
                $tablename = Record::tableFromClass('Category');
                $sql = "UPDATE $tablename SET count=count-1 WHERE id = '$cid'";
                Record::$__CONN__->query($sql);
                
                //删除静态文件
                $this->delPostHtml($id);
            }
        }
        del_cache();
        header('location:index.php?job=admin_article');
    }

    /**
     * 批量删除文章
     * @param <array> $args
     */
    public function batch_removeAction($args=null)
    {
        //mprint_r($_POST['selectID'], 'selectid');
        $post_checked = isset($_POST['checked']) ? $_POST['checked'] : null;
        if(is_array($post_checked) && count($post_checked) > 0) {

            foreach($post_checked as $val)
            {
                $data = unserialize(base64_decode($val));
                $id = $data->id;
                $cid = $data->cid;

                //有待改进
                Article::deleteId($id);
                ContentPart::deleteId($id);
                Tag::deleteId($id);
                $tablename = Record::tableFromClass('Category');
                $sql = "UPDATE $tablename SET count=count-1 WHERE id = '$cid'";
                Record::$__CONN__->query($sql);

                //删除静态文件
                $this->delPostHtml($id);
            }
        }
        del_cache();
        go_redirect('index.php?job=admin_article');
    }

    /**
     * 批量移动文章
     * @param <type> $args
     */
    public function batch_moveAction($args=null)
    {
        //move update cid
        $post_checked = isset($_POST['checked']) ? $_POST['checked'] : null;
        $newcid = isset($_POST['newcid']) ? $_POST['newcid'] : null;

        if(is_array($post_checked) && count($post_checked) > 0) {
            $id_arr = array();
            foreach($post_checked as $val) {
                $data = unserialize(base64_decode($val));
                if($data->cid != $newcid) {
                    $id_arr[] = $data->id;

                    //更新分类统计
//                    $c2 = new Category('id', $data->cid);
//                    $c2->count--;
//                    $c2->save();
                    $tablename = Record::tableFromClass('Category');
                    $sql = "UPDATE $tablename SET count=count-1 WHERE id = '{$data->cid}'";
                    Record::$__CONN__->query($sql);
                }
            }
            //sql
            Article::moveIds($id_arr, $newcid);
        }

        //更新文章分类统计
        $c1 = new Category('id', $newcid);
        $c1->count = $c1->count + count($id_arr);
        $c1->save();

        del_cache();
        go_redirect('index.php?job=admin_article');
    }

    public function createPostHtml($obj=null)
    {
        global $tpl;
        if(is_object($obj) && DEBUG === false) {
            $post = clone $obj;
            $post->content = processContent($post->content);


            //上一篇文章
            $previous = $obj->previous();

            //下一篇文章
            $next = $obj->next();

            //首页最近文章
            $recent_post = Article::getPost(5, false);

            //侧栏分类
            $categories = Category::findAll();

            //Tags
            $hot_tags = Tag::findAll(30);
            //mprint_r($hot_tags, '$hot_tags');
            if(count($hot_tags) > 0) {
                $first = current($hot_tags);
                $last = end($hot_tags);
                foreach($hot_tags as $k => $v) {
                    $tags_list[$k]['word'] = $v->name;
                    $tags_list[$k]['size'] = tagClouds($v->count, $first->count, $last->count);
                }
            }

            //smarty
            $tpl->assign('post', $post);
            $tpl->assign('next', $next);
            $tpl->assign('previous', $previous);
            $tpl->assign('recent_post', $recent_post);
            $tpl->assign('categories', $categories);
            $tpl->assign('tag_list', $tags_list);

            $filetpl = SYSTEM_ROOT.'templates/'.DEFAULT_TEMPLATE.'/post.html';
            $path = SYSTEM_ROOT.'post/';
            //$filename = !empty($obj->slug) ? str_replace(' ', '-', trim($obj->slug)) : $obj->id;
            $filename = $obj->id;
            $file = $path.$filename.'.html';
            file_put_contents($file, $tpl->fetch($filetpl));
            @chmod($file, 0777);
        }
    }//end func

    public function delPostHtml($id)
    {
        //删除静态文件
        $path = SYSTEM_ROOT.'post/';
        //$filename = !empty($article->slug) ? str_replace(' ', '-', trim($article->slug)) : $article->id;
        $filename = $id;
        if(file_exists($path.$filename.'.html'))
            @unlink($path.$filename.'.html');
    }
}

?>
