<?php
// 应用入口文件

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',false);

// 定义应用(网站项目的)目录,建议用绝对路径
define('APP_PATH',__DIR__.'/');

// 引入ThinkPHP入口文件
require '/ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单