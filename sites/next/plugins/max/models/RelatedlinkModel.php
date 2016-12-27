<?php

class RelatedlinkModel extends Model {

    public function __construct() {
        parent::__construct();
    }
    
	public function get_primary_key() {
		return $this->primary_key = 'id';
	}
	
}