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
class Printable extends CI_Controller {
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

    protected $data = array();

    //get
    public function print_receipt_get(){
        // $this->auth_model->role_restriction(1);
        $id = $this->uri->segment(3);

        $this->load->library('pdf');

        $this->db->join('account_statements', 'ON receipts.account_sm_id = account_statements.account_sm_id')
        ->join('users', 'ON account_statements.student_id = users.user_id');

        $this->data['receipt'] = $this->db->get_where('receipts', array('receipt_id' => $id ))->row();

        $this->load->view('documents/print_receipt', $this->data);
    }

    public function print_transcript_get(){
        // $this->auth_model->role_restriction(1);
        $id = $this->uri->segment(3);

        $this->load->library('pdf');

        $this->db->join('users', 'ON transcripts.student_id = users.user_id')
        ->join('prospectuses', 'ON transcripts.prospectus_id = prospectuses.prospectus_id')
        ->join('courses', 'ON prospectuses.course_id = courses.course_id');
        $this->data['transcript'] = $this->db->get_where('transcripts', array('transcript_id' => $id ))->row_array();

        $this->db->join('subjects', 'ON credentials.subject_id = subjects.subject_id')
        ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
        ->join('semester', 'ON subjects.sem_id = semester.sem_id');
        $this->data['credentials'] = $this->db->get_where('credentials', array('transcript_id' => $id ))->result_array();

        $this->load->view('documents/print_transcript', $this->data);
    }

    public function print_prospectus_get(){
        // $this->auth_model->role_restriction(1);
        $id = $this->uri->segment(3);

        $this->load->library('pdf');

        $this->db->join('courses', 'ON prospectuses.course_id = courses.course_id');
        $this->data['prospectus'] = $this->db->get_where('prospectuses', array('prospectus_id' => $id ))->row_array();

        $year_levels = $this->db->get('year_levels')->result_array();
        $semesters = $this->db->get('semester')->result_array();
        $this->data['category'] = [];

        foreach ($year_levels as $row) {
            foreach ($semesters as $row2) {
                $this->db->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
                ->join('semester', 'ON subjects.sem_id = semester.sem_id');
                $sub['category_name'] = $row['year_level_name'].' - '.$row2['sem_name'];

                $sub['subjects'] = $this->db->get_where('subjects', array('prospectus_id' => $id, 'subjects.year_level_id' => $row['year_level_id'], 'subjects.sem_id' => $row2['sem_id'] ))->result_array();

                array_push($this->data['category'], $sub);
            }
        }

        $this->load->view('documents/print_prospectus', $this->data);
    }

    public function print_account_sm_get(){
        // $this->auth_model->role_restriction(1);
        $id = $this->uri->segment(3);

        $this->load->library('pdf');

        $this->data['account_sm'] = $this->db->join('users', 'ON account_statements.student_id = users.user_id', 'left')
            ->join('courses', 'ON users.course_id = courses.course_id', 'left')
            ->join('semester', 'ON account_statements.sem_id = semester.sem_id', 'left')
            ->join('year_levels', 'ON users.year_level_id = year_levels.year_level_id', 'left')->get_where('account_statements', array('account_sm_id' => $id))->row_array();


        $this->data['enrolled_subjects'] = $this->db->join('subjects','ON enrolled_subjects.subject_id = subjects.subject_id')->join('subject_scheds', 'ON enrolled_subjects.subject_id = subject_scheds.subject_id')->join('users', 'ON subject_scheds.teacher_id = users.user_id')->join('rooms', 'ON subject_scheds.room_id = rooms.room_id')->get_where('enrolled_subjects', array('account_sm_id' => $id ))->result_array();

        $i = 0;
        foreach ($this->data['enrolled_subjects'] as $row) {
            $subject_sched = $this->db->get_where('subject_scheds', array('subject_sched_id' => $row['subject_sched_id'] ))->row_array();

            $time_scheds = $this->db->get_where('time_scheds', array('subject_sched_id' => $subject_sched['subject_sched_id']))->result_array();

            $this->data['enrolled_subjects'][$i]['time_scheds'] = $time_scheds;

            $i++;
        }

        $this->load->view('documents/print_account_sm', $this->data);
    }

    public function print_report_card_get(){
        $id = $this->uri->segment(3);

        $this->load->library('pdf');

        $this->db->join('semester', 'account_statements.sem_id = semester.sem_id')
        ->join('users', 'account_statements.student_id = users.user_id')
        ->join('courses', 'users.course_id = courses.course_id')
        ->join('year_levels', 'users.year_level_id = year_levels.year_level_id');
        $this->data['report_card'] = $this->db->get_where('account_statements', array('account_sm_id' =>  $id))->row_array();

        $this->db->join('subjects', 'enrolled_subjects.subject_id = subjects.subject_id')
        ->join('credentials', 'credentials.enrolled_subject_id = enrolled_subjects.enrolled_subject_id');
        $this->data['enrolled_subjects'] = $this->db->get_where('enrolled_subjects', array('account_sm_id' => $this->data['report_card']['account_sm_id']))->result_array();

        $this->load->view('documents/print_report_card', $this->data);
    }


    public function print_schedule_get(){
        // $this->auth_model->role_restriction(1);
        $id = $this->uri->segment(3);

        $this->load->library('pdf');

        
        $this->db->join('courses', 'ON schedules.course_id = courses.course_id')
        ->join('semester', 'ON schedules.sem_id = semester.sem_id');
        $this->data['schedule'] = $this->db->get_where('schedules', array('schedule_id' => $id ))->row_array();

        $this->db->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id')
        ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
        ->join('semester', 'ON subjects.sem_id = semester.sem_id')
        ->join('users', 'ON subject_scheds.teacher_id = users.user_id')
        ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id');
        $this->data['year1'] = $this->db->get_where('subject_scheds', array('schedule_id' => $id, 'subjects.year_level_id' => 1 ))->result_array();

        $i = 0;
        foreach ($this->data['year1'] as $row) {
            $this->data['year1'][$i]['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' => $row['subject_sched_id']))->result_array();
            $i++;
        }


        $this->db->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id')
        ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
        ->join('semester', 'ON subjects.sem_id = semester.sem_id')
        ->join('users', 'ON subject_scheds.teacher_id = users.user_id')
        ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id');
        $this->data['year2'] = $this->db->get_where('subject_scheds', array('schedule_id' => $id, 'subjects.year_level_id' => 2 ))->result_array();

        $i = 0;
        foreach ($this->data['year2'] as $row) {
            $this->data['year2'][$i]['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' => $row['subject_sched_id']))->result_array();
            $i++;
        }


        $this->db->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id')
        ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
        ->join('semester', 'ON subjects.sem_id = semester.sem_id')
        ->join('users', 'ON subject_scheds.teacher_id = users.user_id')
        ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id');
        $this->data['year3'] = $this->db->get_where('subject_scheds', array('schedule_id' => $id, 'subjects.year_level_id' => 3 ))->result_array();

        $i = 0;
        foreach ($this->data['year3'] as $row) {
            $this->data['year3'][$i]['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' => $row['subject_sched_id']))->result_array();
            $i++;
        }


        $this->db->join('subjects', 'ON subject_scheds.subject_id = subjects.subject_id')
        ->join('year_levels', 'ON subjects.year_level_id = year_levels.year_level_id')
        ->join('semester', 'ON subjects.sem_id = semester.sem_id')
        ->join('users', 'ON subject_scheds.teacher_id = users.user_id')
        ->join('rooms', 'ON subject_scheds.room_id = rooms.room_id');
        $this->data['year4'] = $this->db->get_where('subject_scheds', array('schedule_id' => $id, 'subjects.year_level_id' => 4 ))->result_array();

        $i = 0;
        foreach ($this->data['year4'] as $row) {
            $this->data['year4'][$i]['time_scheds'] = $this->db->get_where('time_scheds', array('subject_sched_id' => $row['subject_sched_id']))->result_array();
            $i++;
        }

        $this->load->view('documents/print_schedule', $this->data);
    }

    public function print_daily_collections_get(){
        $date = $this->uri->segment(3);

        $this->load->library('pdf');

        $this->data['date'] = $date;
        $this->data['collections'] = $this->db->join('account_statements', 'receipts.account_sm_id = account_statements.account_sm_id')
                ->join('users', 'ON account_statements.student_id = users.user_id')
                ->get_where('receipts', array('receipt_date' => $date ))->result_array();

        $this->load->view('documents/print_daily_collections', $this->data);
    }

    public function print_monthly_collections_get(){
        $date = $this->uri->segment(3);

        $this->load->library('pdf');

        $this->data['date'] = $date;
        $this->data['collections'] = $this->db->join('account_statements', 'receipts.account_sm_id = account_statements.account_sm_id')
                ->join('users', 'ON account_statements.student_id = users.user_id')
                ->get_where('receipts', array('receipt_date >=' => $date.'-01', 'receipt_date <=' => $date.'-31' ))->result_array();

        $this->load->view('documents/print_monthly_collections', $this->data);
    }

    public function print_class_record_get(){
        $id = $this->uri->segment(3);

        $this->load->library('pdf');

        $this->data['teacher'] = $this->db->join('users', 'ON subject_scheds.teacher_id = users.user_id')
        ->join('subjects', 'subject_scheds.subject_id = subjects.subject_id')
        ->get_where('subject_scheds', array('subject_sched_id' =>$id ))->row_array();

        $this->data['students'] = $this->db->join('account_statements', 'ON account_statements.account_sm_id = enrolled_subjects.account_sm_id')
            ->join('credentials', 'ON credentials.enrolled_subject_id = enrolled_subjects.enrolled_subject_id ')
            ->join('users', 'ON users.user_id = account_statements.student_id')
            ->join('courses', 'ON courses.course_id = users.course_id')
            ->order_by('users.lastname')
            ->get_where('enrolled_subjects', array('subject_sched_id' => $id ))->result_array();

        $this->load->view('documents/print_class_record', $this->data);
    }

    public function print_travel_history_get(){
        $id = $this->uri->segment(3);

        $this->load->library('pdf');

        $this->data['user'] = $this->db->get_where('users', array('user_id' => $id ))->row_array();

        $this->data['resident_logs'] = $this->db->select('log_id, visit_logs.user_id, address, birthdate, contact_number, date_created, email, firstname, gender, lastname, middlename, plate_number, profile_pic, qr_code, token, visiting_point_type, user_status, temperature, log_time, username, visiting_point_address, visit_logs.visiting_point_id, visiting_point_name')->join('visiting_points', 'ON visit_logs.visiting_point_id = visiting_points.visiting_point_id', 'left')->join('users', 'ON visit_logs.user_id = users.user_id', 'left')->get_where('visit_logs', array('visit_logs.user_id' => $id ))->result_array();

        $this->load->view('documents/print_travel_history', $this->data);
    }

    public function print_traced_contacts_get(){
        $id = $this->uri->segment(3);

        $data = array('visiting_point_id' => $this->uri->segment(3),
                        'log_time' => $this->uri->segment(4),
                        'user_id' => $this->uri->segment(5)
                     );

        $this->load->library('pdf');

        $this->data['user'] = $this->db->get_where('users', array('user_id' => $data['user_id'] ))->row_array();

        $this->data['visiting_point'] = $this->db->get_where('visiting_points', array('visiting_point_id' => $data['visiting_point_id'] ))->row_array();

        $this->data['contacts'] = $this->user_model->query_contact_logs($data);

        $this->data['params'] = $data;

        $this->load->view('documents/print_traced_contacts', $this->data);
    }
}
