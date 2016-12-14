<?php

class BlockController extends Admin {
    
    private $block;
    private $type;
    
    public function __construct() {
        parent::__construct();
        $this->block = $this->plugin_model($this->namespace,'block');
        $this->type  = array(1=>lang('a-fnx-92'), 2=>lang('a-fnx-93'), 3=>lang('a-fnx-94'),4=>lang('a-fnx-95'));
        $this->view->assign('type', $this->type);
    }
    
    public function indexAction() {
        if ($this->post('submit_del')) {
            foreach ($_POST as $var=>$value) {
                if (strpos($var, 'del_')!==false) {
                    $id = (int)str_replace('del_', '', $var);
                    $this->delAction($id, 1);
                }
            }
            $this->adminMsg($this->getCacheCode('block') . lang('success'), url('admin/block/'), 3, 1, 1);
        }
        $page     = (int)$this->get('page');
        $page     = (!$page) ? 1 : $page;
        $pagelist = $this->instance('pagelist');
        $pagelist->loadconfig();
        $total    = $this->block->count('block', null, 'site=' . $this->siteid);
        $pagesize = isset($this->site['SITE_ADMIN_PAGESIZE']) && $this->site['SITE_ADMIN_PAGESIZE'] ? $this->site['SITE_ADMIN_PAGESIZE'] : 8;
        $data     = $this->block->where('site=' . $this->siteid)->page_limit($page, $pagesize)->order(array('id DESC'))->select();
        $pagelist = $pagelist->total($total)->url(url('admin/block/index', array('page'=>'{page}')))->num($pagesize)->page($page)->output();
        $this->view->assign(array(
            'list'     => $data,
            'pagelist' => $pagelist,
        ));
        $this->view->display('admin/block_list');
    }
    
    public function addAction() {
        if ($this->post('submit')) {
            $data = $this->post('data');
            if (empty($data['name'])) $this->adminMsg(lang('a-blo-4'));
            if(is_array($data['setting'])){
                $data['setting'] = array2string($data['setting']);
            }else if($data['setting'] == null){
                $data['setting'] = "";
            }
            $data['site'] = $this->siteid;
            $this->block->insert($data);
            // $this->adminMsg($this->getCacheCode('block') . lang('success'), url($this->namespace .'/admin_block/index'), 3, 1, 1);
            $this->redirect(url($this->namespace .'/admin_block/cache'));
        }
        $this->view->display('admin/block_add');
    }

    public function setAction() {
        $id   = (int)$this->get('id');
        $data = $this->block->find($id);
        if (empty($data)) $this->adminMsg(lang('a-blo-5'));
        if ($this->post('submit')) {
            unset($data);
            $data = $this->post('data');
            if (empty($data['name'])) $this->adminMsg(lang('a-blo-4'));
            if(is_array($data['setting'])){
                $data['setting'] = array2string($data['setting']);
            }else if($data['setting'] == null){
                $data['setting'] = "";
            }
            $data['site'] = $this->siteid;
            $this->block->update($data,'id='.$id);
            // $this->adminMsg($this->getCacheCode('block') . lang('success'), url($this->namespace .'/admin_block/index'), 3, 1, 1);
            $this->redirect(url($this->namespace .'/admin_block/cache'));
        }
        $data['setting'] = string2array($data['setting']);
        $data['content'] = string2array($data['content']);
        $this->view->assign('data', $data);
        $this->view->display('admin/block_add');
    }
    
    public function editAction() {
        $id   = (int)$this->get('id');
        $data = $this->block->find($id);
        if (empty($data)) $this->adminMsg(lang('a-blo-5'));
        $data['setting'] = string2array($data['setting']);
        $data['content'] = string2array($data['content']);
        if ($this->post('submit')) {
            unset($data);
            $data = $this->post('data');
            // if (empty($data['type'])) $this->adminMsg(lang('a-blo-3'));
            if(is_array($data['content'])){
                $data['content'] = array2string($data['content']);
            }
            // if (empty($data['name']) || (empty($data['content']) && $data['content'] != '0')) $this->adminMsg(lang('a-blo-4'));
            $data['site'] = $this->siteid;
            $this->block->update($data, 'id=' . $id);
            // $this->adminMsg($this->getCacheCode('block') . lang('success'), url($this->namespace .'/admin_block'), 3, 1, 1);
            $this->redirect(url($this->namespace .'/admin_block/cache'));
        }
        $this->view->assign('data', $data);
        $this->view->display('admin/block_edit');
    }
    
    public function delAction($id=0, $all=0) {
        if (!auth::check($this->roleid, 'block-del', 'admin')) $this->adminMsg(lang('a-com-0', array('1'=>'block', '2'=>'del')));
        $id  = $id  ? $id  : (int)$this->get('id');
        $all = $all ? $all : $this->get('all');
        $this->block->delete('site=' . $this->siteid . ' AND id=' . $id);
        // $all or $this->adminMsg($this->getCacheCode('block') . lang('success'), url('admin/block/index'), 3, 1, 1);
        $all or $this->redirect(url($this->namespace .'/admin_block/cache'));;
    }
    
    public function cacheAction($show=0) {
        $list = $this->block->findAll();
        $data = array();
        foreach ($list as $t) {
            $data[$t['id']] = $t;
        }
        $this->cache->set($this->namespace.'_block', $data);
        $show or $this->adminMsg(lang('a-update'), url($this->namespace .'/admin_block/index'), 3, 1, 1);
    }
    
    /**
     * 加载调用代码
     */
    public function ajaxviewAction() {
        $id   = (int)$this->get('id');
        $data = $this->block->find($id);
        if (empty($data)) exit(lang('a-blo-5'));
        $msg  = "<textarea id='block_" . $id . "' style='font-size:12px;width:100%;height:80px;overflow:hidden;'>";
        $msg .= "<!--" . $data['name'] . "-->\n{php \$block_data = pblock(". $this->namespace .",". $id . ");}\n<!--" . $data['name'] . "-->";
        $msg .= "\n<!-- 调用方式{\$block_data['title']} --></textarea>";

        echo $msg;
    }
}