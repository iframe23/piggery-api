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
class Dashboard extends CI_Controller {
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
        $this->load->model("dashboard_model");

        $this->__resTraitConstruct();
        $this->objOfJwt = new ImplementJwt();
        header('Content-Type: application/json');

    }

    //Get
    public function new_students_get(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();

        $this->db->join('users', 'ON account_statements.student_id = users.user_id');
        $query = $this->db->get_where('account_statements', array('users.student_type' => 'New', 'school_year' => $cur_sy, 'sem_id' => $cur_sem['sem_id']));

        $this->set_response($query->num_rows(), 200);
    }

    public function old_students_get(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();

        $this->db->join('users', 'ON account_statements.student_id = users.user_id');
        $query = $this->db->get_where('account_statements', array('users.student_type' => 'Old', 'school_year' => $cur_sy, 'sem_id' => $cur_sem['sem_id']));

        $this->set_response($query->num_rows(), 200);
    }

    public function used_prospectuses_get(){
        $query = $this->db->get_where('prospectuses', array('is_used' => 1));

        $this->set_response($query->num_rows(), 200);
    }

    public function active_courses_get(){
        $query = $this->db->get('courses');

        $this->set_response($query->num_rows(), 200);
    }

    public function unfinalized_schedules_get(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();

        $query = $this->db->get_where('schedules', array('status !=' => 'Finalized', 'sem_id' => $cur_sem['sem_id'], 'school_year' => $cur_sy ));

        $this->set_response($query->num_rows(), 200);
    }

    public function finalized_schedules_get(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();

        $query = $this->db->get_where('schedules', array('status' => 'Finalized', 'sem_id' => $cur_sem['sem_id'], 'school_year' => $cur_sy ));

        $this->set_response($query->num_rows(), 200);
    }

    public function for_downpayments_get(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();

        $query = $this->db->get_where('account_statements', array('status' => 'For Downpayment', 'sem_id' => $cur_sem['sem_id'], 'school_year' => $cur_sy ));

        $this->set_response($query->num_rows(), 200);
    }

    public function unpaid_account_statements_get(){
        $query = $this->db->get_where('account_statements', array('total_amount !=' => 0, 'balance !=' => 0 ));

        $this->set_response($query->num_rows(), 200);
    }

    public function for_confirmation_get(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();

        $query = $this->db->get_where('account_statements', array('status' => 'For Confirmation', 'sem_id' => $cur_sem['sem_id'], 'school_year' => $cur_sy ));

        $this->set_response($query->num_rows(), 200);
    }

    public function officially_enrolled_get(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();

        $query = $this->db->get_where('account_statements', array('status' => 'Officially Enrolled', 'sem_id' => $cur_sem['sem_id'], 'school_year' => $cur_sy ));

        $this->set_response($query->num_rows(), 200);
    }
    public function enrolled_students_per_courses_get(){
        $courses = $this->db->get_where('courses')->result_array();

        $cur_sem = $this->curriculum_model->current_sem();
        $cur_sy = $this->curriculum_model->current_sy();
        $colors = $this->dashboard_model->colors();

        $response['labels'] = [];
        $response['data'] = [];
        $response['backgroundColor'] = [];

        $i = 0;
        foreach ($courses as $row) {
            array_push($response['labels'], $row['course_abb']);
            
            $this->db->join('users', 'ON account_statements.student_id = users.user_id');    
            $course_enrollees = $this->db->get_where('account_statements', array('users.course_id' => $row['course_id'] ));


            array_push($response['backgroundColor'], $colors[$i]);
            array_push($response['data'], $course_enrollees->num_rows());
            $i++;
        }

        $this->set_response($response, 200);
    }

    public function department_population_post(){
        $cur_sem = $this->curriculum_model->current_sem();
        $cur_sy = $this->curriculum_model->current_sy();

        $input = $this->post();
        $input['school_year'] = $cur_sy;
        $input['sem_id'] = $cur_sem['sem_id'];
        
        $this->db->join('courses', 'ON account_statements.course_id = courses.course_id');
        $this->db->join('departments', 'ON courses.department_id = departments.department_id');        
        $response = $this->db->get_where('account_statements', $input);

        $this->set_response($response->num_rows(), 200);
    }

    public function department_instructors_post(){
        $input = $this->post();

        $response = $this->db->get_where('users', $input);

        $this->set_response($response->num_rows(), 200);
    }

    public function subject_loads_num_post(){
        $cur_sem = $this->curriculum_model->current_sem();
        $cur_sy = $this->curriculum_model->current_sy();

        $input = $this->post();
        $input['schedules.school_year'] = $cur_sy;
        $input['schedules.sem_id'] = $cur_sem['sem_id'];

        $this->db->join('schedules', 'subject_scheds.schedule_id = schedules.schedule_id');
        $response = $this->db->get_where('subject_scheds', $input);

        $this->set_response($response->num_rows(), 200);
    }

    public function students_under_post(){
        $cur_sem = $this->curriculum_model->current_sem();
        $cur_sy = $this->curriculum_model->current_sy();

        $input = $this->post();
        $input['schedules.school_year'] = $cur_sy;
        $input['schedules.sem_id'] = $cur_sem['sem_id'];

        $num = 0;

        $this->db->join('schedules', 'subject_scheds.schedule_id = schedules.schedule_id');
        $sub_sched = $this->db->get_where('subject_scheds', $input)->result_array();

        foreach ($sub_sched as $row) {
            $enrolled = $this->db->get_where('enrolled_subjects', array('subject_sched_id' => $row['subject_sched_id'] ));
            $num = $num + $enrolled->num_rows();
        }

        $this->set_response($num, 200);
    }

    public function enrolled_students_per_year_levels_get(){
        $year_levels = $this->db->get_where('year_levels')->result_array();

        $cur_sem = $this->curriculum_model->current_sem();
        $cur_sy = $this->curriculum_model->current_sy();
        $colors = $this->dashboard_model->colors();

        $response['labels'] = [];
        $response['data'] = [];
        $response['backgroundColor'] = [];

        $i = 0;
        foreach ($year_levels as $row) {
            array_push($response['labels'], $row['year_level_name']);
            
            $this->db->join('users', 'ON account_statements.student_id = users.user_id');    
            $course_enrollees = $this->db->get_where('account_statements', array('users.year_level_id' => $row['year_level_id'] ));


            array_push($response['backgroundColor'], $colors[$i]);
            array_push($response['data'], $course_enrollees->num_rows());
            $i++;
        }

        $this->set_response($response, 200);
    }

    public function monthly_collection_get(){
        $response['labels'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $response['data'] = [];
        $y = date('Y');

        foreach ($response['labels'] as $row) {
            if ($row == 'Jan') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-01-01', 'receipt_date <=' => $y.'-01-31'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            } else if ($row == 'Feb') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-02-01', 'receipt_date <=' => $y.'-02-28'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            } else if ($row == 'Mar') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-03-01', 'receipt_date <=' => $y.'-03-31'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            } else if ($row == 'Apr') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-04-01', 'receipt_date <=' => $y.'-04-30'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            } else if ($row == 'May') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-05-01', 'receipt_date <=' => $y.'-05-31'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            } else if ($row == 'June') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-06-01', 'receipt_date <=' => $y.'-06-30'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            }  else if ($row == 'July') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-07-01', 'receipt_date <=' => $y.'-07-31'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            } else if ($row == 'Aug') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-08-01', 'receipt_date <=' => $y.'-08-31'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            }  else if ($row == 'Sep') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-09-01', 'receipt_date <=' => $y.'-09-30'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            }  else if ($row == 'Oct') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-10-01', 'receipt_date <=' => $y.'-10-31'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            }  else if ($row == 'Nov') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-11-01', 'receipt_date <=' => $y.'-11-30'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            }  else if ($row == 'Dec') {
                $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-12-01', 'receipt_date <=' => $y.'-12-31'))->result_array();
                $amount = 0;
                foreach ($query as $row2) {
                    $amount = $amount + (int)$row2['receipt_amount'];
                }

                array_push($response['data'], $amount); 
            }
        }

        $this->set_response($response, 200);
    }

    public function courses_collections_get(){
        $courses = $this->db->get('courses')->result_array();

        $y = date('Y');

        $response['labels'] = [];
        $response['data'] = [];

        foreach ($courses as $row) {
            array_push($response['labels'], $row['course_abb']);

            $this->db->join('account_statements', 'ON receipts.account_sm_id = account_statements.account_sm_id');
            $query = $this->db->get_where('receipts', array('receipt_date >=' => $y.'-01-01', 'receipt_date <=' => $y.'-01-31', 'account_statements.course_id' => $row['course_id']))->result_array();

            $amount = 0;
            foreach ($query as $row2) {
                $amount = $amount + (int)$row2['receipt_amount'];
            }

            array_push($response['data'], $amount); 
        }
            
        $this->set_response($response, 200);
    }

    public function students_in_each_subject_loads_post(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();
        $colors = $this->dashboard_model->colors();

        $input = $this->post();
        $input['schedules.sem_id'] = $cur_sem['sem_id'];
        $input['schedules.school_year'] = $cur_sy;

        $this->db->join('schedules', 'ON subject_scheds.schedule_id = schedules.schedule_id');
        $this->db->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id');
        $subjects = $this->db->get_where('subject_scheds', $input)->result_array();

        $response['labels'] = [];
        $response['data'] = [];
        $response['backgroundColor'] = [];

        $i = 0;
        foreach ($subjects as $row) {
            array_push($response['labels'], $row['subject_code']);

            $enrolled_subjects = $this->db->get_where('enrolled_subjects', array('subject_sched_id' => $row['subject_sched_id'] ));

            array_push($response['backgroundColor'], $colors[$i]);
            array_push($response['data'], $enrolled_subjects->num_rows());
            $i++;
        }

        $this->set_response($response, 200);

    }

    public function subject_loads_per_days_post(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();
        $colors = $this->dashboard_model->colors();

        $input = $this->post();
        $input['schedules.sem_id'] = $cur_sem['sem_id'];
        $input['schedules.school_year'] = $cur_sy;

        $this->db->join('schedules', 'ON subject_scheds.schedule_id = schedules.schedule_id');
        $this->db->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id');
        $this->db->join('rooms', 'ON subject_scheds.room_id = rooms.room_id');
        $subjects = $this->db->get_where('subject_scheds', $input)->result_array();

        $i = 0;
        foreach ($subjects as $row) {
            $subjects[$i]['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' =>  $row['subject_sched_id']))->result_array();

            $i++;
        }

        $this->set_response($subjects, 200);
    }
}
