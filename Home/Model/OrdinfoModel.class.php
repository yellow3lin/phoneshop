<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2017/9/14
 * Time: 15:43
 */

namespace Home\Model;


use Think\Model;

class OrdinfoModel extends Model
{
    protected $_validate = array(
        // array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('xm','require','姓名必须！'),
        array('mobile' , '/^1[3|4|5|8][0-9]\d{4,8}$/' , '手机号错误'),
        array('address' , 'require' , '地址必须'),
    );
}