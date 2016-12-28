<?php

class PluginmenuModel extends Model{
	
	public function get_primary_key(){
		return $this->primary_key = "id";
	}

	public function set($id ,$data){
		$data['site'] = APP::get_site_id();
		if($id){
			$this->update($data,'id=' . $id);
			return true;
		}
		$this->insert($data);
		if ($this->get_insert_id()) return true;
		return false;
	}

	public function del($id){
		$data = $this->where('site=' . APP::get_site_id() . ' AND parentid=' . $id)->select();
		//var_dump($data);
		if ($data){
			foreach ($data as $d) {
				$this->del($d['id']);
			}
			$this->delete('id='.$id.' AND site=' . APP::get_site_id());
			return true;
		}else{
			$this->delete('id='.$id.' AND site=' . APP::get_site_id());
			return true;
		}
	}

	public function repair($parentid = 0) {
		$data = $this->where('site=' . APP::get_site_id() . ' AND parentid=' . $parentid)->order('listorder ASC')->select();
		foreach ($data as $t) {
			// 检查该栏目下是否有子栏目
			$id = $t['id'];
			$parentid = $t['parentid'];

			// 当前栏目的所有父栏目ID(arrparentid)
			$arrparentid = array();
			foreach ($data as $s) {
				$arrparentid[] = $s['id'];
			}

			// 组合父栏目ID
			$arrparentid = implode(',',$arrparentid);

			// 查询子栏目
			$s_data = $this->where('parentid=?',$t['id'])->order('listorder ASC')->select();
			if ($s_data) {//如果存在子栏目
				// 当前栏目的所有子栏目ID($arrchildid)
				$arrchildid = array();
				foreach ($s_data as $s) {
					$arrchildid[] = $s['id'];
				}

				// 组合子栏目ID
				$arrchildid = implode(',', $arrchildid);
				$this->update(array('child'=>1, 'arrchildid' => $arrchildid, 'arrparentid'=> $arrparentid), 'id=' . $id);
				$this->repair($id);
			}else{ //如果没有子栏目
				$this->update(array('child'=>0, 'arrchildid' => $arrchildid, 'arrparentid'=> $arrparentid),'id='.$id);
			}
		}
	}

	public function cache($ismenu = false,$namespace='',$site_id=0){
		$namespace = $namespace? $namespace : APP::get_plugin_id();
		$site_id = $site_id? $site_id : APP::get_site_id();
		if($ismenu){
			$submenu = $this->where('id > 0')->where('parentid=0')->where('ismenu=1')->order('listorder ASC, id ASC')->select();
		}else{
			$submenu = $this->where('id > 0')->where('parentid=0')->order('listorder ASC, id ASC')->select();
		}
        if($submenu){
            foreach ($submenu as $k => $v) {
                $subid = $v['id'];
                if($ismenu){
                	$data[$v['id']] = $this->where('id > 0')->where('parentid='.$subid)->where('ismenu=1')->order('listorder ASC, id ASC')->select();	
                }else{
                	$data[$v['id']] = $this->where('id > 0')->where('parentid='.$subid)->order('listorder ASC, id ASC')->select();
                }
                
                $data[$v['id']]['name'] = $v['name'];
                $data[$v['id']]['icon'] = $v['icon'];
            }
        }
        // 写入缓存文件
        if($ismenu){
        	$cache = new cache_file();
        	$cache->set($namespace . '_menu_' . $site_id, $data);
        }
        
        return $data;
	}
}