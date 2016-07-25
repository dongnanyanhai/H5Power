<?php

/**
 * index.php 入口文件
 */
header('Content-Type: text/html; charset=utf-8');

/**
 * 定义项目所在路径(APP_ROOT)
 */
define('IN_FINECMS', true);
define('IN_ROOTDIR', false);
define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT',   dirname(dirname(__FILE__)) . DS);
define('SITE_ROOT',   dirname(__FILE__) . DS);

$config = require SITE_ROOT . 'config/config.ini.php';
require APP_ROOT . './core/App.php';

/**
* 启动网站进程
 */
App::run($config);