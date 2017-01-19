<?php

/**
 * index.php 入口文件
 */
header('Content-Type: text/html; charset=utf-8');

/**
 * 定义项目所在路径(APP_ROOT)
 */
define('IN_FINECMS', true);
define('IN_ROOTDIR', true);
define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT',   dirname(__FILE__) . DS);

$sitesmap = require APP_ROOT.'sitesmap.php';

if(!empty($sitesmap[$_SERVER['HTTP_HOST']])){
    define('SITE_ROOT', APP_ROOT . $sitesmap[$_SERVER['HTTP_HOST']] . DS);
}else{
    die("请通过sitemap.php绑定站点目录！");
}

$config = require SITE_ROOT . 'config/config.ini.php';
require APP_ROOT . './core/App.php';

/**
* 启动网站进程
 */
App::run($config);