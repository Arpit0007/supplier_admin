<?php
defined("BASEPATH") OR exit ("Do not access direct script allowed.");
class Asupplier_users extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		//$this->login_check();
	}
	
	public function index(){
		$data["page_title"] = "Users";
	    $data["main_content"] = "asn_supplier_user_list";
	    $data['users'] = $this->common_model->get_all_records('users',array('user_type'=>2));
	    $data["module_title"] = " <i class='fa fa-users'></i> Member ";
	    $this->load->view("admin/admin-template",$data);
	}
	public function add_supplier_user(){
		$submit = $this->input->post('add');
		if($submit != ''){
		   if($this->form_validation->run('add_user') == TRUE){  
		   		unset($_POST['add']);
		   		$_POST['user_type'] = 2;
		  		$response = $this->common_model->insert_record('users',$_POST);
		  		if(!empty($response)){
			    	$this->session->set_flashdata('message' , '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close" >×</button><strong>Success!</strong> User Added Successfully</div>');
			    	redirect("asupplier_users");
			    }else{
			    	$this->session->set_flashdata('message' , '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close" >×</button><strong>Success!</strong> Something went wrong please try again.</div>');
			    }
			}
		}
		$data["page_title"] = "Add user";
	    $data["main_content"] = "add_asn_supplier_user";
	    $data["module_title"] = " <i class='fa fa-user'></i> User";
	    $data["department_list"] = $this->common_model->get_all_records('plants','');
	    //$data["tender_list"] = $this->common_model->get_all_records('tender','');
	    $this->load->view("admin/admin-template",$data);
	}
	public function update_supplier_user(){
		$data["page_title"] = "Update Users";
		$data["main_content"] = "asn_edit_supplier_user";
		$data["module_title"] = " <i class='fa fa-handshake-o'></i> Update Supplier user";
		$data['user_data'] = $this->common_model->get_all_records("users",array('id'=>$this->uri->segment(3)));
		$data["department_list"] = $this->common_model->get_all_records('plants','');
	   	$submit = $this->input->post("update");
		if(isset($submit)){ 
			$where = array('id'=>$this->input->post('id'),'user_type'=>2);
			$update_data = array(
								'name'=>$this->input->post('name'),	
			    				'email'=>$this->input->post('email'),	
			    				'mobile_no'=>$this->input->post('mobile_no'),	
			    				
			    				'username'=>$this->input->post('username'),
			    				'password'=>$this->input->post('password'),
			    			);
			//print_r($update_data);die;
			$response = $this->common_model->update_record("users",$update_data,$where);
			if($response){  	
				$this->session->set_flashdata("message" , "<div class='alert  alert-success alert-dismissible' >User Upadate Successfully.<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span> </button></div>");
				redirect("asupplier_users");
			}else{
				$this->session->set_flashdata("message" , "<div class='alert  alert-danger alert-dismissible'>Some error occured! Please check<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span></button></div>");
			}
		}
		$this->load->view("admin/admin-template",$data);
	}
	public function do_upload($file_name,$uplaod_loc) { 
         $config['upload_path']   = $uplaod_loc; 
         $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
         //$config['max_size']      = 2048; 
         //$config['max_width']     = 1024; 
         //$config['max_height']    = 768;  
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