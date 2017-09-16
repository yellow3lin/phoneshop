<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $cat = D('Cat');
        $cats = S('cats');
        
        if(empty($cats)) {
            $cats = $cat->getTree();
            S('cats' , $cats , 300);
        }

        $this->assign('cats' , $cats);

        $goods = D('Goods');
        $goods->where('is_best=1')->order('goods_id desc')->limit(0,3);
        $this->assign('best' , $goods->select());

        $goods->where('is_hot=1')->order('goods_id desc')->limit(0,3);
        $this->assign('hot' , $goods->select());

        $goods->where('is_new=1')->order('goods_id desc')->limit(0,3);
        $this->assign('new' , $goods->select());
        
        $this->assign('history' , array_reverse(session('history') , true) );

        $this->display();
    }

    public function cat(){
        $cat = D('Cat');
        $cats = $cat->getTree();
        $this->assign('cats' , $cats);


        $goods = D('Goods');

        $cnt = $goods->where('cat_id='.I('get.cat_id'))->count('*');
        $page = getPage($cnt,10);
        $this->assign('pages' , $page->show());

        $goods->where('cat_id='.I('get.cat_id'))->order('goods_id desc')->limit($page->firstRow,$page->listRows);
        $this->assign('goods' , $goods->select());

        $this->display('category');
    }

    //商品详情页
    public function goods(){
        $goods = D('Goods');
        $g = $goods->cache(true)->find(I('get.goods_id'));
        if(empty($g)) {
            $this->redirect('/');
        }
        $this->assign('g' , $g);
        $comm = M('Comment');
        if(IS_POST) {
            $comm->create();
            $comm->user_id = cookie('user_id') ? cookie('user_id') : 0;
            $comm->pubtime = time();
            $comm->goods_id = $goods->goods_id;
            $comm->add();
        }

        // 取商品的评论
        $comm->where('goods_id', $goods->goods_id)->order('pubtime desc');
        $this->assign('comm' , $goods->relationGet('Comment'));

        $cat = D('Cat');
        $this->assign('cats' , $cat->getMbx($g['cat_id']));

        // 把商品信息写入历史记录(session)
        $this->history($g);
        $this->display();
    }

    //记录浏览历史
    public function history($g) {
        $history = session('?history') ? session('history') : array();
        if(isset($history[$g['goods_id']])) {
            unset($history[$g['goods_id']]);
        }
        $row = array();
        $row['thumb_img'] = $g['thumb_img'];        
        $row['goods_name'] = $g['goods_name'];        
        $row['shop_price'] = $g['shop_price'];  

        $history[$g['goods_id']] = $row;

        // 浏览历史不超过5个
        if(count($history) > 5) {
            $key = key($history);
            unset($history[$key]);
        }

        session('history' , $history);           
    }
}