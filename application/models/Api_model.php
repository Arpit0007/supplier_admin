<?php
defined("BASEPATH") OR exit("Do not access direct script allowed.");
class Api_model extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

	}
	public function get_all_records($table_name,$where='',$short_by='',$where_or=''){
		if($where){
			$this->db->where($where);
		}
		if($where_or){
			$this->db->or_where($where_or);
		}
		if($short_by){
			if($short_by=='id'){
				$this->db->order_by('id', 'desc');
			}			
		}
		$query = $this->db->get($table_name);
		return $query->result();
	}	
	public function get_all_record_like($table_name,$like){
		
		$this->db->like($like);  
		
		$query = $this->db->get($table_name);
		return $query->result();
	}
	public function simple_join($table_name,$join_table,$where){
		$this->db->select(''.$table_name.'.*,'.$join_table.'.name as pet_name');
		$this->db->from($table_name);
		$this->db->join($join_table, ''.$join_table.'.id = '.$table_name.'.pet_id');
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	public function insert_record($table_name,$data){
		$this->db->insert($table_name, $data);
		return $insert_id = $this->db->insert_id();

	}
	public function update_record($table_name,$data,$where){
		$this->db->where($where);
		$this->db->update($table_name, $data);
		return true;
	}
	public function delete_data($table_name,$where){
		$this->db->where($where);
		$this->db->delete($table_name);
		return true;
	}
	public function select_column($table_name,$select,$where=''){
		$this->db->select($select);
		$this->db->from($table_name);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();

    }
    public function get_all_record_mothly_wise($table_name,$branch_id,$type){
		$this->db->select('COUNT(id) ids,MONTH(created_date) month,type ,MONTHNAME(created_date) monthname');
		$this->db->group_by('type,MONTH(created_date)');
		$this->db->from($table_name);
		$this->db->where("branch_id=".$branch_id." AND type='".$type."' And created_date > DATE_SUB(now(), INTERVAL 8 MONTH)");
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->result_array();
	}
	 public function get_all_record_mothly_wise_restaurant($table_name,$restaurant_id,$type){
		$this->db->select('COUNT(id) as ids,MONTH(created_date) month,type ,MONTHNAME(created_date) monthname');
		$this->db->group_by('type,MONTH(created_date)');
		$this->db->from($table_name);
		$this->db->where("restaurant_id=".$restaurant_id." AND type='".$type."' And created_date > DATE_SUB(now(), INTERVAL 8 MONTH)");
		$query = $this->db->get();
		return $query->result_array();
	}
	public function get_all_record_day_wise($table_name,$branch_id,$type,$month){
		$this->db->select('created_date as x , count(id) as y ');
		$this->db->group_by('created_date,type');
		$this->db->from($table_name);
		$this->db->where("branch_id=".$branch_id." AND type='".$type."' And MONTH(created_date) = MONTH('".$month."') AND YEAR(created_date) = YEAR(CURRENT_DATE())");
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->result_array();
	}
	public function all_record_days_restaurant($table_name,$branch_id){
		$this->db->select('count(id) as per_day , created_date , name as branch_name');
		$this->db->group_by('created_date');
		$this->db->from($table_name);
		$this->db->where("branch_id=".$branch_id." And MONTH(created_date) = MONTH(CURRENT_DATE()) AND YEAR(created_date) = YEAR(CURRENT_DATE())");
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->result();
	}
	public function get_all_recordsss($table_name,$where='',$short_by='',$where_or=''){
		if($where){
			$this->db->where($where);
		}
		if($where_or){
			$this->db->or_where($where_or);
		}
		if($short_by){
			if($short_by == 'low'){
				$this->db->order_by('price', 'asc');
			}elseif($short_by == 'high'){
				$this->db->order_by('price', 'desc');
			}elseif($short_by=='most_populer'){
				$this->db->order_by('popular', 'desc');
			}elseif($short_by=='name'){
				$this->db->order_by('name', 'asc');
			}
			
		}
		$query = $this->db->get($table_name);
		$result = $query->result_array();
		return $result;
	}
	 public function sent_notification($title,$message,$imageUrl,$action,$actionDestination,$firebase_api,$device_id,$type){
		
		if($type=="user"){
			$this->notification->setTitle($title);
			$this->notification->setMessage($message);
		}
		$this->notification->setImage($imageUrl);
		$this->notification->setAction($action);
		$this->notification->setActionDestination($actionDestination);
		$requestData = $this->notification->getNotificatin();
		$fields = array(
			'to' => $device_id,
			'data' => $requestData,
		);
		// Set POST variables
		$url = 'https://fcm.googleapis.com/fcm/send';
		if($type=="user"){
			$headers = array(
				'Authorization: key=' .$firebase_api,
				'Content-Type: application/json'
			);
		}elseif($type=="driver"){
			$headers = array(
				'Authorization: key=' .$firebase_api,
				'Content-Type: application/json'
			);
		}

		// Open connection
		$ch = curl_init();

		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Disabling SSL Certificate support temporarily
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		// Execute post
		$result = curl_exec($ch);
		if($result === FALSE){
			die('Curl failed: ' . curl_error($ch));
		}

		// Close connection
		curl_close($ch);
	}
	function check_unique_name($table_name,$id = '', $name) {
        $this->db->where('mobile', $name);
        if($id) {
            $this->db->where_not_in('id', $id);
        }
        return $this->db->get($table_name)->num_rows();
    }
    function check_unique($table_name,$id,$value,$column) {
        $this->db->where($column, $value);
        if($id) {
            $this->db->where_not_in('id', $id);
        }
        return $this->db->get($table_name)->num_rows();
    }
    function check_unique_res_code($table_name,$id = '', $name) {
        $this->db->where('resturant_code', $name);
        if($id) {
            $this->db->where_not_in('id', $id);
        }
        //$this->db->get($table_name);
        //echo $this->db->last_query();die;
        return $this->db->get($table_name)->num_rows();
    }
	public function sent_notification_web($token,$title,$message_body,$order_id,$icon,$redirect_url)
	{ 
		/*echo $token."<br>";
		echo $title."<br>";
		echo $message_body."<br>";
		echo $order_id."<br>";
		echo $icon."<br>";
		echo $redirect_url."<br>";*/
		$serverObject = new Web_notification(); 
		$jsonString = $serverObject->sendPushNotificationToFCMSever($token,$message_body,'1',$title,$order_id,$icon,$redirect_url);  
	}
}