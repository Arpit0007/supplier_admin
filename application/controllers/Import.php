<?php
defined("BASEPATH") OR exit ("Do not access direct script allowed.");
class Import extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->dbforge();
		$this->login_check();
	}
	
	public function index(){
		$submit = $this->input->post('add');
		if($submit != ''){
			//echo phpinfo();die;
			for ($i=0; $i < count($this->input->post('users')); $i++) { 
				if (!$this->db->field_exists(strtolower($this->input->post('users')[$i]), 'new_customers_selection'))
				{
				       $fields = array(
							  	strtolower($this->input->post('users')[$i]) => array(
							    'type' => 'VARCHAR',
							    'constraint' => 100
							  )
							);
				       $this->dbforge->add_column('new_customers_selection', $fields);
				}
			}
			$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);

			foreach($file_data as $row)
			{
				$data[] = array(
					'item_serial_number'	=>	$row["item_serial_number"],
	        		'vendor_no'		=>	$row["vendor_no"],
	        		'vendor_name'	=>	$row["vendor_name"],
	        		'company_comments'	=>	$row["company_comments"],
	        		'nupco_code'	=>	$row["company_comments"],
	        		'item_description'	=>	$row["item_description"],
	        		'quantity'	=>	$row["quantity"],
	        		'price'	=>	$row["price"],
	        		'country'	=>	$row["country"],
	        		'manufacturer'	=>	$row["manufacturer"],
	        		'catalog_no'	=>	$row["catalog_no"],
	        		'commercial_recommendation_status'	=>	$row["commercial_recommendation_status"],
	        		'technical_acceptance_status'	=>	$row["technical_acceptance_status"],
	        		'objection_flag'	=>	$row["objection_flag"],
	        		'negotiation'	=>	$row["negotiation"],
	        		'overall_commercial_recommendation_comments'	=>	$row["overall_commercial_recommendation_comments"],
	        		'number_of_quotations'	=>	$row["number_of_quotations"],
	        		'mdma'	=>	$row["mdma"],
	        		'manufacturing_process_done_locally'	=>	$row["manufacturing_process_done_locally"],
	        		'trade_description'	=>	$row["trade_description"],
	        		'volume_pack_size'	=>	$row["volume_pack_size"],
	        		'item_validity'	=>	$row["item_validity"],
	        		'foc'	=>	$row["foc"],
	        		'tender_name'	=>	'test',
	        		'catalog_link'	=>	$row["catalog_link"],
	        		'tender_id'	=>	$this->input->post('tender'),
				);
			}
			$response = $this->csv_import_model->insert('final_suppliers',$data);
		}
		$data["page_title"] = "Import";
	    $data["main_content"] = "import";
	    $data["module_title"] = " <i class='fa fa-dashboard'></i> Import ";
	    $data["tender_list"] = $this->common_model->get_all_records('tender',array(1=>1));
	    $this->load->view("admin/admin-template",$data);
	}
	public function tender_users(){
		$users = $this->common_model->get_all_records('tender_customer',array('tender_id'=>$this->input->post('id')));
		echo json_encode($users);exit();
	}
	public function login_check(){	
		if(empty($this->session->userdata("id")) && $this->session->userdata("name") != "admin")
		{
			redirect("admin/login");
		}
	}
	
}		