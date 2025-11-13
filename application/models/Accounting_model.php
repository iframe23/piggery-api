<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting_model extends CI_model
{
	
	public function __construct()
	{
		parent::__construct();
	}
	//query
	public function recalculate_account_sm($account_sm_id){
		$account_sm = $this->db->get_where('account_statements', array('account_sm_id' => $account_sm_id ))->row_array();

		$this->db->join('subjects', 'ON enrolled_subjects.subject_id = subjects.subject_id');
		$enrolled_subjects = $this->db->get_where('enrolled_subjects', array('account_sm_id' => $account_sm_id))->result_array();

		$data['total_units'] = 0;
		$data['total_lab_units'] = 0; 

		foreach ($enrolled_subjects as $row) {
			$data['total_units'] = $data['total_units'] + $row['units'];
			$data['total_lab_units'] = $data['total_lab_units'] + $row['lab_units'];
		}

		$rate = $this->db->get_where('rates', array('course_id' => $account_sm['course_id'] ))->row_array();

		$data['misc_fee'] = $rate['misc_fee'];
		$data['tuition_fee'] = $data['total_units'] * $rate['tuition_fee'];
		$data['lab_fee'] = $data['total_lab_units'] * $rate['laboratory_fee'];
		$data['total_amount'] = $data['tuition_fee'] + $data['lab_fee'] + $data['misc_fee'];
		$data['per_grading'] = $data['total_amount'] / 4;
		$data['balance'] = $data['total_amount'] - $account_sm['down_payment'];

		$this->db->where('account_sm_id', $account_sm_id)->update('account_statements', $data);
	}
}