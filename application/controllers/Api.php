<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
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
class Api extends REST_Controller {
  function __construct(){
    // Construct the parent class
    parent::__construct();
    $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
    $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
    $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    $this->load->helper(array('url', 'file','form','path'));
    $this->load->library(array("session","form_validation","notification",'upload','web_notification'));
    $this->load->model("api_model");
  }
  public function user_registration_post(){
    //$otp = mt_rand(0,100000);
    $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
    if ($this->form_validation->run() == TRUE){
      $insert_data=array(
                        'first_name'=>$this->input->post('first_name'),
                        'second_name'=>$this->input->post('second_name'),
                        'family_name'=>$this->input->post('family_name'),
                        'mobile_no'=>$this->input->post('mobile_no'),
                        'dob'=>$this->input->post('dob'),
                        'email'=>$this->input->post('email'),
                        'workdetail'=>$this->input->post('workdetail'),
                        'username'=>$this->input->post('username'),
                        'password'=>$this->input->post('password'),
                        'device_id'=>$this->input->post('device_id'),
                      );
      $response = $this->api_model->insert_record('users',$insert_data);
      if($response){
        $user_data = $this->api_model->get_all_records('users',array('id'=>$response));
        //$check =  $this->common_model->sent_notification('Your Otp is',$otp,'','','',user_firebase_api,$user_data[0]->device_id,'user');
        $this->response([
              'status' => 1,
              'message' => 'Sign Up Successflly',
              'result'=>$user_data[0]
          ]);
      }else{
        $this->response([
                'status' => 0,
                'result' => '',
                'message' => 'Something Went wrong please try again.'
            ]); 
      }
    }else{
        $this->response([
                'status' => 0,
                'result' => '',
                'message' => strip_tags(validation_errors() )
            ]);
    }
  }
  public function login_post(){
    $response = $this->chk_login_curl($this->input->post('username'),$this->input->post('password'));
    if(empty($response)){
      $this->response([
              'status' => 0,
              'result' => '',
              'message' => 'National Id or password is wrong.'
          ]); 
    }else{
        $check = $this->api_model->get_all_records('users',array('username'=>$this->input->post('username')));
         $obj = json_decode($response);
         unset($obj->id);
         if(empty($check)){
            $userdata = $this->api_model->insert_record('users',$obj);
         }else{
           $userdata = $this->api_model->update_record('users',$obj,array('username'=>$this->input->post('username')));
         }
        $update_data = array("device_id" => $this->input->post('device_id'));
        $update_record = $this->api_model->update_record("users",$update_data,array('username'=>$this->input->post('username')));
        $employee = $this->api_model->get_all_records("users",array('username'=>$this->input->post('username')));
      $this->response([
            'status' => 1,
            'message' => 'Successfully logged in.',
            'result'=>$employee[0]
        ]);
    }
  }
  public function loan_history_post(){
       $emp_id= $this->input->post('user_id');
       $employee = $this->api_model->get_all_records("license_request",array('user_id'=>$this->input->post('user_id'),'status'=>2));
       foreach ($employee as $key => $value) {
        $user_data = $this->common_model->select_column('users','first_name,mobile_no',array('id'=>$value->user_id));
        $employee[$key]->first_name = $user_data[0]->first_name;
        $employee[$key]->mobile_no = $user_data[0]->mobile_no;
       }
       if(empty($employee)){
        $this->response([
                'status' => 0,
                'result' => '',
                'message' => 'No Record Found.'
            ]); 
      }else{
          $this->response([
              'status' => 1,
              'message' => 'Record Found',
              'result'=>$employee
          ]);
      }
    } 
    public function pending_loan_post(){
       $emp_id= $this->input->post('user_id');
       $employee = $this->api_model->get_all_records("license_request",array('user_id'=>$this->input->post('user_id'),'status'=>0));
       foreach ($employee as $key => $value) {
        $user_data = $this->common_model->select_column('users','first_name,mobile_no',array('id'=>$value->user_id));
        $employee[$key]->first_name = $user_data[0]->first_name;
        $employee[$key]->mobile_no = $user_data[0]->mobile_no;
       }
       if(empty($employee)){
        $this->response([
                'status' => 0,
                'result' => '',
                'message' => 'No Record Found.'
            ]); 
      }else{
          $this->response([
              'status' => 1,
              'message' => 'Record Found',
              'result'=>$employee
          ]);
      }
    }
    public function emi_calculator_post(){ 
      $p = $this->input->post('amount');
      $r = $this->input->post('interest_rate');
      $t = $this->input->post('time');
      $response = $this->chk_creditscore_curl($this->input->post('username'));
      $emi; 
      // one month interest 
      $r = $r / (12 * 100); 
      // one month period 
      //$t = $t * 12;  
      $emi = ($p * $r * pow(1 + $r, $t)) / (pow(1 + $r, $t) - 1); 
      if(empty($emi)){
        $this->response([
                'status' => 0,
                'result' => '',
                'message' => 'Somthing Went Worng.'
            ]); 
      }else{
          $this->response([
              'status' => 1,
              'message' => 'Your monthly Emi',
              'result'=> number_format((float)$emi, 2, '.', ''),
              'credit_score'=> $response,
          ]);
      }
    } 
    public function generate_otp_post(){
      $otp = mt_rand(0,100000);
      $user_data = $this->common_model->select_column('users','device_id',array('id'=>$this->input->post('user_id')));
      $update = $this->common_model->update_record('users',array('otp'=>$otp),array('id'=>$this->input->post('user_id')));
      $response =  $this->common_model->sent_notification('Your Otp is',$otp,'','','',user_firebase_api,$user_data[0]->device_id,'user');
       if(empty($response)){
        $this->response([
                'status' => 0,
                'result' => '',
                'message' => 'Somthing Went Wrong'
            ]); 
      }else{
        $this->response([
              'status' => 1,
              'message' => 'We Have sent you a OTP On your Phone',
              'result'=>''
          ]);
      } 
    }
    public function loan_application_post(){
      $check = $this->api_model->select_column('users','otp',array('id'=>$this->input->post('user_id'),'otp'=>$this->input->post('otp')));
      if(empty($check)){
        $this->response([
                'status' => 0,
                'result' => '',
                'message' => 'Otp Is wrong'
            ]); 
      }else{
        $insert_data=array(
                      'user_id'=>$this->input->post('user_id'),
                      'mobile_no'=>$this->input->post('mobile_no'),
                      'email'=>$this->input->post('email'),
                      'workdetail'=>$this->input->post('workdetail'),
                      'emergencey_contact_person'=>$this->input->post('emergencey_contact_person'),
                      'emergencey_contact_mobile_number'=>$this->input->post('emergencey_contact_mobile_number'),
                      'bank_name'=>$this->input->post('bank_name'),
                      'iban'=>$this->input->post('iban'),
                      'loan_amount'=>$this->input->post('loan_amount'),
                      'interest_rate'=>$this->input->post('interest_rate'),
                      'monthly_emi'=>$this->input->post('monthly_emi'),
                      'months'=>$this->input->post('months'),
                    );
        $response = $this->api_model->insert_record('license_request',$insert_data);
        if(empty($response)){
          $this->response([
                  'status' => 0,
                  'result' => '',
                  'message' => 'Somthing Went Wrong'
              ]); 
        }else{
          
            $this->response([
                'status' => 1,
                'message' => 'Request Added Successfully',
                'result'=>''
            ]);
        }
      }
    } 
    public function exist_device($table,$login_key){
      $result_device = $this->api_model->select_column($table,'login_key',array('login_key'=>$login_key));
      if(empty($result_device)){
        $result = array('status'=>5);
        $this->response([
                'status' => 5,
                'message' => 'Your session is expire.',
                'result'=>""
            ]);
      }
    }
    public function chk_login_curl($national_id,$password){
      $username = $national_id; 
      $password = $password; 
      //$url="https://ansara.in/absher/admin/login_chk"; 
      $url="http://192.168.0.121/absher/admin/login_chk";
      $postdata = "username=".$username."&password=".$password; 

      $ch = curl_init(); 
      curl_setopt ($ch, CURLOPT_URL, $url); 
      curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
      curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
      curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
      curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt ($ch, CURLOPT_REFERER, $url); 
      curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata); 
      curl_setopt ($ch, CURLOPT_POST, 1); 
      $result = curl_exec ($ch);
      //print_r(curl_error($ch));die;
      curl_close($ch);
      return $result;
    }
    public function chk_creditscore_curl($national_id){
      $username = $national_id; 
      //$url="https://ansara.in/sima/admin/login_chk"; 
      $url="http://192.168.0.121/sima/admin/creditscore_chk"; 
      $postdata = "username=".$username; 
      $ch = curl_init(); 
      curl_setopt ($ch, CURLOPT_URL, $url); 
      curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
      curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
      curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
      curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0); 
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt ($ch, CURLOPT_REFERER, $url); 
      curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata); 
      curl_setopt ($ch, CURLOPT_POST, 1); 
      $result = curl_exec ($ch); 
      curl_close($ch);
      return $result;
    }
    public function do_upload($file_name,$uplaod_loc) { 
         $config['upload_path']   = $uplaod_loc; 
         $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
         $config['max_size']      = 2048; 
         $config['max_width']     = 1024; 
         $config['max_height']    = 768;  
         $config['encrypt_name'] = true;
         $this->upload->initialize($config);
        $data = '';
         if ( ! $this->upload->do_upload($file_name)) {
            $data = array('status'=>0,'error' => $this->upload->display_errors()); 
         }
          else { 
            $data = array('status'=>1,'upload_data' => $this->upload->data()); 
         } 
         return $data;
    }
    
}    
   