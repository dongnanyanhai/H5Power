<?php

/**
 * index.php 入口文件
 */
header('Content-Type: text/html; charset=utf-8');

/**
 * 定义项目所在路径(APP_ROOT)
 */
define('IN_FINECMS', true);
define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT',   dirname(__FILE__) . DS);

$sitesmap = require APP_ROOT.'sitesmap.php';

if(!empty($sitesmap[$_SERVER['HTTP_HOST']])){
    define('SITE_ROOT', $sitesmap[$_SERVER['HTTP_HOST']] . DS);
}else{
    define('SITE_ROOT', 'sites' . DS . 'default' . DS);
}

$config = require SITE_ROOT . 'config/config.ini.php';
require APP_ROOT . './core/App.php';

/**
* 启动网站进程
 */
App::run($config);