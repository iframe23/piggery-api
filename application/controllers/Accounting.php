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
class Accounting extends CI_Controller {
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

        $this->__resTraitConstruct();
        $this->objOfJwt = new ImplementJwt();
        header('Content-Type: application/json');

    }

    //get
    public function account_statements_post(){
        $input = $this->post();

        $query = $this->db->join('users', 'ON account_statements.student_id = users.user_id', 'left')
        ->join('courses', 'ON users.course_id = courses.course_id', 'left')
        ->join('year_levels', 'ON users.year_level_id = year_levels.year_level_id', 'left')
        ->join('semester', 'ON account_statements.sem_id = semester.sem_id', 'left')
        ->get_where('account_statements', $input)->result_array();

        $this->set_response($query, 200);
    }

    public function for_down_payments_get(){
        $cur_sy = $this->curriculum_model->current_sy();
        $cur_sem = $this->curriculum_model->current_sem();

        $query = $this->db->join('users', 'ON account_statements.student_id = users.user_id', 'left')
        ->join('courses', 'ON users.course_id = courses.course_id', 'left')
        ->join('year_levels', 'ON users.year_level_id = year_levels.year_level_id', 'left')
        ->get_where('account_statements', array('school_year' => $cur_sy, 'sem_id' => $cur_sem['sem_id'], 'account_statements.status' => 'For Downpayment' ))->result_array();

        $this->set_response($query, 200);
    }

    public function rates_get(){
        $courses = $this->db->get('courses')->result_array();
        $rates = [];

        foreach ($courses as $row) {
            $rate = $this->db->get_where('rates', array('course_id' => $row['course_id'] ));

            if ($rate->num_rows() < 1) {
                $this->db->insert('rates', array('course_id' => $row['course_id'] ));
            } else {
                $rate = $rate->row_array();
                $rate['course_abb'] = $row['course_abb'];
                array_push($rates, $rate);
            }

        }

        $this->set_response($rates, 200);
    }

    public function submit_rate_post(){
        $input = $this->post();

        $this->db->where('rate_id', $input['rate_id']);
        $this->db->update('rates', $input);

        $response = array(
            'result' => 'Success',
            'type' => 'success',
            'message' => 'Successfully updated the new rates'
        );

        $this->set_response($response, 200);
    }

    public function get_account_statements_post(){
        $input = $this->post();

        $this->db->join('semester', 'ON account_statements.sem_id = semester.sem_id');
        $query = $this->db->get_where('account_statements', $input)->result_array();

        $this->set_response($query, 200);
    }

    public function account_sm_post(){
        $input = $this->post();

        if (isset($input['student_id']) && !isset($input['account_sm_id'])) {
            $cur_sem = $this->curriculum_model->current_sem();
            $input['school_year'] = $this->curriculum_model->current_sy();
            $input['account_statements.sem_id'] = $cur_sem['sem_id'];
        }

        $account_sm = $this->db->join('users', 'ON account_statements.student_id = users.user_id', 'left')
            ->join('courses', 'ON users.course_id = courses.course_id', 'left')
            ->join('semester', 'ON account_statements.sem_id = semester.sem_id', 'left')
            ->join('year_levels', 'ON users.year_level_id = year_levels.year_level_id', 'left')->get_where('account_statements', $input)->row_array();

        // $account_sm = $this->db->join('users', 'ON account_statements.student_id = users.user_id', 'left')
        // ->join('courses', 'ON users.course_id = courses.course_id', 'left')
        // ->join('year_levels', 'ON users.year_level_id = year_levels.year_level_id', 'left')
        // ->get_where('account_statements', $input)->row_array();

        $enrolled_subjects = $this->db->join('subjects','ON enrolled_subjects.subject_id = subjects.subject_id')->join('subject_scheds', 'ON enrolled_subjects.subject_sched_id = subject_scheds.subject_sched_id')->join('users', 'ON subject_scheds.teacher_id = users.user_id')->join('rooms', 'ON subject_scheds.room_id = rooms.room_id')->get_where('enrolled_subjects', array('account_sm_id' => $account_sm['account_sm_id'] ))->result_array();

        $i = 0;
        foreach ($enrolled_subjects as $row) {
            $subject_sched = $this->db->get_where('subject_scheds', array('subject_sched_id' => $row['subject_sched_id'] ))->row_array();

            $time_scheds = $this->db->get_where('time_scheds', array('subject_sched_id' => $subject_sched['subject_sched_id']))->result_array();

            $enrolled_subjects[$i]['time_scheds'] = $time_scheds;

            $i++;
        }

        if ($account_sm['status'] == 'For Downpayment') {
            $rates = $this->db->get_where('rates', array('course_id' => $account_sm['course_id'] ))->row_array();

            $total_units = 0;
            $total_lab_units = 0;

            foreach ($enrolled_subjects as $row) {
                $total_units = $total_units + $row['units'];
                $total_lab_units = $total_lab_units + $row['lab_units'];
            }

            $tuition_fee = ($rates['tuition_fee'] * $total_units);
            $lab_fee = ($rates['laboratory_fee'] * $total_lab_units);

            $total_amount = $tuition_fee + $lab_fee + $rates['misc_fee'];

            $account_sm['lab_fee'] = $lab_fee;
            $account_sm['tuition_fee'] = $tuition_fee;
            $account_sm['misc_fee'] = $rates['misc_fee'];
            $account_sm['total_units'] = $total_units;
            $account_sm['total_lab_units'] = $total_lab_units;
            $account_sm['total_amount'] = $total_amount;
            $account_sm['enrolledSubjects'] = $enrolled_subjects;

            $this->set_response($account_sm, 200);
        } else {
            $account_sm['enrolledSubjects'] = $enrolled_subjects;
            $account_sm['receipts'] = $this->db->get_where('receipts', array('account_sm_id' => $account_sm['account_sm_id']))->result_array();
            $this->set_response($account_sm, 200);
        }
        
    }

    //add
    public function submit_down_payment_post(){
        $input = $this->post();

        $account_sm = array(
            'misc_fee' => $input['misc_fee'],
            'tuition_fee' => $input['tuition_fee'],
            'lab_fee' => $input['lab_fee'],
            'total_units' => $input['total_units'],
            'total_lab_units' => $input['total_lab_units'],
            'total_amount' => $input['total_amount'],
            'down_payment' => $input['down_payment'],
            'status' => 'For Confirmation',
            'balance' => $input['total_amount'] - $input['down_payment'],
            'total_amount_paid' => $input['down_payment']
        );

        $account_sm['per_grading'] = $account_sm['balance'] / 4;

        $this->db->where('account_sm_id', $input['account_sm_id'])->update('account_statements', $account_sm);

        $receipt_data = array(
            'account_sm_id' => $input['account_sm_id'],
            'receipt_amount' => $input['down_payment'],
            'receipt_date' => date('Y-m-d'),
            'particulars' => 'Downpayment for Enrollment'
        );

        $this->db->insert('receipts', $receipt_data);

        $response = array(
            'result' => 'Success', 
            'type' => 'success',
            'message' => $input['firstname'].' '.$input['lastname'].' has Successfully paid the Down Payment'
        );

        $this->set_response($response, 200);
    }

    public function submit_payment_post(){
        $input = $this->post();
        $input['receipt_date'] = date('Y-m-d');

        $account_sm = $this->db->get_where('account_statements', array('account_sm_id' => $input['account_sm_id'] ))->row_array();

        $update_data['balance'] = $account_sm['balance'] - $input['receipt_amount'];
        $update_data['total_amount_paid'] = $account_sm['total_amount_paid'] + $input['receipt_amount'];

        $this->db->where('account_sm_id', $input['account_sm_id'])->update('account_statements', $update_data);
        $this->db->insert('receipts', $input);

        $response = array(
            'result' => 'Success',
            'message' => 'Successfully made the payment',
            'type' => 'success' 
        );

        $this->set_response($response, 200);
    }

    public function modify_account_sm_post(){
        $input = $this->post();

        $this->db->where($input['where'])->update('account_statements', $input['data']);

        $response = array(
            'result' => 'Success',
            'type' => 'success' 
        );

        $this->set_response($response, 200);
    }

    public function daily_collections_post(){
        $input = $this->post();

        $query = $this->db->join('account_statements', 'receipts.account_sm_id = account_statements.account_sm_id')
                ->join('users', 'ON account_statements.student_id = users.user_id')
                ->get_where('receipts', $input)->result_array();

        $this->set_response($query, 200);        
    }

    public function monthly_collections_post(){
        $input = $this->post();

        $query = $this->db->join('account_statements', 'receipts.account_sm_id = account_statements.account_sm_id')
                ->join('users', 'ON account_statements.student_id = users.user_id')
                ->get_where('receipts', array('receipt_date >=' => $input['receipt_date'].'-01', 'receipt_date <=' => $input['receipt_date'].'-31' ))->result_array();

        $this->set_response($query, 200);        
    }

    public function test_get(){
        print_r(12*2.5);
    }
}
