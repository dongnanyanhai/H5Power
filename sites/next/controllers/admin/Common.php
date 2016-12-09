<?php

class Admin extends Common {
    
    protected $user;
    protected $roleid;
    protected $userinfo;
    protected $site_url;

    public function __construct() {
        parent::__construct();
        $this->user = $this->model('user');
        $this->isAdminLogin();
        if (!auth::check($this->roleid, $this->namespace . '/' .$this->controller . '/' . $this->action, $this->namespace)) {
            $this->adminMsg(lang('a-com-0', array('1' => $this->controller, '2' => $this->action)));
        }
        $sites  = App::get_site();
        $this->site_url = 'http://' . $sites[$this->siteid]['DOMAIN'];
        $this->view->assign(array(
            'userinfo'  => $this->userinfo,
            'site_url'  => $this->site_url
        ));
        $this->adminLog();
        
        if (file_exists(SITE_ROOT . './config/variable.ini.php')){
            $variable = $this->load_config('variable');
            $this->view->assign($variable);
        }
    }
    // 获取系统后台菜单
    protected function get_sys_menu($ismenu=true){
        $menu = array();
        $be_menu = $this->model('menu');
        $be_menu_data = $this->model('menu_data');
        // 更新后台菜单
        $be_menu_data->repair();
        // 获取顶部菜单
        $top_menu = $be_menu->cache($ismenu);
        // var_dump($top_menu);
        if($top_menu && is_array($top_menu)){
            $menu['top'] = $top_menu;
            $list_menu = $be_menu_data->cache($top_menu,$ismenu);
            if($list_menu && is_array($list_menu)){
                $menu['list'] = $list_menu;
            }
        }

        return $menu;
    }

    protected function get_plugins_menu($plugins,$menu,$ismenu=true){

        $last_menuid = end($menu['top'])['menuid'];
        $menu_data = null;
        // 获取数据库前缀
        $params = Controller::load_config('database');
        $prefix = ($params['prefix']) ? trim($params['prefix']) : '';

        $temp_menu = array(
            "site" => $this->siteid,
            "ismenu" => "1",
        );

        foreach ($plugins as $k => $v) {

            $last_menuid = $last_menuid + 1;
            $temp_menu["menuid"] = $last_menuid;
            $temp_menu["name"] = $v['name'];
            $temp_menu["url"] = $v['url']?$v['url']:$v['dir'].'/admin_category/index';
            $temp_menu["namespace"] = $v['dir'];
            $temp_menu["select"] = 1;
            // 获取插件菜单
            $plugin_menu = array();
            $plugin_menu = $this->cache->get($v['dir'].'_menu_'.$this->siteid);

            if(!$plugin_menu || !is_array($plugin_menu)){
                // 阿海注：
                // 不得不承认，下面这个转换模型数据表前缀的做法非常不合理
                // 这意味着，实际上在实例化插件的菜单类时，只用了第一个插件的菜单类代码
                // 后面其他插件的菜单代码实际上并不会被载入并且用到
                // 但我暂时没有想到其他更好的办法来解决，只能说，所有插件的菜单类代码一定要一致！！！
                if($menu_data == null){
                    $menu_data = $this->plugin_model($v['dir'],'pluginmenu');
                }else{
                    $menu_data->prefix = $prefix . $v['dir'] . "_";
                }
                $plugin_menu = $menu_data->cache($ismenu,$v['dir'],$this->siteid);
                
            }
            if(!$v['select']){
                foreach ($plugin_menu as $nk => $nv) {
                    foreach ($nv as $lk => $lv) {
                        if(is_array($lv) && $lv['url'] == $temp_menu["url"]){
                            $temp_menu["select"] = $lv['id'];
                        }
                    }
                }
            }else{
                $temp_menu["select"] = $v['select'];
            }
            $menu['list'][$last_menuid] = $plugin_menu;
            $menu['top'][$last_menuid] = $temp_menu;
            unset($plugin_menu);
        }
        return $menu;
    }
    
    /**
     * 系统默认菜单
     */
    protected function sysMenu() {

        $last_menuid = 0;
        $menu = array();
        
        $menu['top']  = $this->cache->get('menu_top_'.$this->siteid);
        $menu['list']  = $this->cache->get('menu_list_'.$this->siteid);
        if(!$menu['top'] || !$menu['list']){
            $menu = $this->get_sys_menu(true);
        }

        $plugins = $this->cache->get('plugin');
        
        if($plugins && is_array($plugins)){
            $menu = $this->get_plugins_menu($plugins,$menu,true);
        }
        return $menu;
    }
    
    /**
     * 获取具有审核权限的栏目
     */
    protected function getVerifyCatid() {
        if ($this->userinfo['roleid'] == 1) return false;
        $catid = array();
        foreach ($this->cats as $t) {
            if ($t['typeid'] == 1 && $t['child'] == 0 && $this->verifyPost($t['setting'])) $catid[] = $t['catid'];
        }
        return empty($catid) ? false : $catid;
    }
    
    /**
     * 投稿审核权限判断
     */
    protected function verifyPost($data) {
        if ($this->userinfo['roleid'] == 1) return false;
        if (isset($data['verifypost']) && $data['verifypost'] && $data['verifyrole'] && !in_array($this->userinfo['roleid'], $data['verifyrole'])) {
            return true;
        }
        return false;
    }
    
    /**
     * 后台投稿权限判断
     */
    protected function adminPost($data) {
        if (isset($data['adminpost']) && $data['adminpost'] && $data['rolepost'] && in_array($this->userinfo['roleid'], $data['rolepost'])) {
            return true;
        } elseif (isset($data['siteuser']) && $data['siteuser'] && $data['site'] && in_array($this->siteid, $data['site'])) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 后台登陆检查
     */
    protected function isAdminLogin($namespace = 'admin', $controller = null) {
        if ($this->namespace != $namespace) return false;
        if ($controller && $this->controller != $controller) return false;
        if ($this->namespace == 'admin' && $this->controller == 'login') return false;
        if ($this->session->is_set('user_id')) {
            $userid = $this->session->get('user_id');
            $this->userinfo = $this->user->userinfo($userid);
            if ($this->userinfo) {
                $this->roleid = $this->userinfo['roleid'];
                if (empty($this->userinfo['site']) || $this->userinfo['site'] == $this->siteid) return false;
            }
        }
        $url = $this->namespace == 'admin' && isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != 's=' . ADMIN_NAMESPACE ? url('admin/login', array('url' => urlencode(SITE_PATH . ENTRY_SCRIPT_NAME . '?' . $_SERVER['QUERY_STRING']))) : url('admin/login');
        $this->redirect($url);
    }
    
    /**
     * 指定用户组的操作菜单
     */
    protected function optionMenu($roleid = 0) {
        $menu     = $this->sysMenu();
        $roleid   = $roleid ? $roleid : $this->roleid;
        //加载用户自定义菜单
        $usermenu = string2array($this->userinfo['usermenu']);
        // var_dump($usermenu);
        // exit();
        $member_menu = 0;
        $member_k = 0;
        foreach ($menu['list'] as $k => $v) {
            # code...
            foreach ($v as $kk => $vv) {
                if($vv['name'] == '快捷菜单'){
                    $member_menu = $k;
                    $member_k = $kk;
                }
            }
        }
        
        if ($roleid == 1 && $member_menu) {
            if (!empty($usermenu)) {
                foreach ($usermenu as $k => $t) {
                    $t['sys'] = 1;
                    $t['id'] = '999' . $k;
                    $menu['list'][$member_menu][$member_k]['999' . $k] = str_replace('{site}', $this->siteid, $t);                 
                }
            }
            return $menu;
        }


        if (!empty($usermenu) && $member_menu) {
            foreach ($usermenu as $k => $t) {
                $t['id'] = '999' . $k;
                $menu['list'][$member_menu][$member_k]['999' . $k] = $t;
            }
        }

        foreach ($menu['list'] as $id => $t) {
            if ($id == 1) continue;
            foreach ($t as $oid => $v) {
                foreach ($v as $iid => $r) {
                    //内菜单控制
                    if(is_array($r)){
                        if ($r['url'] && !$this->checkUserAuth($r['url'], $roleid)) {
                            unset($menu['list'][$id][$oid][$iid]);
                            if ($r['url'] == $menu['top'][$id]['url']) {
                                $now_first_menu = reset($menu['list'][$id][$oid]);
                                if(is_array($now_first_menu)){
                                    $menu['top'][$id]['url'] = $now_first_menu['url'];
                                    $menu['top'][$id]['select'] = $now_first_menu['id'];
                                }else{
                                    $menu['top'][$id]['url'] = 'admin/index/main';
                                }
                            }
                        }
                    }
                }
                //如果子菜单全部被删除
                $has_sub_menu = false;
                foreach ($menu['list'][$id][$oid] as $c_k => $c_v) {
                    if(is_array($c_v)){
                        $has_sub_menu = true;
                        break;
                    }
                }
                if ($has_sub_menu == false) unset($menu['list'][$id][$oid]);
            }
        }
        foreach ($menu['top'] as $id => $t) {
            if ($id == 1) continue;
            if (empty($menu['list'][$id])) unset($menu['top'][$id]);
        }
        return $menu;
    }
    
    /**
     * 验证角色是否对指定菜单有操作权限
     */
    protected function checkUserAuth($option, $roleid = 0) {
        $roleid    = $roleid ? $roleid : $this->roleid;
        $data_role = $this->user->roleinfo($roleid);
        $privates     = string2array($data_role['privates']);
        if (!$privates) return false;
        if(in_array($option,$privates)){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 后台操作日志记录
     */
    protected function adminLog() {
        if ($this->namespace != 'admin') return false;
        if (!isset($_POST) || empty($_POST)) return false;
        //跳过不要记录的操作
        if ($this->site['SITE_ADMINLOG'] == false) return false;
        $skip    = require CONFIG_DIR . 'auth.skip.ini.php';
        if (stripos($this->action, 'ajax') !== false) return false;
        $skip    = $skip['admin'];
        $skip[]  = 'index-log';
        if (in_array($this->controller, $skip)) {
            return false;
        } elseif (in_array($this->controller . '-' . $this->action, $skip)) {
            return false;
        }
        //记录操作日志
        $options = require CONFIG_DIR . 'auth.option.ini.php';
        $option  = $options[$this->controller];
        if (empty($option)) return false;
        $now     = $option['option'][$this->action];
        $ip      = client::get_user_ip();
        if (SYS_DOMAIN) $_SERVER['REQUEST_URI'] = str_replace('/' . SYS_DOMAIN, '', $_SERVER['REQUEST_URI']);
        $pathurl = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : $_SERVER['REQUEST_URI'];
        $options = lang($option['name']) . ' - ' . lang($option['option'][$this->action]);
        if ($this->post('submit')) {
            $options  .= ' - ' . lang('a-com-2');
        } elseif (($this->post('submit_order'))) {
             $options .= ' - ' . lang('a-com-3');
        } elseif (($this->post('submit_del'))) {
             $options .= ' - ' . lang('a-com-4');
        } elseif (($this->post('submit_status_1'))) {
             $options .= ' - ' . lang('a-com-5');
        } elseif (($this->post('submit_status_0'))) {
             $options .= ' - ' . lang('a-com-6');
        } elseif (($this->post('submit_status_2'))) {
             $options .= ' - ' . lang('a-com-7');
        } elseif (($this->post('submit_status_3'))) {
             $options .= ' - ' . lang('a-com-8');
        } elseif (($this->post('submit_move'))) {
             $options .= ' - ' . lang('a-com-9');
        } elseif (($this->post('delete'))) {
             $options .= ' - ' . lang('a-com-10');
        }
        $data = array(
            'ip'            => $ip,
            'param'         => $pathurl,
            'userid'        => $this->userinfo['userid'],
            'action'        => $this->action,
            'options'       => $options,
            'username'      => $this->userinfo['username'],
            'controller'    => $this->controller,
            'optiontime'    => time()
        );
        $dir     = SITE_ROOT . 'cache' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        $file    = $dir . date('Ymd') . '.log';
        if (!is_dir($dir)) mkdir($dir, 0777);
        $content = file_exists($file) ? file_get_contents($file) : '';
        $content = serialize($data) . PHP_EOL . $content;
        file_put_contents($file, $content, LOCK_EX);
    }
    
    /**
     * 删除目录及文件
     */
    protected function delDir($filename) {
        if (empty($filename)) return false;
        if (is_file($filename) && file_exists($filename)) {
            unlink($filename);
        } else if ($filename != '.' && $filename != '..' && is_dir($filename)) {
            $dirs = scandir($filename);
            foreach ($dirs as $file) {
                if ($file != '.' && $file != '..') $this->delDir($filename . '/' . $file);
            }
            rmdir($filename);
        }
    }
    
    /**
     * 生成栏目html
     */
    protected function createCat($cat, $page = 1) {
        if ($cat['typeid'] == 3) return false;
        if ($cat['setting']['url']['use'] == 0 || $cat['setting']['url']['tohtml'] == 0 || $cat['setting']['url']['list'] == '') return false;
        $url = substr($this->getCaturl($cat, $page), strlen(self::get_base_url())); //去掉域名部分
        if (substr($url, -5) != '.html') { 
            $file   = 'index.html'; //文件名 
            $dir    = $url; //目录
        } else {
            $file   = basename($url);
            $dir    = str_replace($file, '', $url);
        }
        $this->mkdirs($dir);
        $dir        = substr($dir, -1) == '/' ? substr($dir, 0, -1) : $dir;
        $htmlfile   = $dir ? $dir . '/' . $file : $file;
        ob_start();
        $this->view->setTheme(true);
        $_GET['page']   = $page;
        $_GET['catid']  = $cat['catid'];
        $class  = 'ContentController';
        $action = 'listAction';
        App::load_file(CONTROLLER_DIR . $class . '.php');
        $app = new $class();
        $app->$action();
        $this->view->setTheme(false);
        if (!file_put_contents($htmlfile, ob_get_clean(), LOCK_EX)) $this->adminMsg(lang('a-com-11', array('1' => $htmlfile)));
        $htmlfiles   = $this->cache->get('html_files');
        $htmlfiles[] = $htmlfile;
        if (empty($page) || $page == 1) {
            $onefile = str_replace('{page}', 1, substr($this->getCaturl($cat, '{page}'), strlen(self::get_base_url())));
            @copy($htmlfile, $onefile);
            $htmlfiles[] = $onefile;
        }
        $this->cache->set('html_files', $htmlfiles);
        if (strpos($cat['content'], '{-page-}') !== false) {
            $content  = explode('{-page-}', $cat['content']);
            $pageid   = count($content) >= $page ? ($page - 1) : (count($content) - 1);
            $page_id  = 1;
            $pagelist = array();
            $cat['content'] = $content[$pageid];
            foreach ($content as $t) {
                $pagelist[$page_id] = getCaturl($cat, $page_id);
                $page_id ++ ;
            }
            if (isset($pagelist[$page+1])) $this->createCat($cat, $page + 1);
        }
        return true;
    }
    
    /**
     * 获取更新缓存JS代码
     */
    protected function getCacheCode($c, $a = 'cache') {
        return '<script type="text/javascript" src="' . url('admin/index/updatecache', array('cc' => $c, 'ca' => $a)) . '"></script>';
    }
    
}