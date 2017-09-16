<?php
namespace Home\Model;

use Think\Model\RelationModel;

class GoodsModel extends RelationModel {
    protected $_validate = array(
        array('goods_name' , '5,16' , '商品名称应5-16字符',1, 'length',3),
        array('is_best' , '0,1' , '精品只能0,1',0,'in',3),
        array('goods_sn' , '','货号不能重复',1,'unique' , 3),
        array('cat_id' , 'ckc','栏目不存在',1,'callback' , 3),
    );

    protected $_link = array(
        'Comment'=>self::HAS_MANY,
    );

    protected $_auto = array(
        // array(完成字段1,完成规则,[完成条件,附加规则]),
        array('add_time','time',1,'function'),
        array('last_update' , 'time' , 2 , 'function'),
    );

    // 添加时,允许通过的字段
    protected $insertFields = 'goods_sn,cat_id,brand_id,goods_name,shop_price,market_price,goods_number,click_count,goods_weight,goods_brief,goods_desc,thumb_img,goods_img,ori_img,is_on_sale,is_delete,is_best,is_new,is_hot,add_time';

    protected function ckc() {
        $cat = D('Home/Cat');
        return $cat->find(I('post.cat_id')) ? true : false;
    }

}