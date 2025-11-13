<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("helper_model");
		$this->load->model("user_model");
	}

	public function query_logged_user(){
		if (isset($_SESSION['user_logged']) && $_SESSION['user_logged'] == true) {
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where(array('username' => $_SESSION['username']));
			$this->db->join('roles', 'users.role_id = roles.role_id');
			$query = $this->db->get();

			return $query;
		}
	}

	public function update_logged_user_info($data){
		$this->db->where('username', $_SESSION['username']);
		$this->db->update('users', $data);
	}

	public function query_user_by_id($id){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('user_id', $id);
		$query = $this->db->get();

		return $query;
	}

	public function query_users_by_role($role_id){
		$query = $this->db->get_where('users', array('role_id' => $role_id ));

		return $query;

	}

	public function query_password($id){
		$this->db->select('password');
		$query = $this->db->get_where('users', array('user_id' => $id ));

		return $query;
	}

	public function insert_user($data){
		$data['password'] = md5($data['password']);
		$query = $this->db->get_where('users', array('username' => $data['username']))->row();

		if ($query != null) {
			return array(
				'type' => 'danger',
				'result' => 'Failed',
				'message' => 'Username already taken!' 
			);
		} else {
			$this->db->insert('users', $data);

			$query = $this->helper_model->query_last_id('user_id', 'users');

			return array(
				'type' => 'success',
				'result' => 'Success',
				'message' => 'Successfully Created Account - '.ucfirst($data['firstname']).' '.ucfirst($data['lastname']).'. Complete your Account details next !',
				'user_id' => $query['user_id']
			);
		}
	}

	public function insert_pig($data){
		$data['date_added'] = date('Y-m-d');
		$data['maternity_status'] = 'Not Pregnant';

		$this->db->insert('pigs', $data);

		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Added a Pig');

	}

	public function insert_visiting_point($data){
		$data['userData']['password'] = md5($data['userData']['password']);
		$query = $this->db->get_where('users', array('username' => $data['userData']['username']))->row();

		if ($query != null) {
			return array(
				'type' => 'danger',
				'result' => 'Failed',
				'message' => 'Username already taken!' 
			);
		} else {
			$this->db->insert('users', $data['userData']);

			$query = $this->helper_model->query_last_id('user_id', 'users');

			$data['visiting_pointData']['user_id'] = $query['user_id'];

			$this->db->insert('visiting_points', $data['visiting_pointData']);

			$query2 = $this->helper_model->query_last_id('visiting_point_id', 'visiting_points');

			return array(
				'type' => 'success',
				'result' => 'Success',
				'message' => 'Successfully added new Visiting Point - '.ucfirst($data['visiting_pointData']['visiting_point_name']),
				'visiting_point_id' => $query2['visiting_point_id']
			);
		}
	}

	public function insert_log($data){

		$this->db->insert('visit_logs', $data);

		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully saved Visitor Log - ',
		);
	}

	public function update_user($data){
		$this->db->where('user_id', $data['user_id']);
		$this->db->update('users', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Edited user Information' 
		);
	}

	public function update_pig($data){
		$this->db->where('pig_id', $data['pig_id']);
		$this->db->update('pigs', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Edited Pig Information' 
		);
	}

	public function insert_rate($data){
		$this->db->where('job_id', $data['job_id']);
		$this->db->update('jobs', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Rated the Job and Marked as complete'
		);
	}

	public function accept($data){
		$this->db->where('job_id', $data['job_id']);
		$this->db->update('jobs', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Accepted the Job'
		);
	}

	public function reject($data){
		$this->db->where('job_id', $data['job_id']);
		$this->db->update('jobs', $data);
		return array(
			'type' => 'warning',
			'result' => 'Success',
			'message' => 'You Have Rejected the Offer'
		);
	}

	public function pay($data){
		$this->db->where('job_id', $data['job_id']);
		$this->db->update('jobs', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'You Have Confirm that payment has been received by you!'
		);
	}

	public function delete_data($data){
		$this->db->where($data['id_field'], $data['id']);
		$this->db->delete($data['table']);

		return array(
			'type' => 'warning',
			'result' => 'Success',
			'message' => 'Successfully deleted data!'
		);
	}

	public function insert_profile_pic($pic){
		$user_id = $this->query_logged_user_info()->row()->user_id;
		$data['profile_pic'] = $pic;

		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data);
	}

	public function delete_old_profile_pic(){
		$user = $this->query_logged_user_info()->row();

		unlink('./images/profile_pics/'.$user->profile_pic);
	}

	public function profile_pic($pic){
		if ($pic == '') {
			return base_url().'images/profile_pics/profile.jpg';
		} else {
			return base_url().'images/profile_pics/'.$pic;
		}
	}

	public function role($id){
		$query = $this->db->get_where('roles', array('role_id' => $id ))->row();

		if ($query != null) {
			return $query->role_name;
		} else {
			return 'No role';
		}
		
	}

	public function designation($id){
		$query = $this->db->get_where('designations', array('designation_id' => $id ))->row();

		if ($query != null) {
			return $query->designation_name;
		} else {
			return 'No designation';
		}
	}

	public function job_status($id){
		$query = $this->db->get_where('job_statuses', array('job_status_id' => $id ))->row();

		if ($query != null) {
			return $query->job_status_name;
		} else {
			return 'No Job Status';
		}
	}

	public function query_visit_logs($where){
		$day = (int)date('d' , strtotime($where['log_time']));
		$day = (int)$day + 1;
		$month = (int)date('m', strtotime($where['log_time']));
		$year = (int)date('Y', strtotime($where['log_time']));
		$start = date('Y-m-d' , strtotime($where['log_time']));
		$end = date('Y-m-d' , strtotime($year.'-'.$month.'-'.$day));
		$sql = 'SELECT *
			FROM visit_logs
			LEFT JOIN users ON visit_logs.user_id = users.user_id
			WHERE visit_logs.visiting_point_id = '.$where['visiting_point_id'].' AND (visit_logs.log_time >= "'.$start.'" AND visit_logs.log_time < "'.$end.'")';

		return $this->db->query($sql)->result_array();
	}

	public function query_contact_logs($where){
		$day = (int)date('d' , strtotime($where['log_time']));
		$day = (int)$day + 1;
		$month = (int)date('m', strtotime($where['log_time']));
		$year = (int)date('Y', strtotime($where['log_time']));
		$start = date('Y-m-d' , strtotime($where['log_time']));
		$end = date('Y-m-d' , strtotime($year.'-'.$month.'-'.$day));
		$sql = 'SELECT *
			FROM visit_logs
			LEFT JOIN users ON visit_logs.user_id = users.user_id
			WHERE visit_logs.visiting_point_id = '.$where['visiting_point_id'].' AND visit_logs.user_id != '.$where['user_id'].' AND (visit_logs.log_time >= "'.$start.'" AND visit_logs.log_time < "'.$end.'")';

		return $this->db->query($sql)->result_array();
	}

	public function send_email($user, $message){
    	$config['protocol'] = 'smtp';
		// SMTP Server Address for Gmail.
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		// SMTP Port - the port that you is requir2ed
		$config['smtp_port'] = '465';
		// SMTP Username like. (abc@gmail.com)
		$config['smtp_user'] = 'leoiensinoy@gmail.com';
		// SMTP Password like (abc***##)
		$config['smtp_pass'] = '@newo2021NEWpassword_1994';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		// Load email library and passing configured values to email library
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		// Sender email address
		$this->email->from('youremail', 'IATF Administrator');
		// Receiver email address.for single email
		$this->email->to($user['email']);
		// Subject of email
		$this->email->subject($message['subject']);
		// Message in email
		$this->email->message($message['content']);
		// It returns boolean TRUE or FALSE based on success or failure
		$this->email->send(); 
    }

}