<?php
namespace Admin\Controller;


use Think\Controller;

class CatController extends Controller {

    function __construct()
    {
        parent::__construct();
        if (!acc('admin')){
            $this->redirect('admin/login');
        }
    }

    //栏目列表
    public function lists() {
        $cat = D('Home/Cat');
        
        $cats = $cat->getTree();
        $this->assign('cats' , $cats);
        $this->display();
    }

    //栏目添加
    public function add() {
        if(!IS_POST) {
            $this->display('add');
        } else {
            // POST入库
            $cat = D('Home/Cat');
            $cat->add($_POST);
            $this->redirect('Admin/Cat/lists');
        }
    }

    //栏目编辑
    public function edit() {
        $cat = D('Home/Cat');
        
        $cats = $cat->getTree();
        $this->assign('cats' , $cats);

        $row = $cat->find(I('get.cat_id'));
        
        if(empty($row)) {
            $this->redirect('Admin/Cat/lists');
        }

        if(!IS_POST) {
            $this->assign('row' , $row);
            $this->display();
        } else {
            $cat->cat_name = I('post.cat_name');
            $cat->intro = I('post.intro');
            echo $cat->save() ? 'OK' : 'fail';
        }
    }

    //栏目删除
    public function del(){
        $cat = D('Home/Cat');
        $cat->delete(I('get.cat_id'));
        $this->redirect('Admin/Cat/lists');
    }
}