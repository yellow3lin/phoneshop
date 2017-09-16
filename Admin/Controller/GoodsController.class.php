<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Image;
use Think\Upload;

class GoodsController extends Controller {
    protected $model = null;

    public function __construct() {
        parent::__construct();
        if (!acc('admin')){
            $this->redirect('admin/login');
        }
        $this->model = D('Home/Goods');

    }

    //商品列表
    public function lists() {
        $cnt = $this->model->count('*');
        $page = getPage($cnt,15);
        $pages = $page->show();

        $goods = $this->model->field('goods_id,goods_name,goods_sn,shop_price,is_best,is_new,is_hot')->order('goods_id desc')->limit($page->firstRow,$page->listRows)->select();

        $this->assign('pages' , $pages);
        $this->assign('goods' , $goods);
        $this->display();
    }

    //商品添加
    public function add() {
        if(!IS_POST) {
            $this->display();
        } else {
            if(!$this->model->create()) {
                echo $this->model->getError();
            } else {
                if(isset($_FILES['goods_img'])) {
                    $up = new Upload();
                    $up->exts = array('jpeg' , 'jpg' , 'png' , 'gif');
                    $up->rootPath = APP_PATH;
                    $up->savePath = '/Public/images/';

                    $info = $up->upload();
                    if($info) {
                        $this->model->ori_img = $info['goods_img']['savepath']  . $info['goods_img']['savename'];
                        $this->model->goods_img = $this->model->ori_img . '230X230.png';
                        $this->model->thumb_img = $this->model->ori_img . '100X100.png';

                        // 缩略
                        $img = new Image();
                        $img->open(APP_PATH . $this->model->ori_img);

                        $img->thumb(230,230,Image::IMAGE_THUMB_FILLED);
                        $img->save(APP_PATH . $this->model->goods_img);

                        $img->thumb(100,100,Image::IMAGE_THUMB_FILLED);
                        $img->save(APP_PATH . $this->model->thumb_img);
                    }
                }
                $this->model->goods_desc = $_POST['goods_desc'];
                $this->model->add();
                $this->redirect('goods/lists');
            }
        }
    }

    //商品删除
    public function del() {
        $this->model->delete(I('get.goods_id'));
        $this->redirect('Admin/Goods/lists');
    }
}