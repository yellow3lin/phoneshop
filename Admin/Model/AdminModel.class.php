<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2017/9/15
 * Time: 7:39
 */

namespace Admin\Model;

use Think\Model;

class AdminModel extends Model
{

    protected $_validate = array(
        // array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('adminname','require','用户名必须'),
        array('password','require','密码必须'),
        array('vcode','require','验证码必须！'),
    );
    // 设置cookie,完成登录
    public function auth($time='') {
        cookie('admin_id' , $this->admin_id,$time);
        cookie('adminname' , $this->adminname,$time);
        cookie('acode' , encCookie($this->admin_id , $this->adminname),$time);
        cookie('admin_expire' ,$time,$time);
    }


    // 收回权限,即清除cookie
    public function revoke() {
        cookie('admin_id' , null);
        cookie('adminname' , null);
        cookie('acode' , null);
    }

    //验证登录密码
    public function checkPass($password) {
        $realpass = $this->password;
        $this->password = $password;
        $this->encPass();
        return $realpass === $this->password;
    }

    //返回MD5加密后的密码
    public function encPass() {
        $this->password = md5(C('salt').$this->password);
    }

    /**
     * 根据传递过来的user数据,以及index/blog/read 生成改密地址
     * @param [type] $user [description]
     * @param [type] $url index模块 blog控制器的read操作
     */
    public function Econtent($user,$url,$ip){
        $time = time();
        $token = $this->createToken($user['username'],$ip);
        $data = ['username'=>$user['username'], 'time'=>$time, 'token'=>$token];
        $url = url($url,$data);
        return $content = '<p>尊敬的' . $user['username'].', 您好</p> <p>您的找回密码链接如下: </p><p><a href="http://tpshop.com'. $url. '"></a></p><p>该链接5分钟内有效,请勿传递给别人!</p><p>该邮件为系统自动发送,请勿回复!</p>';
    }

    //根据用户名修改密码
    public function changePass($username,$password){
        $data['password'] = $password;
        return $this->where('adminname', $username)->save($data);
    }
}