<?php

/**
* menu 模型
*/
class MenuModel extends Model{
	
	public function get_primary_key(){
		return $this->primary_key = "menuid";
	}

	public function set($menuid, $data){
		$data['site'] = APP::get_site_id();
		if($menuid){
			$this->update($data, 'menuid=' . $menuid);
			return true;
		}
		$this->insert($data);
		if($this->get_insert_id()) return true;
		return false;
	}

	public function del($menuid){
		$this->delete('menuid='.$menuid.' AND site=' . APP::get_site_id());
		$table = $this->prefix.'menu_data';
		$this->query('delete from '.$table . ' where menuid='.$menuid);
	}

	public function cache($ismenu=true){
		$topmenu = array();
		if($ismenu){
			$menu = $this->where('site=' . APP::get_site_id())->where('ismenu=1')->select();
		}else{
			$menu = $this->where('site=' . APP::get_site_id())->select();
		}
		
		if($menu && is_array($menu)){
			foreach ($menu as $k => $v) {
				$topmenu[$v['menuid']] = $v;
			}
			$cache = new cache_file();
			if($ismenu){
				$cache->set('menu_top_' . APP::get_site_id(), $topmenu);
			}
			
			return $topmenu;
		}else{
			return false;
		}
	}
}