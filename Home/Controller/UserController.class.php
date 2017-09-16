<?php
namespace Home\Controller;


use Think\Controller;

class UserController extends Controller {
    //用户注册
    public function reg() {
        if(!IS_POST) {
            $this->display('user/reg');
        } else {
            $user = D('User');
            if(!$user->create()) {
                exit($user->getError());
            } else {
                if( $this->check_verify(I('post.vcode'))){
                    $user->reg();
                    $this->redirect('user/login');
                }
            }
        }
    }

    //用户登录
    public function login() {
        if(!IS_POST) {
            $this->display('user/login');
        } else {
            // 验证用户名/密码是否正确
            $user = D('User');
            $u = $user->where(['username'=>I('post.username')])->find();

            if(empty($u)) {
                exit('用户名或密码错误');
            }
            if(!$user->checkPass(I('post.password'))) {
                exit('用户名或密码错误');
            } else {
                $user->auth();
                $this->redirect('/');
            }
        }
    }

    //退出登录
    public function logout() {
        $user = D('User');
        $user->revoke();
        $this->redirect('User/login');
    }

    //验证码
    public function vcode() {
        $verify = new \Think\Verify();
        $verify->imageW = 120;
        $verify->imageH = 40;
        $verify->length= 4;
        $verify->fontSize = 17;
        $verify->useNoise = false;
        
        $verify->entry();
    }

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
}
