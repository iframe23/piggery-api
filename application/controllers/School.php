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
class School extends CI_Controller {
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

        $this->__resTraitConstruct();
        $this->objOfJwt = new ImplementJwt();
        header('Content-Type: application/json');

    }

    //Get

    public function departments_get(){
        $query = $this->db->get('departments')->result_array();
        $this->set_response($query, 200);
    }

    public function rooms_get(){
        $query = $this->db->get('rooms')->result_array();
        $this->set_response($query, 200);
    }

    public function courses_get(){
        $query = $this->db->get('courses')->result_array();
        $i = 0;
        foreach ($query as $row) {
            $query[$i]['department'] = $this->school_model->department($row['department_id'], true);
            $i++;
        }
        
        $this->set_response($query, 200);
    }

    public function courses_post(){
        $input = $this->post();

        $query = $this->db->get_where('courses', $input)->result_array();

        $this->set_response($query, 200);
    }

    public function subject_credential_post(){
        $input = $this->post();

        $this->db->join('transcripts', 'ON transcripts.transcript_id = credentials.transcript_id');
        $query = $this->db->get_where('credentials', $input);

        if ($query->num_rows() == 0) {
            $query->equivalent_grade = 0;
        } else {
            $query = $query->row_array();
        }

        $this->set_response($query, 200);
    }

    //Add
    public function add_room_post(){
        $input = $this->post();

        $response = $this->school_model->insert_room($input);

        $this->set_response($response, 200);
    }

    public function add_department_post(){
        $input = $this->post();

        $response = $this->school_model->insert_department($input);

        $this->set_response($response, 200);
    }

    public function add_course_post(){
        $input = $this->post();

        $response = $this->school_model->insert_course($input);

        $this->set_response($response, 200);
    }

    public function submit_grade_post(){
        $input = $this->post();

        $this->db->where('credential_id', $input['credential_id'])->update('credentials', $input);

        $response = array(
            'result' => 'Success',
            'message' => 'The entered grades has been successfully submitted',
            'type' => 'success'
        );

        $this->set_response($response, 200);
    }

    //Edit
    public function edit_room_post(){
        $input = $this->post();

        $response = $this->school_model->update_room($input);
        
        $this->set_response($response, 200);
    }

    public function edit_department_post(){
        $input = $this->post();

        $response = $this->school_model->update_department($input);
        
        $this->set_response($response, 200);
    }

    public function edit_course_post(){
        $input = $this->post();

        $response = $this->school_model->update_course($input);
        
        $this->set_response($response, 200);
    }

    //misc
    public function current_sem_get(){
        $cur_sem = $this->curriculum_model->current_sem();

        $this->set_response($cur_sem, 200);
    }

    public function current_sy_get(){
        $cur_sy = $this->curriculum_model->current_sy();

        $this->set_response($cur_sy, 200);
    }
}
