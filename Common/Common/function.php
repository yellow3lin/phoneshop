<?php

function encCookie($user_id,$username) {
    return md5(C('COO_KEY') . $user_id . $username);
}

//访问权限
function acc($acc) {
    if ($acc == 'admin'){
        if (!cookie('admin_id') || !cookie('adminname')){
            return false;
        }
        $acode = encCookie(cookie('admin_id') , cookie('adminname'));
        return $acode === cookie('acode');
    }else if($acc == 'user'){
        if(!cookie('user_id') || !cookie('username')) {
            return false;
        }
        $ccode = encCookie(cookie('user_id') , cookie('username'));
        return $ccode === cookie('ccode');
    }

}

//分页
function getPage($count, $list){
    $page = new \Think\Page($count, $list);
    $page->setConfig('prev', '上一页');
    $page->setConfig('next', '下一页');
    $page->setConfig('first', '首页');
    $page->setConfig('last', '末页');
    $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
    $page->parameter=I('get.');
    return $page;
}

//根据传递过来的user数据,生成改密地址
function Econtent($username,$url,$ip){
    $time = time();
    $token = createToken($username,$ip);
    $data = ['username'=>$username, 'time'=>$time, 'token'=>$token];
    $url = U($url,$data);
    return $content = '<p>尊敬的' . $username.', 您好</p> <p>您的找回密码链接如下: </p><p><a href="'. $url. '"></a></p><p>该链接5分钟内有效,请勿传递给别人!</p><p>该邮件为系统自动发送,请勿回复!</p>';
}

// 创建token
function createToken($username,$ip){
    $time = time();
    return $token = md5(md5($username).base64_encode($ip).md5($time).C('token'));
}

