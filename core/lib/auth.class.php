<?php
/**
 * auth class file
 * 权限验证
 */

if (!defined('IN_FINECMS')) {
    exit();
}

class auth extends Fn_base {
    public static function check($groupid, $action) {
        //跳过不需要验证的模块
        $skip_array = array(
            'admin/index/index',
            'admin/index/main',
            'admin/login/logout',
            'admin/login/index',
            'login',
            'content',
            'form'
        );
        // if (self::skip($action, $namespace)) return true;
        if(@in_array($action,$skip_array)) return true;
        if(!$groupid) return false;
        if($groupid == 1) return true;
        $rules = self::get_role($groupid);
        if (empty($rules)) false;

        if(@in_array($action, $rules)){
            return true;
        }

        $rules_n = array();
        foreach ($rules as $k => $v) {
            list($s,$c,$a) = explode("/", $v);
            $rules_n[$k] = $s."/".$c;
        }

        list($s,$c,$a) = explode("/", $action);
        if(@in_array($s."/".$c, $rules_n)){
            return true;
        }

        return false;
        
    }
    
    public static function get_role($groupid) {
        //加载权限分配文件
        $user = Controller::Model('user');
        $config = $user->roleinfo($groupid);
        if($config && $config['privates'] !=''){
            return string2array($config['privates']);
        }else{
            return null;
        }
    }
    
    public static function skip($action, $namespace="defalut") {
        //controller和action
        list($c, $m) = explode("-", $action);
        if (stripos($m, "ajax")!==false) return true;
        //加载不需要权限验证的配置文件
        $config_file = CONFIG_DIR . "auth.skip.ini.php";
        if (!is_file($config_file)) return false;
        $config = require $config_file;
        $skip = $namespace && isset($config[$namespace]) ?  $config[$namespace] : $config['defalut'];
        if (empty($skip)) return true; //配置文件中没有内容，直接跳过验证
        if (in_array($c, $skip)) {
            //跳过
            return true;
        } elseif (in_array($action, $skip)) {
            //跳过
            return true;
        } else {
            return false;
        }
    }
    
}