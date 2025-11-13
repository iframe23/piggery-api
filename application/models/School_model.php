<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School_model extends CI_model
{
	
	public function __construct()
	{
		parent::__construct();
	}
	//query
	public function department($id, $name=null){
		$query = $this->db->get_where('departments', array('department_id' => $id ))->row();

		if ($query != null) {
			if ($name != null) {
				return $query->department_name.' ('.$query->department_abb.')';
			} else {
				return $query;
			}
		} else {
			'No department';
		}
	}

	public function course($id, $name=null){
		$query = $this->db->get_where('courses', array('course_id' => $id ))->row();

		if ($query != null) {
			if ($name = 'abb') {
				return $query->course_abb;
			} else if ($name != null) {
				return $query->course_name.' ('.$query->course_abb.')';
			} else {
				return $query;
			}
		} else {
			'No course';
		}
	}

	//insert
	public function insert_room($data){
		$this->db->insert('rooms', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully added new room - '.ucfirst($data['room_name']) 
		);
	}

	public function insert_department($data){
		$this->db->insert('departments', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully added new department - '.ucfirst($data['department_name']) 
		);
	}

	public function insert_course($data){
		$this->db->insert('courses', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully added new course - '.ucfirst($data['course_name']) 
		);
	}


	//update
	public function update_room($data){
		$this->db->where('room_id', $data['room_id']);
		$this->db->update('rooms', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Edited room - '.ucfirst($data['room_name']) 
		);
	}

	public function update_department($data){
		$this->db->where('department_id', $data['department_id']);
		$this->db->update('departments', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Edited department - '.ucfirst($data['department_name']) 
		);
	}

	public function update_course($data){
		$this->db->where('course_id', $data['course_id']);
		$this->db->update('courses', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Edited course - '.ucfirst($data['course_name']) 
		);
	}

	//misc
}