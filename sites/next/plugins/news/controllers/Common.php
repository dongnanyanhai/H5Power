<?php

/**
 * 文件名称: Common.php for v1.6 +
 * 插件控制器公共类
 */

class Plugin extends Common {
    
    protected $plugin;   //插件模型
    protected $data;     //插件数据
    protected $viewpath; //视图目录

    protected $cats;
    protected $content;
    protected $cats_dir;
    protected $category;
    
    public function __construct() {
        parent::__construct();
        // $this->plugin   = $this->model('plugin');
        $this->plugin   = $this->cache->get("plugin");
        // $this->data     = $this->plugin->where('dir=?', $this->namespace)->select(false);
        $this->data     = $this->plugin[$this->namespace];
        if (empty($this->data))     $this->adminMsg('插件尚未安装', url('admin/plugin'));
        if ($this->data['disable']) $this->adminMsg('插件尚未开启', url('admin/plugin'));

        $this->category      = $this->model('category');
        $this->cats          = $this->get_category();
        $this->cats_dir      = $this->get_category_dir();
        $this->content       = $this->model('content_' . $this->siteid);

        // $this->viewpath = SITE_PATH . $this->site['PLUGIN_DIR'] . '/' . $this->data['dir'] . '/views/';
        $this->viewpath = PLUGIN_DIR . $this->site['PLUGIN_DIR'] . '/' . $this->data['dir'] . '/views/';
        $this->assign(array(
            'cats'       => $this->cats,
            'viewpath'  => $this->viewpath,
            'pluginid'  => $this->data['pluginid'], 
        ));
        date_default_timezone_set(SYS_TIME_ZONE);
    }
    
    
}