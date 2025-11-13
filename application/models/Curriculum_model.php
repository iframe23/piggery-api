<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curriculum_model extends CI_model
{
	
	public function __construct()
	{
		parent::__construct();
	}
	//query
	public function query_prospectus_subjects($id){
		$query = $this->db->order_by('year_level_id', 'ASC')->order_by('sem_id', 'ASC')->get_where('subjects', array('prospectus_id' =>  $id))->result_array();

        $i = 0;
        foreach ($query as $row) {
            $query[$i]['year_level'] = $this->curriculum_model->year_level($row['year_level_id'], true);
            $query[$i]['semester'] = $this->curriculum_model->semester($row['sem_id'], true);
            $i++;
        }

        return $query;
	}

	//insert
	public function insert_prospectus($data){
		$this->db->insert('prospectuses', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully added new prospectus - '.ucfirst($data['prospectus_name']) 
		);
	}

	public function insert_subjects($data){
		$i = 0;

		foreach ($data as $row) {
			$this->db->insert('subjects', $row);
		}
		
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully added Subjects' 
		);
	}

	//update
	public function update_prospectus($data){
		$this->db->where('prospectus_id', $data['prospectus_id']);
		$this->db->update('prospectuses', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Edited prospectus - '.ucfirst($data['prospectus_name']) 
		);
	}

	public function update_subject($data){
		$this->db->where('subject_id', $data['subject_id']);
		$this->db->update('subjects', $data);
		return array(
			'type' => 'success',
			'result' => 'Success',
			'message' => 'Successfully Edited subject - '.ucfirst($data['descriptive_title']) 
		);
	}

	//misc
	public function get_is_used($is_used){
		if ($is_used == 1) {
			return 'Used';
		} else {
			return 'Not Used';
		}
	}

	public function year_level($id, $name=null){
		$query = $this->db->get_where('year_levels', array('year_level_id' => $id ))->row();

		if ($query != null) {
			if ($name != null) {
				return $query->year_level_name;
			} else {
				return $query;
			}
		} else {
			'No year level';
		}
	}

	public function semester($id, $name=null){
		$query = $this->db->get_where('semester', array('sem_id' => $id ))->row();

		if ($query != null) {
			if ($name != null) {
				return $query->sem_name;
			} else {
				return $query;
			}
		} else {
			'No semester';
		}
	}

	public function current_sem(){
        $sems = $this->db->get('semester')->result_array();
        $cur_sem = null;
        $cur_month = (int)date('m');

        foreach ($sems as $row) {
            $row['sem_start_month'] = (int)$row['sem_start_month'];
            $row['sem_end_month'] = (int)$row['sem_end_month'];

            if ($row['sem_start_month'] <= $row['sem_end_month']) {
                if ($cur_month >= $row['sem_start_month'] && $cur_month <= $row['sem_end_month']) {
                    $cur_sem = $row;
                }
            } else {
                if (($cur_month <= $row['sem_start_month'] && $cur_month <= 12)
                    && ($cur_month <= $row['sem_end_month'] && $cur_month <= 12)
                ) {
                    $cur_sem = $row;
                }
            }
        }

        return $cur_sem;
    }

	public function current_sy(){
        $cur_month = date('m');
        $sems = $this->db->get('semester')->result_array();
        $sy = '';

        if ($cur_month >= $sems[0]['sem_start_month'] && $cur_month <= $sems[0]['sem_end_month']) {
            $year = date('Y');
            $year2 = date('Y')+1;

            $school_year1 = (string)$year;
            $school_year2 = (string)$year2;

            $sy = $school_year1.'-'.$school_year2;
        } elseif ($cur_month >= $sems[1]['sem_start_month'] && $cur_month <= $sems[1]['sem_end_month']) {

            $year = date('Y')-1;
            $year2 = date('Y');

            $school_year1 = (string)$year;
            $school_year2 = (string)$year2;

            $sy = $school_year1.'-'.$school_year2;
        } else {

            $year = date('Y')-1;
            $year2 = date('Y');

            $school_year1 = (string)$year;
            $school_year2 = (string)$year2;

            $sy = $school_year1.'-'.$school_year2;
        }

        return $sy;
    }
}