<?php

class Content_1_productModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_primary_key() {
        return $this->primary_key = 'id';
    }

    public function get_fields() {
        return $this->get_table_fields();
    }

}