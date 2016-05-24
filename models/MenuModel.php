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
}