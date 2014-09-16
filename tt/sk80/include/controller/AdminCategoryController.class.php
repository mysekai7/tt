<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdminCategoryController extends Controller
{
    public function __construct()
    {
        AuthUser::load();
        if(!AuthUser::isLoggedIn()) {
            echo 'Please Login';
            header('location:index.php?job=login');
        }
    }

    public function indexAction()
    {
        global $tpl;
        
        //默认分类列表
        //
        $cats = array();
        $result = Category::findAll();
        if(is_array($result) && count($result)>0)
        {
            foreach($result as $key => $val)
            {
                $result[$key]->level = count(explode(',',$val->path)) - 2;
                $result[$key]->parent = 0;

                foreach($result as $v)
                {
                    if($val->id == $v->pid) {
                        $result[$key]->parent = 1;
                        break;
                    }
                }
            }
            $cats = $result;
        }

        //var_dump($result);
        $tpl->assign('categories', $cats);

        $tpl->display('admin/category_list.html');
        
        
    }

    public function addAction()
    {
        global $tpl;
        //$reffer = $_SERVER['HTTP_REFERER'];
        
        $data = isset($_POST['cat']) ? $_POST['cat'] : array();
        //var_dump($data);
        if(isset($_POST['submit'])){
            //统一处理 验证方法 后期改进
            foreach($data as $key=>$val) {
                $data[$key] = trim($val);
            }
            
            $new_category = new Category($data);
            $new_category->save('category');       

            //更新当前分类
            $pid = (int)$data['pid'];
            if($pid == 0) {
                //顶级分类, path为0,本身ID
                $new_category->path = '0,'.$new_category->id;
            } elseif($pid > 0) {
                //查询父ID的path
                $parent_category = new Category('id', $pid);
                $new_category->pid = $parent_category->id;
                $new_category->path = $parent_category->path.','.$new_category->id;
                
            }
            $new_category->save('category');
            $_POST = array();
            header('location:index.php?job=admin_category');
        } else {
            //查出所有分类
            //$result = Category::findAll();
            $result = Category::findAll();
            //var_dump($result);
            $tpl->assign('cats', $result);
            //$tpl->assign('cancel', $reffer);

            $tpl->display('admin/categroy_add.html');
        }
    }

    public function delAction($args=null)
    {
        //$id = (int)$_GET['id']; //参数改进
        $id = isset($args['id']) ? (int)$args['id'] : '';

        $where = "pid = '{$id}'";
        //$a = Category::findAll($where);
        //var_dump($a);
        //exit;

        if(!Category::findAll($where))
            Category::deleteId($id);
        
        header('location:index.php?job=admin_category');
    }

    public function editAction($args=null)
    {
        global $tpl;

        $data = isset($_POST['cat']) ? $_POST['cat'] : array();
        if(isset($_POST['submit'])){
            //var_dump($data);
            $category = new Category('id', $date['id']);

            //更新当前分类
            $pid = (int)$data['pid'];
            if($pid == 0) {
                //顶级分类, path为0,本身ID
                $category->path = '0,'.$category->id;
                $category->pid = 0;
            } elseif($pid > 0) {
                //查询父ID的path
                $parent_category = new Category('id', $pid);
                $category->pid = $parent_category->id;
                $category->path = $parent_category->path.','.$category->id;

            }
            $category->save('category');
            $_POST = array();
            header('location:index.php?job=admin_category');
        } else {
            $id = isset($args['id']) ? (int)$args['id'] : '';
            $category = new Category('id', $id);    //参数改进
            //$category = get_object_vars($category);
            $tpl->assign('cat', $category);

            //查出所有分类
            //$result = Category::findAll();
            $result = Category::findAll();
            //var_dump($result);
            $tpl->assign('cats', $result);

            $tpl->display('admin/categroy_edit.html');
        }
    }//end func
}
?>
