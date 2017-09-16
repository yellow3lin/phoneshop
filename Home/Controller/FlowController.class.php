<?php

namespace Home\Controller;

use Home\Tool\CartTool;
use Think\Controller;

class FlowController extends Controller {
    // 把商品添加到购物车
    public function add() {
        if (!acc('user')){
            $this->redirect('user/login');
        }
        // 根据地址栏的goods_id,查询商品信息
        $goods = D('Goods');
        if(!$goods->find(I('get.goods_id'))) {
            $this->redirect('/');
        }

        // 实例化购物车类
        $cart = CartTool::getIns();
        
        // 把商品信息添加到购物车
        $cart->add($goods->goods_id , $goods->goods_name, $goods->shop_price);

        // 转到checkout页面
        $this->redirect('Home/Flow/checkout');
    }

    // 购物车展示页面,准备下单
    public function checkout(){
        // 实例化购物车类
        $cart = CartTool::getIns();
        $this->assign('goods' , $cart->items());
        $this->assign('money' , $cart->calcMoney());

        $this->display();
    }

    // 正式下单
    public function done() {
        // 实例化购物车类
        $cart = CartTool::getIns();
        $order = D('Ordinfo');
        if (!$order->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            exit($order->getError());
        }
        $order->ord_sn = $ord_sn = date('Ymd').rand(1000,9999);
        $order->user_id = cookie('user_id') ? cookie('user_id') : 0;
        $order->money = $money = $cart->calcMoney();
        $order->ordtime = time();

        if($ordinfo_id = $order->add()) {
            // 添加ordgoods表
            $og = M('ordgoods');
            $data = array();

            foreach($cart->items() as $k=>$v) {
                $row = array();
                $row['goods_id'] = $k;
                $row['goods_name'] = $v['goods_name'];
                $row['shop_price'] = $v['shop_price'];
                $row['goods_num'] = $v['num'];
                $row['ordinfo_id'] = $ordinfo_id;

                $data[] = $row;
            }

            if($og->addAll($data)) {
                $this->assign('ord_sn' , $ord_sn);
                $this->assign('money' , $money);

                // 在线支付
                $jdpay = new \Home\Pay\JdPay($ord_sn , $money);
                $this->assign('form' , $jdpay->form());

                $cart->clear();
                $this->display();
            }
        } else {
            echo 'error';
        }

        
    }
}

?>