<?php
defined("BASEPATH") OR exit ("Do not access direct script allowed.");
class Plant extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		//$this->login_check();
	}
	
	public function index(){
		$submit = $this->input->post('add');
		if($submit != ''){
		    unset($_POST['add']);
	  		$response = $this->common_model->insert_record('plants',$_POST);
	  		if(!empty($response)){
		    	$this->session->set_flashdata('message' , '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close" >×</button><strong>Success!</strong> Plant Added Successfully</div>');
		    }else{
		    	$this->session->set_flashdata('message' , '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close" >×</button><strong>Success!</strong> Something went wrong please try again.</div>');
		    }
		}
		$data["page_title"] = "add_plant";
	    $data["main_content"] = "add_plant";
	    $data["module_title"] = " <i class='fa fa-dashboard'></i> Add Plant ";
	    $data["department_list"] = $this->common_model->get_all_records('plants',array(1=>1));
	    $this->load->view("admin/admin-template",$data);
	}
	public function update_plant(){
		
		$submit = $this->input->post("update");
		if(isset($submit))
		{ 
			$validation_array = array(
				array(
					"field" => "name",
					"label" => "name",
					"rules" => "required"
				)
			);
			$this->form_validation->set_rules($validation_array);
			if($this->form_validation->run() == TRUE)
			{  
				$where = array('id'=>$this->input->post('id'));
				$update_data = array(
									'name'=>$this->input->post('name'),	
				    			);
				$response = $this->common_model->update_record("plants",$update_data,$where);
				if($response)
				{  	
					$this->session->set_flashdata("message" , "<div class='alert  alert-success alert-dismissible' >User Upadate Successfully.<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span></button></div>");
					redirect("plant");
				}else{
					$this->session->set_flashdata("message" , "<div class='alert  alert-danger alert-dismissible'>Some error occured! Please check<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span></button></div>");
				}
			}
		}
		$this->load->view("admin/admin-template",$data);
	}
}		