<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_model
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function query_logged_user_token($data){
		return $this->db->get_where('users', array('username' => $data['username'], 'token' => $data['token'] ));
	}

	public function username_exist($username){
		$this->db->select('username');
		$query = $this->db->get_where('users' , array('username' => $username));

		return $query;
	}

	public function email_exist($email){
		$this->db->select('email');
		$query = $this->db->get_where('users' , array('email' => $email));

		return $query;
	}

	public function insert_register($userdata){
		$this->db->insert('users', $userdata);
	}

	public function query_user_by_username_password($data){
		$query = $this->db->get_where('users', array('username' => $data['username'], 'password' => $data['password']));

		return $query;
	}

	public function check_log_session_if_false(){
		if (!isset($_SESSION['user_logged']) && $_SESSION['user_logged'] != true) {
			redirect('login');
		} 
	}

	public function check_log_session_if_true(){
		if (isset($_SESSION['user_logged']) && $_SESSION['user_logged'] == true) {
			redirect('dashboard');
		} 
	}

	public function is_logged(){
		if (!isset($_SESSION['user_logged']) && $_SESSION['user_logged'] != true) {
			return false;
		} else if (isset($_SESSION['user_logged']) && $_SESSION['user_logged'] == true) {
			return true;
		}
	}

	public function close_posting(){
		$curr_date = date('Y-m-d');
		$data['status'] = 'Closed';
	}

	public function admin_only($role_id){
		if ($role_id != 1) {
			show_404();
		}
	}

	public function supplier_only($role_id){
		if ($role_id != 2) {
			show_404();
		}
	}

	public function admin_personnel_only($role_id){
		if ($role_id != 1 && $role_id != 3) {
			show_404();
		}
	}

	public function bid_result($result){
		if ($result == 'Awarded') {
			echo "Winning Bid Result";
		} else {
			echo "Lost Bid Result";
		}
	}

	public function status_info($status){
		$user = $this->user_model->query_logged_user_info()->row();
		
		$a = "";

		if ($status == "Closed") {
			$a = "(Ready for Selection of Awardee)";
		} elseif ($status == "On-processing") {
			if ($user->role_id == 2) {
				$a = "(Coordinate with The BAC Office for processing.)";
			} else {
				$a = "(This Procurement is on processing now.)";
			}
		} elseif ($status == "Completed") {
			$a = "(Transaction has already been completed and disbursed.)";
		}

		return $status.' '.$a; 
	}

	public function get_modified_status($date){
		$n = strtotime($date);

		if ($n == 0) {
			return 'Not yet Modified';
		} else {
			return date('M g, Y', $n);
		}
	}

	public function role_restriction($roles =  array()){
		$logged_user = $this->user_model->query_logged_user_info()->row();

		if (!is_array($roles)) {
			if ($roles != $logged_user->role_id) {
				show_404();
			}
		} else {
			$l = count($roles);
			$role_count = 0;

			for ($i=0; $i < $l; $i++) { 
				if ($roles[$i] == $logged_user->role_id) {
					$role_count++;
				}
			}

			if ($role_count < 1) {
				show_404();
			}
		}
	}
}