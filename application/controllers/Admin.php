<?php
defined("BASEPATH") OR exit ("Do not access direct script allowed.");
class Admin extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('qr_code');
	}
	public function login()
	{
		$submit = $this->input->post("login_submit");
		if(isset($submit))
		{
			$validation_array = array(
				array(
					"field" => "username",
					"label" => "Username",
					"rules" => "required|trim"
				),
				array(
					"field" => "password",
					"label" => "Password",
					"rules" => "required"
				)
			);
			$this->form_validation->set_rules($validation_array);
			if($this->form_validation->run() == TRUE)
			{
				$username = $this->input->post("username");
				$password = $this->input->post("password");
				$where = " username='".$username."' AND password='".$password."' AND status=1 ";
				$response = $this->common_model->get_all_records("admin",$where);
				if(count($response) > 0)
				{  
					$this->session->set_flashdata('message' , '<div class="alert alert-success alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close" >×</button><strong>Error!</strong> Successfully logged in as an admin</div>');
					$session_array = array(
						"id"=> $response[0]->id,
						"name"=> $response[0]->name,
						//"user_type"=> $response[0]->user_type,
					);
					$this->session->set_userdata($session_array);
					redirect("admin");
				}else{
					$this->session->set_flashdata('message' , '<div class="alert alert-error alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button><strong>Error!</strong> Username or password is does not match </div>');
				}
			}
		}
		$this->load->view("admin/login");
	}
	public function index(){
		$this->login_check();
		$data["page_title"] = "Dashboard";
	    $data["main_content"] = "index";
	    $data["module_title"] = " <i class='fa fa-dashboard'></i> Dashboard ";
	    $this->load->view("admin/admin-template",$data);
	}
	public function change_status(){
		$id = $this->input->post('id');
		$table = $this->input->post('table_name');
		$update_status = 0;
		$class = '';
		$status_text = '';
		$current_status = $this->common_model->select_column($table,'status',array('id'=>$id));
		if($current_status[0]->status==0){
			$update_status = 1;
			$class = 'bg-green';
			$status_text = 'Active';
		}else{
			$update_status = 0;
			$class = 'bg-red';
			$status_text = 'Inactive';
		}
		$response = $this->common_model->update_record($table,array('status'=>$update_status),array('id'=>$id));
		echo json_encode(array('class'=>$class,'status_text'=>$status_text));
	}
	public function delete(){
		/*$result = $this->common_model->select_column($this->uri->segment(4),'image',array('id'=>$this->uri->segment(3)));
		if(!empty($result) && $result[0]->image){
			unlink('./upload/drivers/'.$result[0]->image);
		}*/
		$response = $this->common_model->delete_data($this->uri->segment(4),array('id'=>$this->uri->segment(3)));
		if($response)
		{  	
			$this->session->set_flashdata("message" , "<div class='alert  alert-success alert-dismissible' >Record deleted Successfully.<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button></div>");
            if(!empty($this->uri->segment(5))){
            	redirect($this->uri->segment(5));
            }else{
            	redirect($this->uri->segment(4));
            }
			
		}else{

			$this->session->set_flashdata("message" , "<div class='alert  alert-danger alert-dismissible'>Some error occured! Please check<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button></div>");
            redirect($this->uri->segment(4));
		}
	}
	public function check_exist(){
		$status = 1;
		$message = '';
		$result = $this->common_model->check_unique($this->input->post('table_name'),$this->input->post('id'), $this->input->post('value'), $this->input->post('column'));
	    if($result == 0)
	        $status = 1;
	    else {
	        $message = ucfirst($this->input->post('column')).' must be unique';
	        $status = 0;
	    }
	    echo json_encode(array('status'=>$status,'message'=>$message));

	}
	public function login_check(){	
		if(empty($this->session->userdata("id")) && $this->session->userdata("name") != "admin")
		{
			redirect("admin/login");
		}
	}
	public function logout()
	{
		$this->session->sess_destroy();
		redirect("admin/login");
	}
}		