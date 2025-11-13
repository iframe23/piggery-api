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
class Auth extends CI_Controller {
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model("user_model");
        $this->load->model("auth_model");

        $this->__resTraitConstruct();
        $this->objOfJwt = new ImplementJwt();
        header('Content-Type: application/json');
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function login_post(){

        $input = $this->post();
        
        $data['username'] = $input['username'];
        $data['password'] = md5($input['password']);

        $login_details = $this->auth_model->query_user_by_username_password($data);

        $response['result'] = 'Success';
        $response['type'] = 'success';
        $response['message'] = 'Successfully Logged-in!';

        if ($login_details->num_rows() > 0) {
            $row = $login_details->row_array();
            unset($row['password']);

            if ($row['token'] == '') {
                
                $jwtToken = $this->objOfJwt->GenerateToken($row);
                $this->db->where(array('user_id' => $row['user_id'] ))->update('users', array('token' => $jwtToken));
                $response['username'] = $row['username'];
                $response['token'] = $jwtToken;
                $response['role_id'] = $row['role_id'];
            } else {
                $response['username'] = $row['username'];
                $response['token'] = $this->db->select('token')->from('users')->where('user_id', $row['user_id'])->get()->row()->token;
                $response['role_id'] = $row['role_id'];
            }

        } else {
            $response['result'] = 'Failed';
            $response['type'] = 'danger';
            $response['message'] = 'User does not Exist, try again!';
        }
          
        $this->set_response($response, 200);
    }

    public function verifyLoggedUser_post(){

        $input = $this->post();
        
        $query = $this->auth_model->query_logged_user_token($input);

        if ($query->num_rows() > 0) {
            $response = true;
        } else {
            $response = false;
        }

        $this->set_response($response, 200);
    }

     public function LoginToken_get()
    {
        $tokenData['uniqueId'] = '55555l';
        $tokenData['role'] = 'admin';
        $tokenData['timeStamp'] = Date('Y-m-d h:i:s');
        $jwtToken = $this->objOfJwt->GenerateToken($tokenData);
        echo json_encode(array('Token'=>$jwtToken));
    }
     
    //////// get data from token ////////////
         
    public function GetTokenData_get()
    {
    $received_Token = $this->input->request_headers('Authorization');
        try
            {
            $jwtData = $this->objOfJwt->DecodeToken($received_Token['Token']);
            echo json_encode($jwtData);
            }
            catch (Exception $e)
            {
            http_response_code('401');
            echo json_encode(array( "status" => false, "message" => $e->getMessage()));exit;
            }
    }

    public function checkLogin_post(){
        $input = $this->post();
        $response['message'] = '';

        $query = $this->auth_model->query_logged_user_token($input);

        if ($query->num_rows() < 1) {
            $response['message'] = 'Not existing';
        } else {
            $response['message'] = 'Existing';
        }
        
        $this->set_response($response, 200);
    }

    public function test_get(){

        $response = array(
            'shit' => 'shit',
            'shit2' => 'shit2' 
        );

        $this->set_response($response, 200);
    }
}
