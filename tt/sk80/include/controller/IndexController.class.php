<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 缓存 做改进 封装到函数中
 */
class IndexController extends Controller
{
    public $categories;
    public $cache;

    public function __construct()
    {
        global $tpl, $app;

        $app->refresh();

        $this->cache = new FileCache();
        $this->cache->cachePath = DATA_DIR.'cache/';

        //侧栏分类
        $categories = $this->cache->get('categories');
        if($categories === false) {
            $categories = array();
            //获得所有分类
            $categories[0] = Category::findAll();
            if(is_array($categories[0]) && count($categories[0]) > 0) {
                foreach($categories[0] as $k => $v) {
                    $categories[0][$k]->url = SITE_URL.'category/'.$v->slug.'/';
                    //$this->categories[$v->id] = get_object_vars($v);
                    $categories[1][$v->id] = $v;
                    $categories[1][$v->slug] = $v;
                }
            }

            if(DEBUG === false) {
                $this->cache->set('categories', $categories, 7200);//缓存2小时
                del_cache();
            }
        }

        //mprint_r($categories[0], 'a');

        //分别以ID, slug为键值的分类组合
        $this->categories = $categories[1];


        //Tags 热词
        $tags_list = $this->cache->get('hot_tags');
        if($tags_list === false) {
            $hot_tags = Tag::findAll(20);
            //mprint_r($hot_tags, '$hot_tags');
            if(count($hot_tags) > 0) {
                $first = current($hot_tags);
                $last = end($hot_tags);
                foreach($hot_tags as $k => $v) {
                    $tags_list[$k]['word'] = $v->name;
                    $tags_list[$k]['size'] = tagClouds($v->count, $first->count, $last->count);
                    $tags_list[$k]['url'] = SITE_URL.'tag/'.strtolower($v->name).'/';
                }
            }
            if(DEBUG === false) {
                $this->cache->set('hot_tags', $tags_list, 7200);
            }
        }

        //首页最近文章
        $recent_post = $this->cache->get('recent_post');
        if($recent_post === false) {
            $recent_post = Article::getPost(5);
            if(is_array($recent_post) && count($recent_post)>0) {
                foreach($recent_post as $key => $val) {
                    $recent_post[$key]->url = SITE_URL.'post/'.$val->id.'.html';
                }
            }
            if(DEBUG === false) {
                $this->cache->set('recent_post', $recent_post, 7200);//缓存2小时
                del_cache();
            }
        }
        //mprint_r($recent_post, '$recent_post');
        $tpl->assign('recent_post', $recent_post);

        $tpl->assign('categories', $categories[0]);
        $tpl->assign('tag_list', $tags_list);
    }

    public function indexAction($args=null)
    {
        global $tpl;

//        if(file_exists('index.html'))
//            header('location:index.html');

        $page = new Page;
        $page->Page = isset($args['page']) ? (int)$args['page'] : 1;
        $page->PerPage = 1;
        $page->Url = SITE_URL.'page/';
        $page->Condition = '/';


        //获得头5篇文章
        //$articles = Article::getPost(5);
        $result = Article::listPost($page);
        $page->Count = $result['count'];
        $articles = $result['res'];
        if(is_array($articles) && count($articles)>0) {
            foreach($articles as $key => $val) {
                $content_part = ContentPart::findById($val->id);
                $articles[$key]->content = processContent($content_part->content);
                $articles[$key]->url = SITE_URL.'post/'.$val->id.'.html';
                $articles[$key]->category = $this->categories[$val->cid]->name;
                $articles[$key]->category_url = SITE_URL.'category/'.$this->categories[$val->cid]->slug.'/';
            }
        }
        //mprint_r($articles, '$articles');

        //获得内容
        //$content_part = ContentPart::findById($article->id);

        //语法加亮处理
        //$article->content = processContent($content_part->content);

        //获得tags
        //$tags = Tag::findById($article->id);
        //$article->tags = $tags;
        //获得分类
        //$category = new Category('id', $article->cid);
        //$article->category = $category->name;
        //$article->category_slug = $category->slug;
        //$article->category = $this->categories[$article->cid]->name;
        //$article->category_slug = $this->categories[$article->cid]->slug;

        $tpl->assign('articles', $articles);

        $tpl->assign('page_nav', $page->getSimplePage());

        //首页生成静态页
        $index_cache = $this->cache->get('index_cache');
        if($index_cache === false || !file_exists('index.html')) {
            if(DEBUG === false) {
                header('location:'.SITE_URL.'page/1/');
                $fp = fopen('index.html','w');
                fputs($fp, $tpl->fetch(DEFAULT_TEMPLATE.'/index.html'));
                fclose($fp);
                @chmod('index.html', 0777);
                $index_cache = 'nkjnj';
                $this->cache->set('index_cache', $index_cache, 7200);//缓存2小时
            }
        }

        $tpl->display(DEFAULT_TEMPLATE.'/index.html');
    }

    public function showAction($args=null)
    {
        global $tpl, $app;
        $id = isset($args['id']) ? (int)$args['id'] : '';    //如果为空 提示出错
        try {
            $article = new Article('id', $id);
            if(!$article->id)
                throw new Exception('文章不存在');
            $content_part = ContentPart::findById($id);
            //语法加亮处理
            $article->content = processContent($content_part->content);
            $article->tags = $article->getTags();
            $article->category = $this->categories[$article->cid]->name;
            $article->category_slug = $this->categories[$article->cid]->slug;
            //上一篇文章
            $previous = $article->previous();

            //下一篇文章
            $next = $article->next();

            //首页最近文章
            //$recent_post = Article::getPost(5, true);
        } catch (Exception $e) {
            //die($e->getMessage());
            $app->error($e->getMessage(), SITE_URL);
        }

        $tpl->assign('post', $article);
        $tpl->assign('next', $next);
        $tpl->assign('previous', $previous);
        //首页post静态页
        if(DEBUG === false) {
            $fp = fopen(SYSTEM_ROOT."post/{$id}.html",'w');
            fputs($fp, $tpl->fetch(DEFAULT_TEMPLATE.'/post.html'));
            fclose($fp);
        }

        $tpl->display(DEFAULT_TEMPLATE.'/post.html');
    }

    public function tagAction($args=null)
    {
        global $app, $tpl;
        try{
            if(!isset($args['name']))
                throw new Exception('请输入合法Tag标签');
            $page = new Page;
            $page->Page = $args['page'];
            $page->Url = SITE_URL.'tag/'.trim($args['name']).'/';
            $page->Condition = '/';

            $result = Article::findByTag(trim($args['name']), $page);
            $page->Count = $result['count'];
        } catch(Exception $e) {
            //die($e->getMessage());
            $app->error($e->getMessage(), SITE_URL);
        }
        $tpl->assign('post_list', $result['res']);
        $tpl->assign('tag', $args['name']);
        $tpl->assign('page_nav', $page->getPage());
        $tpl->display(DEFAULT_TEMPLATE.'/list.html');
    }

    public function categoryAction($args=null)
    {
        global $tpl, $app;
        try{
            if(!isset($args['slug']) || !isset($this->categories[trim($args['slug'])]))
                throw new Exception('分类不能为空或分类不存在');

            $page = new Page;
            $page->Page = $args['page'];
            $page->Url = SITE_URL.'category/'.trim($args['slug']).'/';
            $page->Condition = '/';
            //$category = new Category('slug', trim($args['slug']));

            $category = $this->categories[trim($args['slug'])];

            $result = Article::findByCid($category->id, $page);
            $page->Count = $result['count'];

        } catch(Exception $e) {
            //die($e->getMessage());
            $app->error($e->getMessage(), SITE_URL);
        }

        $tpl->assign('post_list', $result['res']);
        $tpl->assign('category', $category->name);
        $tpl->assign('page_nav', $page->getPage());
        $tpl->display(DEFAULT_TEMPLATE.'/list.html');
    }
}

?>
