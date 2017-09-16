<?php
namespace Home\Model;

use Think\Model;

class CatModel extends Model {
    protected $cats = array();

    public function __construct() {
        parent::__construct();
        $this->cats = $this->select();
    }

    // 获取栏目树
    public function getTree($parent_id=0 , $lev=0) {
        $tree = array();

        foreach($this->cats as $c) {
            if($c['parent_id'] == $parent_id) {
                $c['lev'] = $lev;
                $tree[] = $c;

                // 再查$c的下级栏目
                $tree = array_merge($tree , $this->getTree($c['cat_id'] , $lev+1) );
            }   
        }

        return $tree;
    }

    // 获取面包屑
    public function getMbx($cat_id) {
        $row = $this->find($cat_id);
        $tree[] = $row;

        while($row['parent_id'] > 0) {
            $row = $this->find($row['parent_id']);
            $tree[] = $row;
        }

        return array_reverse($tree);
    }
}