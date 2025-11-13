<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/ImplementJwt.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Schedule extends CI_Controller {
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model("user_model");
        $this->load->model("auth_model");
        $this->load->model("school_model");
        $this->load->model("curriculum_model");
        $this->load->model("schedule_model");
        $this->load->model("helper_model");

        $this->__resTraitConstruct();
        $this->objOfJwt = new ImplementJwt();
        header('Content-Type: application/json');
    }

    //get
    public function load_current_scheds_get(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();
        $courses = $this->db->get('courses')->result_array();

        foreach ($courses as $row) {
            $cur_course_sched = $this->db->get_where('schedules', array('course_id' => $row['course_id'],'school_year' => $cur_sy, 'sem_id' => $cur_sem['sem_id']));

            if ($cur_course_sched->num_rows() < 1) {
                $sched_data = array(
                    'course_id' => $row['course_id'],
                    'sched_name' => $row['course_abb'].' - '.$cur_sem['sem_name'].' - SY '.$cur_sy.' Schedules',
                    'school_year' => $cur_sy,
                    'sem_id' => $cur_sem['sem_id'],
                    'status' => 'Draft',
                    'date_added' => date('Y-m-d')
                );

                $this->db->insert('schedules', $sched_data);

                $last_sched_id = $this->helper_model->query_last_id('schedule_id', 'schedules');
                $subject_scheds = $this->db->get_where('subjects', array('course_id' => $row['course_id'], 'sem_id' => $cur_sem['sem_id']))->result_array();

                foreach ($subject_scheds as $row) {
                    //insert subject scheds
                    $subject_sched_data = array(
                        'schedule_id' => $last_sched_id['schedule_id'],
                        'year_level_id' => $row['year_level_id'],
                        'subject_id' => $row['subject_id'],
                    );

                    $this->db->insert('subject_scheds', $subject_sched_data);
                }
                
                $this->db->where(array('student_type' => 'New', ))->update('users', array('student_type' => 'Old', )); 
            }
            
        }

        $cur_scheds = $this->db->get_where('schedules', array('school_year' => $cur_sy, 'sem_id' => $cur_sem['sem_id']))->result_array();

        $i = 0;
        foreach ($cur_scheds as $row) {
            $cur_scheds[$i]['course'] = $this->school_model->course($row['course_id'], 'abb');
            $cur_scheds[$i]['semester'] = $this->curriculum_model->semester($row['sem_id'], true);
            
            $i++;
        }

        $response = array(
            'message' => 'Success',
            'loadedCurrentScheds' => $cur_scheds
        );

        $this->set_response($response, 200);
    }

    public function course_sched_post(){
        $input = $this->post();

        $course_sched = $this->db->get_where('schedules', array('schedule_id' => $input['id']))->row_array();
        $sched['course_sched'] = $course_sched;
        $sched['course_sched']['course'] = $this->school_model->course($course_sched['course_id'], true);
        $sched['course_sched']['semester'] = $this->curriculum_model->semester($course_sched['sem_id'], true);
        $sched['subject_scheds'] = $this->schedule_model->query_subject_scheds($input['id']);

        $i = 0;
        foreach ($sched['subject_scheds'] as $row) {
            $sched['subject_scheds'][$i]['time_scheds'] = $this->schedule_model->query_time_scheds($row['subject_sched_id'])->result_array();
            $this->db->join('users', 'ON can_teach.teacher_id = users.user_id');
            $sched['subject_scheds'][$i]['assigned_teachers'] = $this->db->get_where('can_teach', array('can_teach.subject_id' => $row['subject_id']))->result_array();

            if (count($sched['subject_scheds'][$i]['time_scheds']) < 1) {
                array_push($sched['subject_scheds'][$i]['time_scheds'], array(
                    'subject_sched_id' => $row['subject_sched_id'],
                    'merge_code' => $row['merge_code'],
                    'day_abb' => '',
                    'time_start' => null,
                    'time_end' => null
                ));
            }
            $i++;
        }

        $this->set_response($sched, 200);
    }

    //This usually used for adding/dropping but can also be used flexibly   
    public function course_schedule_post(){
        $input = $this->post();
        $query = [];

        if (!isset($input['except'])) {
            $query['course_sched'] = $this->db->get_where('schedules', $input)->row_array();

            $this->db->join('users', 'ON subject_scheds.teacher_id = users.user_id')
            ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id')
            ->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id')
            ->join('courses', 'ON subjects.course_id = courses.course_id')
            ->join('semester', 'ON subjects.sem_id = semester.sem_id')
            ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id');
            $query['subject_scheds'] = $this->db->get_where('subject_scheds', array('schedule_id' => $query['course_sched']['schedule_id']))->result_array();

            $i = 0;
            foreach ($query['subject_scheds'] as $row) {
                $query['subject_scheds'][$i]['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' => $row['subject_sched_id'] ))->result_array();

                $i++;
            }

        } else {
            $query['course_sched'] = $this->db->get_where('schedules', $input['where'])->row_array();

            $this->db->join('users', 'ON subject_scheds.teacher_id = users.user_id')
            ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id')
            ->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id')
            ->join('courses', 'ON subjects.course_id = courses.course_id')
            ->join('semester', 'ON subjects.sem_id = semester.sem_id')
            ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
            ->select('*')->from('subject_scheds')
            ->where('schedule_id', $query['course_sched']['schedule_id']);
            foreach ($input['except'] as $row) {
                $this->db->where($row);
            }
            $query['subject_scheds'] = $this->db->get()->result_array();

            $i = 0;
            foreach ($query['subject_scheds'] as $row) {
                $query['subject_scheds'][$i]['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' => $row['subject_sched_id'] ))->result_array();

                $i++;
            }
        }
        
        $this->set_response($query, 200);
    }

    public function other_course_sched_post(){
        $input = $this->post();

        $this->db->join('courses', 'ON schedules.course_id = courses.course_id')
        ->join('semester', 'schedules.sem_id = semester.sem_id');
        $cross['course_sched'] = $this->db->get_where('schedules', array('schedule_id' => $input['schedule_id']))->row_array();

        $this->db->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id')
        ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
        ->join('semester', 'ON subjects.sem_id = semester.sem_id')
        ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id')
        ->join('users', 'subject_scheds.teacher_id = users.user_id');
        $subject_scheds = $this->db->get_where('subject_scheds', array('schedule_id' => $input['schedule_id'] ))->result_array();

        if (isset($input['student'])) {
            # execute algorithm for querying available subjects for cross enrollment for old student
            $transcript = $this->db->get_where('transcripts', array('student_id' => $input['student']['user_id'] ))->row_array();

            $cross['available_subjects'] = [];

            foreach ($subject_scheds as $row) {
                $this->db->join('subject_scheds', 'ON subject_scheds.subject_sched_id = '.$row['subject_sched_id'])
                ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
                ->join('semester', 'ON subjects.sem_id = semester.sem_id')
                ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id')
                ->join('users', 'subject_scheds.teacher_id = users.user_id')
                ->select('subjects.subject_id, subjects.subject_code, subjects.descriptive_title, subjects.type, subjects.sem_id, subjects.year_level_id, year_levels.year_level_name, semester.sem_name, subjects.units, subjects.lec_units, subjects.lab_units, subjects.hours_week, subject_scheds.merge_code, subject_scheds.schedule_id, subject_scheds.subject_sched_id, subject_scheds.teacher_id, users.firstname, users.middlename, users.lastname, subject_scheds.room_id, rooms.room_name')->from('subjects')
                ->where(array('prospectus_id' => $transcript['prospectus_id'], 'descriptive_title' => $row['descriptive_title'] ));
                $query = $this->db->get();

                if ($query->num_rows() != 0) {
                    $a = $query->row_array();
                    $a['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' => $a['subject_sched_id']))->result_array();
                    array_push($cross['available_subjects'], $a);
                }
            }

            $this->set_response($cross, 200);
        } else {
            # otherwise execute algorithm for new student
            $cross['available_subjects'] = [];

            foreach ($subject_scheds as $row) {
                $this->db->join('subject_scheds', 'ON subject_scheds.subject_sched_id = '.$row['subject_sched_id'])
                ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
                ->join('semester', 'ON subjects.sem_id = semester.sem_id')
                ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id')
                ->join('users', 'subject_scheds.teacher_id = users.user_id')
                ->select('subjects.subject_id, subjects.subject_code, subjects.descriptive_title, subjects.type, subjects.sem_id, subjects.year_level_id, year_levels.year_level_name, semester.sem_name, subjects.units, subjects.lec_units, subjects.lab_units, subjects.hours_week, subject_scheds.merge_code, subject_scheds.schedule_id, subject_scheds.subject_sched_id, subject_scheds.teacher_id, users.firstname, users.middlename, users.lastname, subject_scheds.room_id, rooms.room_name')->from('subjects')
                ->where(array('prospectus_id' => $input['prospectus_id'], 'descriptive_title' => $row['descriptive_title'] ));
                $query = $this->db->get();

                if ($query->num_rows() != 0) {
                    $a = $query->row_array();
                    $a['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' => $a['subject_sched_id']))->result_array();
                    array_push($cross['available_subjects'], $a);
                }
            }

            $this->set_response($cross, 200);
        }
    }

    public function finalized_course_sched_post(){
        $input = $this->post();
        $cur_sem = $this->curriculum_model->current_sem();
        $cur_sy = $this->curriculum_model->current_sy();
        $sched = null;

        $course_sched = $this->db->get_where('schedules', array('course_id' => $input['course_id'], 'school_year' => $cur_sy, 'sem_id' => $cur_sem['sem_id'], 'status' => 'Finalized'))->row_array();

        if ($course_sched != null) {
            $sched['course_sched'] = $course_sched;
            $sched['course_sched']['course'] = $this->school_model->course($course_sched['course_id'], true);
            $sched['course_sched']['semester'] = $this->curriculum_model->semester($course_sched['sem_id'], true);
            $sched['subject_scheds'] = $this->schedule_model->query_subject_scheds($course_sched['schedule_id']);

            $i = 0;
            foreach ($sched['subject_scheds'] as $row) {
                $sched['subject_scheds'][$i]['time_scheds'] = $this->schedule_model->query_time_scheds($row['subject_sched_id'])->result_array();
                $i++;
            }
        } else {
            $sched['course_sched'] = array();
            $sched['subject_scheds'] = [];
        }
        
        $this->set_response($sched, 200);
    }

    public function course_schedules_post(){
        $input = $this->post();

        $this->db->join('semester', 'ON schedules.sem_id = semester.sem_id');
        $query = $this->db->get_where('schedules', $input)->result_array();

        $this->set_response($query , 200);
    }

    public function get_other_subjects_post(){
        $input = $this->post();
        $response = null;

        if ($input['merge_code'] == '') {
            $response = array('result' => 'Failed' );
        } else {
            $response['other_subjects'] = $this->db->select('*')->from('subject_scheds')->join('subjects', 'subject_scheds.subject_id = subjects.subject_id')->join('courses', 'subjects.course_id = courses.course_id')->where(array('subject_sched_id !=' => $input['subject_sched_id'], 'merge_code' => $input['merge_code']))->get()->result_array();

            $response['result'] = 'Success';
        }

        $this->set_response($response, 200);
    }

    public function subject_loads_post(){
        $input = $this->post();

        $query = $this->db->join('users', 'ON subject_scheds.teacher_id = users.user_id')
            ->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id')
            ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
            ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id')
            ->get_where('subject_scheds', array('teacher_id' => $input['teacher_id'] ))->result_array();

        $i = 0;    
        foreach ($query as $row) {
            $query[$i]['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' => $row['subject_sched_id'] ))->result_array();
            $i++;
        }

        $this->set_response($query, 200);    
    }

    //add
    public function save_subject_sched_post(){
        $input = $this->post();

        $subject_sched = null;
        $time_scheds = null;

        $subject_sched['teacher_id'] = $input['teacher_id'];
        $subject_sched['room_id'] = $input['room_id'];

        $hours = $this->schedule_model->total_hours($input['time_scheds']);

        if ($hours != $input['hours_week']) {
            $response['result'] = array(
                'type' => 'danger',
                'result' => 'Failed',
                'message' => 'Inputted time schedules does not satisfy the equivalent prescribed hours per week',
                'hours' => $hours
            );

            $this->set_response($response, 200);
        }
        else {
            if ($input['merge_code'] == "") {
                $instructor_con = $this->schedule_model->check_instructor_conflict($input);
                $room_con = $this->schedule_model->check_room_conflict($input);
                $subject_con = $this->schedule_model->check_subject_conflict($input);

                if ($instructor_con->num_rows() > 0) {
                    $response['result'] = array(
                        'type' => 'danger',
                        'result' => 'Failed',
                        'message' => 'There is an instructor time conflict on subject/s ',
                        'con_subjects' => $instructor_con->result_array()
                    );

                    $this->set_response($response, 200);
                } 
                else if($room_con->num_rows() > 0){
                    $response['result'] = array(
                        'type' => 'danger',
                        'result' => 'Failed',
                        'message' => 'There is an room conflict on subject/s ',
                        'con_subjects' => $room_con->result_array()
                    );

                    $this->set_response($response, 200);
                }
                else if ($subject_con->num_rows() > 0) {
                    $response['result'] = array(
                        'type' => 'danger',
                        'result' => 'Failed',
                        'message' => 'There is a Year Level subject conflict on subject/s ',
                        'con_subjects' => $subject_con->result_array()
                    );

                    $this->set_response($response, 200);
                } else {
                    $this->db->where('subject_sched_id', $input['subject_sched_id'])->update('subject_scheds', $subject_sched);
                    $i = 0;
                    foreach ($input['time_scheds'] as $row) {
                        if (isset($row['time_sched_id'])) {
                            $this->db->where('time_sched_id', $input['time_scheds'][$i]['time_sched_id'])->update('time_scheds', $input['time_scheds'][$i]);
                        } else {
                            $this->db->insert('time_scheds', $input['time_scheds'][$i]);
                        }
                        $i++;
                    }

                    $response['result'] = array(
                                            'type' => 'success',
                                            'result' => 'Success',
                                            'message' => 'Successfully Saved Time Schedule for - '.$input['descriptive_title'] ,
                                            'con_subjects' => array() 
                                        );

                    $this->set_response($response, 200);
                }
            } else {
                $instructor_con = $this->schedule_model->check_instructor_conflict($input, $input['merge_code']);
                $room_con = $this->schedule_model->check_room_conflict($input, $input['merge_code']);
                $subject_con = $this->schedule_model->check_subject_conflict($input, $input['merge_code']);

                if ($instructor_con->num_rows() > 0) {
                    $response['result'] = array(
                        'type' => 'danger',
                        'result' => 'Failed',
                        'message' => 'There is an instructor time conflict on subject/s ',
                        'con_subjects' => $instructor_con->result_array()
                    );

                    $this->set_response($response, 200);
                } 
                else if($room_con->num_rows() > 0){
                    $response['result'] = array(
                        'type' => 'danger',
                        'result' => 'Failed',
                        'message' => 'There is an room conflict on subject/s ',
                        'con_subjects' => $room_con->result_array()
                    );

                    $this->set_response($response, 200);
                }
                else if ($subject_con->num_rows() > 0) {
                    $response['result'] = array(
                        'type' => 'danger',
                        'result' => 'Failed',
                        'message' => 'There is a Year Level subject conflict on subject/s ',
                        'con_subjects' => $subject_con->result_array()
                    );

                    $this->set_response($response, 200);
                } else {
                    $this->db->where('merge_code', $input['merge_code'])->update('subject_scheds', $subject_sched);

                    $subject_scheds = $this->db->get_where('subject_scheds', array('merge_code' => $input['merge_code'] ))->result_array();

                    $this->db->where('merge_code', $input['merge_code'])->delete('time_scheds');

                    foreach ($subject_scheds as $row) {
                        foreach ($input['time_scheds'] as $row2) {
                               $data2['subject_sched_id'] = $row['subject_sched_id'];
                               $data2['merge_code'] = $row['merge_code'];
                               $data2['day_abb'] = $row2['day_abb'];
                               $data2['time_start'] = $row2['time_start'];
                               $data2['time_end'] = $row2['time_end'];

                               $this->db->insert('time_scheds', $data2);
                           }   
                    }

                    $response['result'] = array(
                                            'type' => 'success',
                                            'result' => 'Success',
                                            'message' => 'Successfully Saved Schedule for - '.$input['descriptive_title'].' and its Respective merged subjects',
                                            'con_subjects' => array() 
                                        );
                    $this->set_response($response, 200);
                }
            }
        }
    }

    public function merge_post(){
        $input = $this->post();

        $merge_code['merge_code'] = ''.$input['selected_subject']['subject_sched_id'].'';

        foreach ($input['other_subjects'] as $row) {
            $merge_code['merge_code'] .= '-'.$row['subject_sched_id'];
        }

        $data = array(
            'merge_code' => $merge_code['merge_code'],
            'teacher_id' => $input['selected_subject']['teacher_id'],
            'room_id' => $input['selected_subject']['room_id']
        );

        $this->db->where('subject_sched_id', $input['selected_subject']['subject_sched_id'])->update('subject_scheds', $data);

        $time_scheds = $this->db->get_where('time_scheds', array('subject_sched_id' => $input['selected_subject']['subject_sched_id']))->result_array();

        foreach ($time_scheds as $row) {
            $this->db->where('time_sched_id', $row['time_sched_id'])->update('time_scheds', $merge_code);
        }

        foreach ($input['other_subjects'] as $row) {
            $this->db->where('subject_sched_id', $row['subject_sched_id'])->update('subject_scheds', $data);

            $this->db->where('subject_sched_id', $row['subject_sched_id'])->delete('time_scheds');

            foreach ($time_scheds as $row2) {
                $data2['merge_code'] = $merge_code['merge_code'];
                $data2['subject_sched_id'] = $row['subject_sched_id'];
                $data2['day_abb'] = $row2['day_abb'];
                $data2['time_start'] = $row2['time_start'];
                $data2['time_end'] = $row2['time_end'];

                $this->db->insert('time_scheds', $data2);
            }
        }

        $response = array(
            'result' => 'Success',
            'message' => 'Successfully Merged Selected subjects',
            'type' => 'success' 
        );

        $this->set_response($response, 200);
    }

    public function unmerge_post(){
        $input = $this->post();
        $merge_code['merge_code'] = '';

        $this->db->where('subject_sched_id', $input['selected_subject']['subject_sched_id'])->update('subject_scheds', $merge_code);

        $time_scheds = $this->db->get_where('time_scheds', array('subject_sched_id' => $input['selected_subject']['subject_sched_id'] ))->result_array();
        foreach ($time_scheds as $row) {
            $this->db->where('subject_sched_id', $input['selected_subject']['subject_sched_id'])->update('time_scheds', $merge_code);
        }

        $unmerge['teacher_id'] = 0;
        $unmerge['room_id'] = 0;
        $unmerge['merge_code'] = '';

        foreach ($input['other_subjects'] as $row) {
            $this->db->where('subject_sched_id', $row['subject_sched_id'])->update('subject_scheds', $unmerge);

            $this->db->where('subject_sched_id', $row['subject_sched_id'])->delete('time_scheds');

        }

        $response = array(
            'result' => 'Success',
            'message' => 'Successfully Unmerged Selected subjects',
            'type' => 'warning' 
        );

        $this->set_response($response, 200);
    }

    public function assigned_teachers_post(){
        $input = $this->post();

        $this->db->join('users', 'ON can_teach.teacher_id = users.user_id');
        $query = $this->db->get_where('can_teach', $input)->result_array();

        $this->set_response($query, 200);
    }

    public function finalize_post(){
        $input = $this->post();
        $data['status'] = 'Finalized';

        $this->db->where('schedule_id', $input['schedule_id'])->update('schedules', $data);

        $response = array(
            'result' => 'Success',
            'message' => 'Successfully Finalized the Course schedules',
            'type' => 'success' 
        );

        $this->set_response($response, 200);
    }

    public function reset_draft_post(){
        $input = $this->post();
        $data['status'] = 'Draft';

        $this->db->where('schedule_id', $input['schedule_id'])->update('schedules', $data);

        $response = array(
            'result' => 'Success',
            'message' => 'Successfully Resetted the Course Schedules to Draft',
            'type' => 'warning' 
        );

        $this->set_response($response, 200);
    }

    public function initialize_subjects_post(){
        $input = $this->post();
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();
        $unloaded = 0;

        $sem_course_subjects = $this->db->get_where('subjects', array('sem_id' => $cur_sem['sem_id'], 'course_id' => $input['course_id']))->result_array();

        foreach ($sem_course_subjects as $row) {
            $subject_sched = $this->db->get_where('subject_scheds', array('schedule_id' => $input['schedule_id'], 'subject_id' => $row['subject_id']));

            if ($subject_sched->num_rows() < 1) {
                $data['schedule_id'] = $input['schedule_id'];
                $data['year_level_id'] = $row['year_level_id'];
                $data['subject_id'] = $row['subject_id'];

                $this->db->insert('subject_scheds', $data);
                $unloaded ++;
            }
        }

        if ($unloaded != 0) {
            $response = array(
                'result' => 'Success',
                'message' => 'Successfully Initialized all unloaded subjects for the current semester.',
                'type' => 'success' 
            );

            $this->set_response($response, 200);
        } else {
            $response = array(
                'result' => 'Already Initialized',
                'message' => 'Already initialized all subjects for the current semester, action aborted',
                'type' => 'warning' 
            );

            $this->set_response($response, 200);
        }
    }

    //delete
    public function delete_time_sched_post(){
        $input = $this->post();

        $this->db->where('time_sched_id', $input['id'])->delete('time_scheds');

        $response['result'] = array(
            'type' => 'warning',
            'result' => 'Success',
            'message' => 'A time schedule has successfully been removed! '
        );

        $this->set_response($response, 200);
    }
    public function test_post(){
        $input = $this->post();

        $input['time_start'] = strtotime($input['time_start']);
        $input['time_end'] = strtotime($input['time_end']);

        $this->set_response(($input['time_end']-$input['time_start'])/60/60, 200);
    }
}
