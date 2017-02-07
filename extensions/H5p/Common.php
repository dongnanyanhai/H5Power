<?php

namespace H5p;

/**
* 自定义二维码参数
* 还未测试 红包发放
*/
class Common extends \Fn_base
{
    protected $app_id;
    protected $app_secret;

    protected $pay_id; //商号id
    protected $pay_secret; // 商号密匙
    protected $pay_cert_path;
    
    protected $staff;

    protected $siteid;
    protected $sys_config;

    public $site_url;
    public static $errinfo;

    
    function __construct()
    {

        $this->sys_config = require SITE_ROOT . 'config/config.ini.php';
        // var_dump($this->sys_config);

        $base_url = \Controller::get_base_url();
        $server_name = \Controller::get_server_name();
        $this->site_url = $server_name . ($base_url ? $base_url : '' );

        $this->siteid       = \App::get_site_id()?\App::get_site_id():1;

        $this->app_id = $this->sys_config['SITE_FNX_WXAPPID'];

        if(empty($this->app_id)){
            self::$errinfo = 'appId 为空';
        }

        $this->app_secret = $this->sys_config['SITE_FNX_WXAPPSECRET'];

        if(empty($this->app_secret)){
            self::$errinfo = 'App Secret 为空';
        }

        // 下面这两个需要到系统增加设定

        $this->pay_id = $this->sys_config['SITE_FNX_WXPAYID'] ? $this->sys_config['SITE_FNX_WXPAYID'] : '';
        $this->pay_secret = $this->sys_config['SITE_FNX_WXPAYSECRET'] ? $this->sys_config['SITE_FNX_WXPAYSECRET'] : '';
        $this->pay_cert_path = $this->sys_config['SITE_FNX_WXPAYCERTPATH'] ? $this->sys_config['SITE_FNX_WXPAYCERTPATH'] : '';


        

        // 用以发送微信公众号信息的类实例
        // $this->staff = new \Overtrue\Wechat\Staff($this->app_id, $this->app_secret);

    }

    /**
     * 格式化字段数据
     */
    protected function getFieldData($model, $data) {
        if (!isset($model['fields']['data']) || empty($model['fields']['data']) || empty($data)) return $data;
        foreach ($model['fields']['data'] as $t) {
            if (!isset($data[$t['field']])) continue;
            if ($t['formtype'] == 'editor') {
                //把编辑器中的HTML实体转换为字符
                $data[$t['field']] = htmlspecialchars_decode($data[$t['field']]);
            } elseif (in_array($t['formtype'], array('checkbox', 'files', 'fields'))) {
                //转换数组格式
                $data[$t['field']] = string2array($data[$t['field']]);
            }
        }
        return $data;
    }

    protected function setFieldData($model, $data) {
        if (!isset($model['fields']['data']) || empty($model['fields']['data']) || empty($data)) return $data;
        foreach ($model['fields']['data'] as $t) {
            if (!isset($data[$t['field']])) continue;
            if ($t['formtype'] == 'editor') {
                //把编辑器中的HTML实体转换为字符
                $data[$t['field']] = htmlspecialchars($data[$t['field']]);
            } elseif (in_array($t['formtype'], array('checkbox', 'files', 'fields'))) {
                //转换数组格式
                $data[$t['field']] = array2string($data[$t['field']]);
            }
        }
        return $data;
    }

    public function get_errinfo(){
        return self::$errinfo;
    }

    public function get_content_data($id,$plugin_id =""){

        $plugin_id = $plugin_id ? $plugin_id : \App::get_plugin_id();

        $id = (int)$id;

        if(empty($id)){
            self::$errinfo = '错误的ID值';
            return false;
        }

        $content = \Controller::plugin_model($plugin_id,'content_' . $this->siteid);

        $data = $content->find($id);
        

        if($data){

            // 存在对应id的数据

            // 获取站点模型缓存
            $model = get_model_data($plugin_id.'_model_content',$this->siteid);

            if ($data['status'] == 0) { 
                //判断数据是否存在或文档状态是否通过
                self::$errinfo = '文档未通过审核';
                return false;
            } elseif (!isset($model[$data['modelid']]) || empty($model[$data['modelid']])) {    //判断模型是否存在
                // 文档模型不存在
                self::$errinfo = '文档模型不存在';
                return false;
            }

            $table  = \Controller::plugin_model($plugin_id,$model[$data['modelid']]['tablename']);

            $_data  = $table->find($id);    //附表数据查询

            $data   = array_merge($data, $_data); //合并主表和附表

            $data   = $this->getFieldData($model[$data['modelid']], $data); //格式化部分数据类型

            return $data;
        }

        return false;
    }

        // 添加表单数据
    public function set_content_data($modelid,$data,$plugin_id =""){

        $plugin_id = $plugin_id ? $plugin_id : \App::get_plugin_id();

        $modelid = (int)$modelid;

        if(empty($modelid) || empty($data) || !is_array($data)){
            self::$errinfo = '参数错误';
            return false;
        }

        $model = get_model_data($plugin_id.'_model_content',$this->siteid);

        $content_model = $model[$modelid];

        $content = \Controller::plugin_model($plugin_id,'content_' . $this->siteid);

        $temp_data['modelid']    = (int)$modelid;
        $temp_data['inputtime']  = $temp_data['updatetime'] = time();

        $data = array_merge($temp_data,$data);
        $data = $this->setFieldData($content_model,$data);

        // 字段数据
        if(isset($data['id']) && ((int)$data['id'] != 0)){
            // 更新内容
            $id = $content->set($data['id'],$content_model['tablename'], $data);
        }else{
            // 新增内容
            $id = $content->set(0,$content_model['tablename'], $data);
        }
        
        if($id){
            return $id;
        }else{
            self::$errinfo = '数据库插入数据失败';
            return false;
        }

    }

    // 根据$id获取表单数据
    public function get_form_data($modelid,$conditions = '',$id=0,$plugin_id =""){

        $plugin_id = $plugin_id ? $plugin_id : \App::get_plugin_id();

        if(empty($modelid)){
            self::$errinfo = '未提供准确的Modelid';
            return false;
        }

        $modelid = (int)$modelid;

        $model = get_model_data($plugin_id.'_model_form',$this->siteid);
        $form_model = $model[$modelid];
        $form = \Controller::plugin_model($plugin_id,$form_model['tablename']);

        if(!empty($conditions)){
            // 根据条件查询
            $data = $form->getAll($conditions);

            if($data){

                if (isset($form_model['fields']) && $form_model['fields']){

                    $data = $this->getFieldData($form_model, $data);
                }
                return $data;
            }else{
                self::$errinfo = '未查询到数据';
                return false;
            }

        }else if(!empty($id)){
            // 获取指定id
            $id = (int)$id;
            $data = $form->find($id);

            if($data){

                if (isset($form_model['fields']) && $form_model['fields']){

                    $data = $this->getFieldData($form_model, $data);
                }

                return $data;
            }else{
                self::$errinfo = '不存在指定id数据';
                return false;
            }
        }
        
        self::$errinfo = '未提供查询条件或id';
        return false;

    }

    // 添加表单数据
    public function set_form_data($modelid,$data,$plugin_id =""){

        $plugin_id = $plugin_id ? $plugin_id : \App::get_plugin_id();

        $modelid = (int)$modelid;

        if(empty($modelid) || empty($data) || !is_array($data)){
            self::$errinfo = '参数错误';
            return false;
        }

        $model = get_model_data($plugin_id.'_model_form',$this->siteid);

        $form_model = $model[$modelid];

        $form = \Controller::plugin_model($plugin_id,$form_model['tablename']);

        $temp_data['ip'] = \client::get_user_ip();
        $temp_data['cid'] = '';
        $temp_data['status'] = 1;
        $temp_data['userid'] = 0;
        $temp_data['username'] = '';
        $temp_data['inputtime'] = $temp_data['updatetime'] = time();
        $temp_data['dealunqiue'] = $form_model['setting']['dealunqiue'];

        $data = array_merge($temp_data,$data);

        $data = $this->setFieldData($form_model,$data);

        // 字段数据
        if(isset($data['id']) && ((int)$data['id'] != 0)){
            // 更新内容
            $id = $form->set($data['id'], $data);
        }else{
            // 新增内容
            $id = $form->set(0, $data);
        }
        
        if($id){
            return $id;
        }else{
            self::$errinfo = '数据库插入数据失败';
            return false;
        }

    }

    // 实际处理方法，交由子类覆盖实现
    // public function doit($id){}

    // 设置用户标签
    public function set_user_tag($tags,$openid){

        $openids = array();

        $openids[] = $openid;

        $tag = new \Overtrue\Wechat\Tag($this->app_id, $this->app_secret);

        // 获取当前所有标签
        $tags = $tag->lists();

        // 获取二维码设定标签
        $all_tagname = explode(',', $tags);

        // 获取用户现有标签
        $u_tags = $tag->userTags($u_openid);

        // 先保证存在对应分组，并获取二维码设定标签的id值
        $temp_tags = array();

        foreach ($tags as $k => $v) {

            $temp_tags[$v['id']] = $v['name'];

        }

        $temp_all_tags = array();

        foreach ($all_tagname as $kk => $v) {
            
            if(count($temp_all_tags) <= 3 ){

                $key = array_search($v, $temp_tags);

                if($key !== false){
                    // 标签名称存在，获取对应的id
                    $temp_all_tags[] = $key;
                }else{
                    // 标签不存在，新建标签
                    $new_tag = $tag->create($v);

                    $temp_all_tags[] = $new_tag['id'];
                }
            }
            
        }

        // 直接把用户的标签全部去除
        foreach ($u_tags as $k => $v) {
            $tag->batchUntagUsers($openids,$v);
        }
        // 然后再批量增加上
        foreach ($temp_all_tags as $k => $v) {
            $tag->batchTagUsers($openids,$v);
        }
    }

    // 发送文本信息
    public function send_text($text,$openid){

        $text = htmlspecialchars_decode($text);

        if($text){
            return $this->staff->send($text)->to($openid);
        }else{
            self::$errinfo = '文本信息为空';
            return false;
        }

    }

    // 发送超级链接
    public function send_link($title,$url,$openid){

        if(empty($title)){
            self::$errinfo = '标题为空';
            return false;
        }

        if(empty($url)){
            self::$errinfo = '地址为空';
            return false;
        }

        if(empty($openid)){
            self::$errinfo = 'openid为空';
            return false;
        }

        $text = '<a href="'.$url.'">'.$title.'</a> ';
        return $this->staff->send($text)->to($openid);

    }

    // 发送图片
    public function send_image($image,$openid){

        $media = new \Overtrue\Wechat\Media($this->app_id, $this->app_secret);

        $media_image = $media->image(SITE_ROOT . $image); // 上传并返回媒体ID

        $message = \Overtrue\Wechat\Message::make('image')->media($media_image['media_id']);

        if($message){
            return $this->staff->send($message)->to($openid);
        }else{
            self::$errinfo = '上传媒体素材失败';
            return false;
        }
    }

    // 发送图文信息
    public function send_news($news,$openid){
        // $news 为一个二维数组，结构如下：
        /*
        $news = array(
            0 => array(
                'title'=>'标题',
                'image'=>'本地图片地址',
                'url'=>'链接地址',
            ),
            1 => array(
                'title'=>'标题',
                'image'=>'本地图片地址',
                'url'=>'链接地址',
            )
        );
        */

        if(!is_array($news)){
            self::$errinfo = '图文信息news参数错误';
            return false;
        }

        $temp_msg = array();
        

        foreach ($news as $k => $v) {
            if(isset($v['title'],$v['url']) && !empty($v['title']) && !empty($v['url'])){

                $v['image'] = $v['image'] ? $this->site_url.$v['image'] :'';

                $temp_msg[] = \Overtrue\Wechat\Message::make('news_item')->title($v['title'])->url($v['url'])->picUrl($v['image']);
            }
        }

        $message = \Overtrue\Wechat\Message::make('news')->items($temp_msg);

        if($message){
            return $this->staff->send($message)->to($openid);
        }else{
            self::$errinfo = '图文信息生成失败';
            return false;
        }
    }

    // 发送微信红包_未测试
    public function send_redpack($setting,$openid){

        if(empty($openid)){
            self::$errinfo = 'openid为空';
            return false;
        }

        if(empty($this->pay_id) || empty($this->pay_secret) || empty($this->pay_cert_path)){
            self::$errinfo = '商号ID或商号密匙或证书路径为空';
            return false;
        }
        // 第一步，设定微信信息

        // AppID(应用ID)wx2cff6f93af0fd7b6
        // AppSecret(应用密钥)40ce2f9599551f23d3735dd27e7d43db
        // 商号ID：1291002001
        // 密匙：40ce2f9599551f23d3735dd27e7d43db

        $business = new \Overtrue\Wechat\Payment\Business(
            $this->app_id, 
            $this->app_secret,
            $this->pay_id,
            $this->pay_secret
        );

        /**
        * 第 2 步：设置证书路径
        * CLIENTCERT_PATH即证书apiclient_cert.pem的路径
        * CLIENTkey_PATH即证书apiclient_key.pem的路径
        */
        // $luckm_path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR."luckmoneycert". DIRECTORY_SEPARATOR;

        $clientcert_path = $this->pay_cert_path . "apiclient_cert.pem";
        $clientkey_path = $this->pay_cert_path . "apiclient_key.pem";

        if(!file_exists($clientcert_path) || !file_exists($clientkey_path)){
            // 证书不存在
            self::$errinfo = '证书文件不存在';
            return false;
        }
        
        $business->setClientCert($clientcert_path);
        $business->setClientKey($clientkey_path);

        /**
        * 第 3 步：创建LuckMoney实例
        */
        $luckMoneyServer = new \Overtrue\Wechat\LuckMoney($business);

        /**
        * 第 4 步：要发送的红包相关数据（本代码以发送现金红包为例）
        */
        $lm_data = array();
        $lm_data['mch_billno'] = time();  // 红包记录对应的商户订单号

        $lm_data['send_name'] = $setting['send_name']?$setting['send_name']:'红包发送者名称';  // 红包发送者名称

        $lm_data['re_openid'] = $openid;  // 红包接收者的openId

        // 红包总额（单位为分），现金红包至少100，裂变红包至少300
        if(!empty($setting['total_amount']) && (int)$setting['total_amount'] >=100){

            $lm_data['total_amount'] = (int)$setting['total_amount'];

        }else{

            $lm_data['total_amount'] = 100;
        }

        if((int)$setting['total_num'] >= 3 && $lm_data['total_amount'] < 300){

            $lm_data['total_amount'] = 300;

        }
        $lm_data['total_num'] = $setting['total_num']?(int)$setting['total_num']:1;  // 现金红包时为1，裂变红包时至少为3

        $lm_data['wishing'] = $setting['wishing']?$setting['wishing']:'恭喜发财';  // 祝福语

        $lm_data['act_name'] = $setting['act_name']?$setting['act_name']:'红包活动名称'; // 活动名称

        $lm_data['remark'] = $setting['remark']?$setting['remark']:'红包活动备注'; // 红包备注

        /**
        * 第 5 步：发送红包
        * 第二个参数表示发送的红包类型，有现金红包（'CASH_LUCK_MONEY'）和裂变红包（'GROUP_LUCK_MONEY'）可选，红包工具类中已定义相关常量。
        */
        $lm_type = $lm_data['total_num'] > 1 ? \Overtrue\Wechat\LuckMoney::GROUP_LUCK_MONEY :\Overtrue\Wechat\LuckMoney::TYPE_CASH_LUCK_MONEY;

        $result = $luckMoneyServer->send($lm_data, $lm_type);

        // var_dump($result);
        if($result['result_code'] == 'SUCCESS'){
            // 发放红包成功，添加记录
            Log::write(var_export($result, true), 'wc-lm-suc');
            return true;
        }else{
            // 发放红包失败，添加记录
            Log::write(var_export($result, true), 'wc-lm-err');
            self::$errinfo = '红包发放失败';
            return false;
        }

    }

}