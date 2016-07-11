<?php

/**
 * index.php 入口文件
 */
header('Content-Type: text/html; charset=utf-8');

/**
 * 定义项目所在路径(APP_ROOT)
 */
define('IN_FINECMS', true);
define('APP_ROOT',   dirname(__FILE__) . DIRECTORY_SEPARATOR);
$config = require APP_ROOT . 'config/config.ini.php';
require APP_ROOT . './core/App.php';
require EXTENSION_DIR . 'function.php';

// 在这里处理微信的事务
// 采用overtrue/wechat 2.x 框架
// 帮助手册：https://github.com/overtrue/wechat/wiki

$appId          = $config['SITE_FNX_WXAPPID'];
$secret         = $config['SITE_FNX_WXAPPSSCRET'];
$token          = $config['SITE_FNX_WXTOKEN'];
$encodingAESKey = $config['SITE_FNX_WXAES_KEY'];

$server = new Overtrue\Wechat\Server($appId, $token, $encodingAESKey);

// 监听所有类型
$server->on('message', function($message) {
    // 获取用户openId: $openId = $message->FromUserName;
    return Message::make('text')->content('您好！');
});

// 监听指定类型
$server->on('message', 'image', function($message) {
    return Message::make('text')->content('我们已经收到您发送的图片！');
});

$result = $server->serve();

echo $result;