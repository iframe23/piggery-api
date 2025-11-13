<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helper_model extends CI_model
{
	
	public function __construct()
	{
		parent::__construct();
	}
	//query
	public function query_last_id($id_field, $table){
		$last_row = $this->db->select($id_field)->order_by($id_field,"desc")->limit(1)->get($table)->row_array();
		return $last_row;
	}

	//insert
}