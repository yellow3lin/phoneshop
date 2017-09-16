<?php

namespace Home\Model;

use Think\Model;

class UserModel extends Model {
    protected $_validate = array(
        // array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('username','/^\w{5,16}$/','用户名由5-16字母数字下划线组成',1,'regex',3),
        array('username','','用户名已存在',1,'unique',3),
        array('email','email','email不合法',1,'regex',3),
        array('password','6,16','密码长度应在6-16位',1,'length',3),
        array('rpwd','password','两次密码不一致',1,'confirm',3),
        array('vcode','require','验证码必须！'),
    );

    // 设置cookie,完成登录
    public function auth() {
        cookie('user_id' , $this->user_id);
        cookie('username' , $this->username);
        cookie('ccode' , encCookie($this->user_id , $this->username));
    }

    // 收回权限,即清除cookie
    public function revoke() {
        cookie('user_id' , null);
        cookie('username' , null);
        cookie('ccode' , null);
    }

    //验证登录密码
    public function checkPass($password) {
        $realpass = $this->password;
        $this->password = $password;
        $this->encPass();
        return $realpass === $this->password;
    }

    //注册
    public function reg() {
        $this->encPass();
        return $this->add();
    }

    //返回MD5加密后的密码
    public function encPass() {
        $this->salt();
        $this->password = md5($this->salt.$this->password);
    }

    //生成盐
    public function salt() {
        if(!isset($this->salt)) {
            $this->salt = $this->randStr();
        }

        return $this->salt;
    }


    public function randStr($length = 8) {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*(';
        $str = str_shuffle($str);
        return substr($str, 0,$length);
    }
}
