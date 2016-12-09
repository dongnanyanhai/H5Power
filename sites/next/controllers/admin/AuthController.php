<?php

class AuthController extends Admin {
	public $role;
    public function __construct() {
		parent::__construct();
		$this->role = $this->user->get_role_list();
		
	}
	
	public function indexAction() {
	    $this->view->assign('list', $this->role);
		$this->view->display('admin/auth');
	}
	
	public function listAction() {
	    $roleid    = $this->get('roleid');
	    if (!$roleid) $this->adminMsg(lang('a-aut-0'));

        $privates = array();
        if($this->role[$roleid]['privates'] != ''){
        	$privates = string2array($this->role[$roleid]['privates']);
        }

        $data_auth = array();
		$data_auth = $this->get_sys_menu(false);
		$plugins = $this->cache->get('plugin');

		if($plugins && is_array($plugins)){
			$data_auth = $this->get_plugins_menu($plugins,$data_auth,false);
		}


        if ($this->post('submit')) {
            if ($roleid == 1) $this->adminMsg(lang('a-aut-1'));
            $auth = $_POST['auth'];
            if(isset($_POST['auth']) && !empty($_POST['auth'])){
            	$re = $this->user->set_role_privates($roleid,array2string($_POST['auth']));
            	if($re){
            		// 更新成功
            		$this->adminMsg($this->getCacheCode('auth') . lang('success'), url('admin/auth/list', array('roleid'=>$roleid)), 3, 1, 1);
            	}else{
            		// 更新失败
            		$this->adminMsg($this->getCacheCode('auth') . lang('failure'), url('admin/auth/list', array('roleid'=>$roleid)), 3, 1, 1);
            	}
            }
        }
        $this->view->assign(array(
            'roleid' => $roleid,
            'privates'   => $privates,
            'data'   => $data_auth,
        ));
		$this->view->display('admin/auth_list');
	}
	
	public function addAction() {
	    if ($this->post('submit')) {
	        $rolename    = $this->post('rolename');
			if (empty($rolename)) $this->adminMsg(lang('a-aut-2'));
	        $description = $this->post('description');
	        $result      = $this->user->set_role(0, $rolename, $description);
	        if ($result == 1) {
	            $this->adminMsg(lang('success'), url('admin/auth'), 3, 1, 1);
	        } elseif ($result == 0) {
	            $this->adminMsg(lang('a-aut-3'));
	        } else {
	            $this->adminMsg(lang('a-aut-4'));
	        }
	    }
	    $this->view->display('admin/auth_add');
	}
	
    public function editAction() {
	    if ($this->post('submit')) {
	        $roleid      = $this->post('roleid');
	        $rolename    = $this->post('rolename');
			if (empty($rolename)) $this->adminMsg(lang('a-aut-2'));
	        $description = $this->post('description');
	        $result      = $this->user->set_role($roleid, $rolename, $description);
	        if ($result == 1) {
	            $this->adminMsg(lang('success'), url('admin/auth'), 3, 1, 1);
	        } elseif ($result == 0) {
	            $this->adminMsg(lang('a-aut-3'));
	        } else {
	            $this->adminMsg(lang('a-aut-4'));
	        }
	    }
        $roleid = $this->get('roleid');
        if (!$roleid) $this->adminMsg(lang('a-aut-0'));
        $row    = $this->user->roleinfo($roleid);
        $this->view->assign('data', $row);
	    $this->view->display('admin/auth_add');
	}
	
	public function delAction() {
	    $roleid = $this->get('roleid');
        if (!$roleid) $this->adminMsg(lang('a-aut-0'));
        if ($this->userinfo['roleid'] == $roleid) $this->adminMsg(lang('a-aut-5'));
        if ($roleid == 1) $this->adminMsg(lang('a-aut-6'));
        $this->user->del_role($roleid);
        $this->adminMsg($this->getCacheCode('auth') . lang('success'), url('admin/auth'), 3, 1, 1);
	}
	
	public function cacheAction($show=0) {
        //所有角色拥有的权限
        $data_role = require CONFIG_DIR . 'auth.role.ini.php';
        $role      = $this->user->get_role_list();
        $roleids   = array(); //角色ID表
        foreach ($role as $t) {
            $roleids[] = $t['roleid'];
        }
        foreach ($data_role as $id=>$t) {
            if (!in_array($id, $roleids)) {
                //检查角色不存在就删除该角色的配置
                unset($data_role[$id]);
            }
        }
        $content = "<?php" . PHP_EOL . "if (!defined('IN_FINECMS')) exit();" . PHP_EOL . PHP_EOL . "/**" . PHP_EOL . " * 用户权限配置信息" . PHP_EOL . " */" . PHP_EOL
        . "return " . var_export($data_role, true) . ";";
        file_put_contents(CONFIG_DIR . 'auth.role.ini.php', $content);
        $show or $this->adminMsg(lang('a-update'), '', 3, 1, 1);
	}
}