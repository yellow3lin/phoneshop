<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysql',         // 数据库类型
    'DB_HOST'               =>  'localhost',     // 服务器地址
    'DB_NAME'               =>  '数据库名',      // 数据库名
    'DB_USER'               =>  '用户名',        // 用户名
    'DB_PWD'                =>  '密码',          // 密码
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE 模式); 3 (兼容模式) 默认为PATHINFO 模式
    'URL_MODEL' => 2,
    'URL_PATHINFO_DEPR'     =>  '/',
    'COO_KEY'               =>  'dsjfkl%(#P)EWI%dsfl;',

    #admin的盐
    'salt'                  =>  'Qt2^%fiD',

    #JdPay配置
    'V_MID'                 =>  '商户号',
    'V_URL'                 =>  '回调地址',
    'V_KEY'                 =>  '商户秘钥',

    #邮件
    'email'                 =>  [
        'Host'              => 'SMTP服务器',  //smtp.163.com
        'Username'          => 'SMTP服务器用户名',
        'Password'          => '授权码',
        'Port'              => 25,  //25,587,465 SMTP服务器端口
        'setFrom'           => '发件人名称',
        'addReplyTo'        => '回复人名称',
    ],

    #url token
    'token'                 => 'er8*(#nirtml213',
);