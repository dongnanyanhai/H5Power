<?php

class Form_1_qqdataModel extends FormModel {

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