<?php
/**
 * Created by PhpStorm.
 * User: h
 * Date: 2017/9/14
 * Time: 20:17
 */

namespace Admin\Controller;

use Common\Util\phpmailer\Email;
use Think\Controller;

class AdminController extends Controller
{
    public function login() {
        if ((cookie('admin_id') || cookie('adminname'))){
            $this->redirect('index/index');
        }
        $admin = D('Admin');
        if(!IS_POST) {
            $this->display('admin/login');
        }else {
            if(!$admin->create()){
                exit($admin->getError());
            }else{
                // 验证用户名/密码是否正确
                if ($this->check_verify(I('post.vcode'))){
                    $a = $admin->where(['adminname'=>I('post.adminname')])->find();
                    if(empty($a)) {
                        exit('用户名或密码错误');
                    }

                    if(!$admin->checkPass(I('post.password'))) {
                        exit('用户名或密码错误');
                    } else {
                        if (null !== I('post.remember') && I('post.remember') ==1 ){
                            $time = 3600*24;
                            $admin->auth($time);
                            $this->redirect('index/index');
                        }
                        $admin->auth();
                        $this->redirect('index/index');
                    }
                }else{
                   echo '验证码错误';
                }
            }

        }
    }

    public function logout() {
        $admin = D('Admin');
        $admin->revoke();
        $this->redirect('Admin/login');
    }


    public function ginfo(){
        $this->display();
    }

    //改密验证
    public function cpass(){
        //判断是否为post数据
        $admin= D('Admin');
        if (!IS_POST) {
            if (time() - I('get.time') >300){
                echo '该链接过期,请重新发送邮件';
            }
            $user = $admin->where(['adminname'=>I('get.username')])->find();
            $token = md5(md5(I('get.username')).base64_encode(get_client_ip()).md5(I('get.time')).C('token'));
            if (!$user && $token !== I('get.token')){
                $this->redirect('admin/login');
            }

            $this->display();
        }

        $rules = array(
            array('password','require','新密码必须！'),
            array('repass','password','确认密码不正确',0,'confirm'),
        );
        if (!$admin->validate($rules)->create()){
            exit($admin->getError());
        }
        $password= md5(C('salt').I('post.password'));
        $admin->changePass(I('get.username'), $password);
        $this->redirect('admin/login');
    }
    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }


    public function cinfo(){
        //判断是否为post数据
        if (!IS_POST) {
            $this->redirect('admin/login');
        }
        $rules = array(
            array('adminname','require','用户名必须！'),
            array('email','require','邮箱必须！'),
        );

        $admin= D('Admin');
        if (!$admin->validate($rules)->create()){
            exit($admin->getError());
        }else{
            $user = $admin->where(['adminname'=>I('post.adminname')])->find();
            if (!$user) {
                echo '该用户不存在';
            }
            //判断email是否正确
            if($user['email'] != I('post.email')){
                echo '邮箱错误';
            }
            $to = $user['email'];
            $title = '商城密码修改';
            $url = 'admin/admin/cpass';
            $content = Econtent($user['adminname'],$url,get_client_ip());
            if (Email::send($to,$title,$content)) {
                $this->success('邮件发送成功');
            }
        }

    }

}