<?php

class IndexController extends Plugin {

	public function __construct() {
        parent::__construct();
	}
	
	public function indexAction() {
		if (file_exists(SITE_ROOT . 'cache/index/' . $this->siteid . '.html')) {
			echo file_get_contents(SITE_ROOT . 'cache/index/' . $this->siteid . '.html');
			exit;
		}
	    $this->view->assign(array(
	        'indexc'           => 1, //首页标识符
	        'meta_title'       => $this->site['SITE_TITLE'],
	        'meta_keywords'    => $this->site['SITE_KEYWORDS'], 
	        'meta_description' => $this->site['SITE_DESCRIPTION'],
	    ));
		$this->view->display('index');
	}
	
}