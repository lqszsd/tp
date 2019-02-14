<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
//注册自动加载
spl_autoload_register(function($class){
    //获取当前模块
   $module=\think\Request::instance()->module();
   //查找当前模块是否有该实例化模型
    if(stripos($class,$module.'\model')){
        require_once __DIR__.'/application/'.str_replace('\\',DIRECTORY_SEPARATOR,substr($class,0));
    }
});
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
