<?php

class Member_testModel extends Model {

    public function __construct() {
        $this->is_plugin_model = true;
        parent::__construct();
    }

    public function get_primary_key() {
        return $this->primary_key = 'id';
    }

    public function get_fields() {
        return $this->get_table_fields();
    }

}