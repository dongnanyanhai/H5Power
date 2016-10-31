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
require EXTENSION_DIR . 'function.php';

// 在这里处理微信的事务
// 采用overtrue/wechat 2.x 框架
// 帮助手册：https://github.com/overtrue/wechat/wiki
// 阿海在这个公众号的openid
// oFrGvt5VighehGtB1eC1GQ7j_Y2g

$appId          = $config['SITE_FNX_WXAPPID'];
$secret         = $config['SITE_FNX_WXAPPSECRET'];
$token          = $config['SITE_FNX_WXTOKEN'];
$encodingAESKey = $config['SITE_FNX_WXAES_KEY'];

$server = new Overtrue\Wechat\Server($appId, $token, $encodingAESKey);

// 监听所有事件
$server->on('event', function($event) {
    /*
    $event 包含以下基本属性：

    ToUserName   接收者 ID（公众号 ID）
    FromUserName 发送方帐号（一个 OpenID）
    CreateTime   消息创建时间 （整型）
    MsgType      event
    Event        事件类型，ex: subscribe
    EventKey     事件 Key 值，与自定义菜单接口中 Key 值对应
    */
    Log::write(var_export($event, true), 'wc-event');

    switch ($event['Event']) {

        case 'subscribe':
            $re = strpos($event['EventKey'], 'qrscene_h5p-');
            if($re !== false && $re === 0){
                // 符合要求
                $qrcode_arg = str_replace('qrscene_h5p-','',$event['EventKey']);

                if($qrcode_arg != ''){

                    $QRCode = new H5p\QRCode();
                    
                    try {
                        $QRCode->doit($qrcode_arg,$event['Event'],$event['FromUserName']);
                    } catch (Exception $e) {
                        Log::write(var_export($e, true), 'wc-qrcode-err');
                    }

                }
            }
            break;

        case 'SCAN':
            $re = strpos($event['EventKey'], 'h5p-');
            if($re !== false && $re === 0){
                // 符合要求
                $qrcode_arg = str_replace('h5p-','',$event['EventKey']);

                if($qrcode_arg != ''){

                    $QRCode = new H5p\QRCode();
                    
                    try {
                        $QRCode->doit($qrcode_arg,$event['Event'],$event['FromUserName']);
                    } catch (Exception $e) {
                        Log::write(var_export($e, true), 'wc-qrcode-err');
                    }
                    // return 
                }
            }
            break;

        default:
            # code...
            break;
    }
});

// 监听所有事件
$server->on('message', function($message) {
    /*
    $message 参数具体属性查看：
    https://github.com/overtrue/wechat/wiki/%E6%B6%88%E6%81%AF%E7%9A%84%E4%BD%BF%E7%94%A8
    */
    Log::write(var_export($message, true), 'wc-message');
});


$result = $server->serve();

echo $result;