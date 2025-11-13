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
class Curriculum extends CI_Controller {
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
        $this->load->model("helper_model");
        $this->load->model("accounting_model");

        $this->__resTraitConstruct();
        $this->objOfJwt = new ImplementJwt();
        header('Content-Type: application/json');

    }

    public function prospectuses_get(){
        $query = $this->db->get('prospectuses')->result_array();
        $i = 0;
        foreach ($query as $row) {
            $query[$i]['course'] = $this->school_model->course($row['course_id'], true);
            $query[$i]['usage_status'] = $this->curriculum_model->get_is_used($row['is_used']);
            $i++;
        }
        
        $this->set_response($query, 200);
    }

    public function subjects_post(){
        $input = $this->post();
        $query = $this->curriculum_model->query_prospectus_subjects($input['id']);
        
        $this->set_response($query, 200);
    }

    public function year_levels_get(){
        $query = $this->db->get('year_levels')->result_array();

        $this->set_response($query, 200);
    }

    public function semester_get(){
        $query = $this->db->get('semester')->result_array();

        $this->set_response($query, 200);
    }

    public function courses_by_department_post(){
        $input = $this->post();
        $query = $this->db->get_where('courses', array('department_id' => $input['department_id']))->result_array();
        
        $this->set_response($query, 200);
    }

    public function department_by_course_post(){
        $input = $this->post();

        $course = $this->db->get_where('courses', array('course_id' => $input['course_id']))->row_array();
        $query = $this->db->get_where('departments', array('department_id' => $course['department_id']))->result_array();
        
        $this->set_response($query, 200);
    }

    public function prospectus_by_course_post(){
        $input = $this->post();
        $query = $this->db->get_where('prospectuses', array('course_id' => $input['course_id'], 'prospectus_status' => 'New'))->row_array();

        $query['usage_status'] = $this->curriculum_model->get_is_used($query['is_used']);
        $query['course'] = $this->school_model->course($query['course_id'], true);
        $query['subjects'] = $this->curriculum_model->query_prospectus_subjects($query['prospectus_id']);
        
        $this->set_response($query, 200);
    }

    public function prospectuses_by_course_post(){
        $input = $this->post();
        $query = $this->db->get_where('prospectuses', array('course_id' => $input['course_id']))->result_array();
        
        $this->set_response($query, 200);
    }

    public function prospectuses_post(){
        $input = $this->post();
        $this->db->join('courses', 'ON prospectuses.course_id = courses.course_id');
        $query = $this->db->get_where('prospectuses', $input)->result_array();
        
        $this->set_response($query, 200);
    }

    public function enrolled_subjects_post(){
        $input = $this->post();

        $this->db->join('semester', 'account_statements.sem_id = semester.sem_id');
        $data = $this->db->get_where('account_statements', $input)->row_array();

        $this->db->join('subjects', 'enrolled_subjects.subject_id = subjects.subject_id')
        ->join('credentials', 'credentials.enrolled_subject_id = enrolled_subjects.enrolled_subject_id');
        $data['enrolled_subjects'] = $this->db->get_where('enrolled_subjects', array('account_sm_id' => $data['account_sm_id']))->result_array();

        $this->set_response($data, 200);
    }

    //Get Single data
    public function prospectus_post(){
        $input = $this->post();
        $query['prospectus'] = $this->db->get_where('prospectuses', $input)->row_array();

        $query['prospectus']['usage_status'] = $this->curriculum_model->get_is_used($query['prospectus']['is_used']);
        $query['prospectus']['course'] = $this->school_model->course($query['prospectus']['course_id'], true);

        $query['subjects'] = $this->db->order_by('year_level_id', 'ASC')->order_by('sem_id', 'ASC')->get_where('subjects', array('prospectus_id' =>  $query['prospectus']['prospectus_id']))->result_array();

        $i = 0;
        foreach ($query['subjects'] as $row) {
            $query['subjects'][$i]['year_level'] = $this->curriculum_model->year_level($row['year_level_id'], true);
            $query['subjects'][$i]['semester'] = $this->curriculum_model->semester($row['sem_id'], true);
            $i++;
        }
        
        $this->set_response($query, 200);
    }
    //Add
    public function add_prospectus_post(){
        $input = $this->post();

        $response = $this->curriculum_model->insert_prospectus($input);

        $this->set_response($response, 200);
    }

    public function add_subjects_post(){
        $input = $this->post();
        
        $response = $this->curriculum_model->insert_subjects($input);

        $this->set_response($response, 200);
    }

    public function enroll_new_student_post(){
        $input = $this->post();
        $cur_sem = $this->curriculum_model->current_sem();
        $cur_sy = $this->curriculum_model->current_sy();
        $input['studentData']['role_id'] = 6;
        $input['studentData']['student_type'] = 'New';
        $input['studentData']['date_created'] = date('Y-m-d');
        $input['studentData']['last_enrolled'] = date('Y-m-d');

        $this->db->insert('users', $input['studentData']);

        $user_id = $this->helper_model->query_last_id('user_id', 'users');

        $transcript = array(
            'student_id' => $user_id['user_id'],
            'prospectus_id' => $input['credentials']['prospectus_id'],
            'course_id' => $input['credentials']['course_id'],
            'date_enrolled' => date('Y-m-d'),
        );

        $this->db->insert('transcripts', $transcript);

        $transcript_id = $this->helper_model->query_last_id('transcript_id', 'transcripts');

        foreach ($input['credentials']['subjects'] as $row) {
            if (!isset($row['equivalent_grade'])) {
                $row['equivalent_grade'] = 0;
            }

            $credential = array(
                'transcript_id' => $transcript_id['transcript_id'],
                'subject_id' => $row['subject_id'],
                'equivalent_grade' => $row['equivalent_grade']
            );

            $this->db->insert('credentials', $credential);
        }

        $account_sm = array(
            'student_id' => $user_id['user_id'],
            'course_id' => $input['credentials']['course_id'],
            'school_year' => $cur_sy,
            'sem_id' => $cur_sem['sem_id'],
            'status' => 'For Downpayment',
            'account_sm_date' => date('Y-m-d')
        );

        $this->db->insert('account_statements', $account_sm);

        $account_sm_id = $this->helper_model->query_last_id('account_sm_id', 'account_statements');

        foreach ($input['enrolledSubjects'] as $row) {
            $enrolled_subject = array(
                'account_sm_id' => $account_sm_id['account_sm_id'],
                'subject_id' => $row['subject_id'],
                'subject_sched_id' => $row['subject_sched_id'],
                'school_year' => $cur_sy,
                'sem_id' => $cur_sem['sem_id'] 
            );

            $this->db->insert('enrolled_subjects', $enrolled_subject);

            $enrolled_subject_id = $this->helper_model->query_last_id('enrolled_subject_id', 'enrolled_subjects');

            $this->db->where(array('subject_id' => $row['subject_id'], 'transcript_id' => $transcript_id['transcript_id']))->update('credentials', $enrolled_subject_id);
        }

        $response = array(
            'type' => 'success',
            'result' => 'Success',
            'message' => 'Successfully submitted the Enrollment Trial Form of '.$input['studentData']['firstname'].' '.$input['studentData']['lastname'].'. Proceed to the Accounting for the Down Payment' 
        );

        $this->set_response($response, 200);
    }

    public function enroll_old_student_post(){
        $input = $this->post();
        $cur_sem = $this->curriculum_model->current_sem();
        $cur_sy = $this->curriculum_model->current_sy();

        $transcript = $this->db->get_where('transcripts', array('student_id' => $input['studentData']['user_id'] ))->row_array();

        $this->db->where('user_id', $input['studentData']['user_id'])->update('users', array('last_enrolled' => date('Y-m-d'), 'year_level_id' => $input['studentData']['year_level_id'] ));
        
        $account_sm = array(
            'student_id' => $input['studentData']['user_id'],
            'course_id' => $transcript['course_id'],
            'school_year' => $cur_sy,
            'sem_id' => $cur_sem['sem_id'],
            'status' => 'For Downpayment',
            'account_sm_date' => date('Y-m-d')
        );

        $this->db->insert('account_statements', $account_sm);

        $account_sm_id = $this->helper_model->query_last_id('account_sm_id', 'account_statements');

        foreach ($input['enrolledSubjects'] as $row) {
            $enrolled_subject = array(
                'account_sm_id' => $account_sm_id['account_sm_id'],
                'subject_id' => $row['subject_id'],
                'subject_sched_id' => $row['subject_sched_id'],
                'school_year' => $cur_sy,
                'sem_id' => $cur_sem['sem_id'] 
            );

            $this->db->insert('enrolled_subjects', $enrolled_subject);

            $enrolled_subject_id = $this->helper_model->query_last_id('enrolled_subject_id', 'enrolled_subjects');

            $this->db->where(array('subject_id' => $row['subject_id'], 'transcript_id' => $transcript['transcript_id']))->update('credentials', $enrolled_subject_id);
        }

        $response = array(
            'type' => 'success',
            'result' => 'Success',
            'message' => 'Successfully submitted the Enrollment Trial Form of '.$input['studentData']['firstname'].' '.$input['studentData']['lastname'].'. Proceed to the Accounting for the Down Payment' 
        );

        $this->set_response($response, 200);
    }

    //Edit
    public function edit_prospectus_post(){
        $input = $this->post();

        $response = $this->curriculum_model->update_prospectus($input);
        
        $this->set_response($response, 200);
    }

    public function edit_subject_post(){
        $input = $this->post();

        $response = $this->curriculum_model->update_subject($input);
        
        $this->set_response($response, 200);
    }

    public function drop_post(){
        $input = $this->post();

        $this->db->join('account_statements', 'ON enrolled_subjects.account_sm_id = account_statements.account_sm_id');
        $account_sm = $this->db->get_where('enrolled_subjects', $input)->row_array();

        $this->db->where($input)->delete('enrolled_subjects');

        $this->accounting_model->recalculate_account_sm($account_sm['account_sm_id']);

        $response = array(
            'type' => 'warning',
            'result' => 'Success',
            'message' => 'Selected Subject has been successfully dropped'
        );

        $this->set_response($response, 200);
    }

    public function add_post(){
        $input = $this->post();

        $this->db->insert('enrolled_subjects', $input);

        $account_sm = $this->db->get_where('account_statements', array('account_sm_id' => $input['account_sm_id'] ))->row_array();

        $transcript = $this->db->get_where('transcripts', array('student_id' => $account_sm['student_id']))->row_array();

        $enrolled_subjects = $this->db->get_where('enrolled_subjects', array('account_sm_id' => $input['account_sm_id'] ))->result_array();

        foreach ($enrolled_subjects as $row) {
             $this->db->where(array('subject_id' => $row['subject_id'], 'transcript_id' => $transcript['transcript_id']))->update('credentials', array('enrolled_subject_id' => $row['enrolled_subject_id'] ));
        }
       
        $this->accounting_model->recalculate_account_sm($input['account_sm_id']);

        $response = array(
            'type' => 'success',
            'result' => 'Success',
            'message' => 'Selected Subject has been successfully added'
        );

        $this->set_response($response, 200);
    }

    public function update_transcript_status_post(){
        $input = $this->post();

        $this->db->where('transcript_id', $input['transcript_id'])->update('transcripts', array('transcript_status' => $input['transcript_status'], 'so_number' => $input['so_number'] ));

        $response = array(
            'type' => 'success',
            'result' => 'Success',
            'message' => 'Successfully update transcript status'
        );

        $this->set_response($response, 200);
    }

    public function update_credential_post(){
        $input = $this->post();

        $this->db->where('credential_id', $input['credential_id'])->update('credentials', array('equivalent_grade' => $input['equivalent_grade']));

        $response = array(
            'type' => 'success',
            'result' => 'Success',
            'message' => 'Successfully update transcript status'
        );

        $this->set_response($response, 200);
    }
}
