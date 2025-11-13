<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_model extends CI_model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('curriculum_model');
	}

	//query
	public function query_subject_scheds($id){
		$sql = 'SELECT subject_scheds.subject_sched_id, subject_scheds.schedule_id, subject_scheds.year_level_id, subject_scheds.subject_id, subject_scheds.teacher_id, subject_scheds.room_id,subject_scheds.merge_code, subjects.subject_code, subjects.descriptive_title, subjects.type, subjects.lec_units, subjects.lab_units, subjects.course_id, subjects.units,subjects.hours_week, year_levels.year_level_name, year_levels.year_level_abb, users.firstname, users.lastname, 
			users.gender, rooms.room_name, courses.course_abb, semester.sem_id, semester.sem_name
			FROM subject_scheds
			LEFT JOIN subjects ON subject_scheds.subject_id = subjects.subject_id
			JOIN courses ON subjects.course_id = courses.course_id
			LEFT JOIN year_levels ON subject_scheds.year_level_id = year_levels.year_level_id
			LEFT JOIN semester ON subjects.sem_id = semester.sem_id
			LEFT JOIN rooms ON subject_scheds.room_id = rooms.room_id
			LEFT JOIN users ON subject_scheds.teacher_id = users.user_id
			WHERE subject_scheds.schedule_id = '.$id.'
			';
		return $this->db->query($sql)->result_array();
	}

	public function query_time_scheds($id){
		return $this->db->get_where('time_scheds', array('subject_sched_id' => $id ));
	}
	//insert

	//misc
	public function check_instructor_conflict($subject_sched, $merge_code=null){
		$cur_sem = $this->curriculum_model->current_sem();
		$cur_sy = $this->curriculum_model->current_sy();

		$sql = 'SELECT subject_scheds.subject_sched_id, subject_scheds.teacher_id, time_scheds.time_sched_id, time_scheds.day_abb, time_scheds.time_start, time_scheds.time_end, subjects.subject_code, subjects.descriptive_title, courses.course_abb 
			FROM subject_scheds
			JOIN schedules ON subject_scheds.schedule_id = schedules.schedule_id
			JOIN subjects ON subject_scheds.subject_id = subjects.subject_id
			LEFT JOIN courses ON subjects.course_id = courses.course_id
			JOIN time_scheds ON subject_scheds.subject_sched_id = time_scheds.subject_sched_id
			WHERE subject_scheds.merge_code = "'.$subject_sched['merge_code'].'" AND 
				schedules.school_year = "'.$cur_sy.'" AND 
				schedules.sem_id = '.$cur_sem["sem_id"].' AND
				subject_scheds.teacher_id = '.$subject_sched['teacher_id'].' AND
				subject_scheds.subject_sched_id != '.$subject_sched['subject_sched_id'].'';
				if ($merge_code != null) {
					$sql .= ' AND subject_scheds.merge_code != "'.$merge_code.'"';
				}

		$i = 0;
		$con = 'AND';
		foreach ($subject_sched['time_scheds'] as $row) {
			if ($i != 0) {
				$con = "OR";
			}
			$sql .= ' '.$con.' (time_scheds.day_abb = "'.$row['day_abb'].'" AND ((time_scheds.time_start BETWEEN "'.$row['time_start'].'" AND SUBTIME("'.$row['time_end'].'", "1")) OR (SUBTIME(time_scheds.time_end, "1") BETWEEN "'.$row['time_start'].'" AND "'.$row['time_end'].'")
				OR ("'.$row['time_start'].'" BETWEEN time_scheds.time_start AND SUBTIME(time_scheds.time_end, "1"))
				OR (SUBTIME("'.$row['time_end'].'", "1") BETWEEN time_scheds.time_start AND time_scheds.time_end)
				))';
			$i++;
		}

		$query = $this->db->query($sql);
		return $query;
	}

	public function check_room_conflict($subject_sched, $merge_code=null){
		$cur_sem = $this->curriculum_model->current_sem();
		$cur_sy = $this->curriculum_model->current_sy();

		$sql = 'SELECT subject_scheds.subject_sched_id, subject_scheds.teacher_id, time_scheds.time_sched_id, time_scheds.day_abb, time_scheds.time_start, time_scheds.time_end, subjects.subject_code, subjects.descriptive_title, courses.course_abb 
			FROM subject_scheds
			JOIN schedules ON subject_scheds.schedule_id = schedules.schedule_id
			JOIN subjects ON subject_scheds.subject_id = subjects.subject_id
			LEFT JOIN courses ON subjects.course_id = courses.course_id
			JOIN time_scheds ON subject_scheds.subject_sched_id = time_scheds.subject_sched_id
			WHERE subject_scheds.merge_code = "'.$subject_sched['merge_code'].'" AND 
				schedules.school_year = "'.$cur_sy.'" AND 
				schedules.sem_id = '.$cur_sem["sem_id"].' AND
				subject_scheds.room_id = '.$subject_sched['room_id'].' AND
				subject_scheds.subject_sched_id != '.$subject_sched['subject_sched_id'].'';
				if ($merge_code != null) {
					$sql .= ' AND subject_scheds.merge_code != "'.$merge_code.'"';
				}

		$i = 0;
		$con = 'AND';
		foreach ($subject_sched['time_scheds'] as $row) {
			if ($i != 0) {
				$con = "OR";
			}
			$sql .= ' '.$con.' (time_scheds.day_abb = "'.$row['day_abb'].'" AND ((time_scheds.time_start BETWEEN "'.$row['time_start'].'" AND SUBTIME("'.$row['time_end'].'", "1")) OR (SUBTIME(time_scheds.time_end, "1") BETWEEN "'.$row['time_start'].'" AND "'.$row['time_end'].'")
				OR ("'.$row['time_start'].'" BETWEEN time_scheds.time_start AND SUBTIME(time_scheds.time_end, "1"))
				OR (SUBTIME("'.$row['time_end'].'", "1") BETWEEN time_scheds.time_start AND time_scheds.time_end)
				))';
			$i++;
		}

		$query = $this->db->query($sql);
		return $query;
	}

	public function check_subject_conflict($subject_sched, $merge_code=null){
		$cur_sem = $this->curriculum_model->current_sem();
		$cur_sy = $this->curriculum_model->current_sy();

		$sql = 'SELECT subject_scheds.subject_sched_id, subject_scheds.teacher_id, time_scheds.time_sched_id, time_scheds.day_abb, time_scheds.time_start, time_scheds.time_end, subjects.subject_code, subjects.descriptive_title, courses.course_abb 
			FROM subject_scheds
			JOIN schedules ON subject_scheds.schedule_id = schedules.schedule_id
			JOIN subjects ON subject_scheds.subject_id = subjects.subject_id
			LEFT JOIN courses ON subjects.course_id = courses.course_id
			JOIN time_scheds ON subject_scheds.subject_sched_id = time_scheds.subject_sched_id
			WHERE subject_scheds.merge_code = "'.$subject_sched['merge_code'].'" AND 
				schedules.school_year = "'.$cur_sy.'" AND 
				schedules.sem_id = '.$cur_sem["sem_id"].' AND
				subject_scheds.year_level_id = '.$subject_sched['year_level_id'].' AND
				subjects.course_id = '.$subject_sched['course_id'].' AND
				subject_scheds.subject_sched_id != '.$subject_sched['subject_sched_id'].'';
				if ($merge_code != null) {
					$sql .= ' AND subject_scheds.merge_code != "'.$merge_code.'"';
				}

		$i = 0;
		$con = 'AND';
		foreach ($subject_sched['time_scheds'] as $row) {
			if ($i != 0) {
				$con = "OR";
			}
			$sql .= ' '.$con.' (time_scheds.day_abb = "'.$row['day_abb'].'" AND ((time_scheds.time_start BETWEEN "'.$row['time_start'].'" AND SUBTIME("'.$row['time_end'].'", "1")) OR (SUBTIME(time_scheds.time_end, "1") BETWEEN "'.$row['time_start'].'" AND "'.$row['time_end'].'")
				OR ("'.$row['time_start'].'" BETWEEN time_scheds.time_start AND SUBTIME(time_scheds.time_end, "1"))
				OR (SUBTIME("'.$row['time_end'].'", "1") BETWEEN time_scheds.time_start AND time_scheds.time_end)
				))';
			$i++;
		}

		$query = $this->db->query($sql);
		return $query;
	}

	public function total_hours($time_scheds){
		$total = 0;
		foreach ($time_scheds as $row) {
			$hours = strtotime($row['time_end']) - strtotime($row['time_start']);
			$total = $total + $hours;
		}

		return $total/60/60;
	}
}