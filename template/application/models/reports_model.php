<?php
Class Reports_model extends CI_Model{ 
	var $contactIDs = array();
	var $suppleDetails = array();//arr of detail for supple
	var $cardDetails = array();//arr of dtails for card details
	var $agDetails = array(); //agreement details
	
	public function __construct()
	{
		$this->load->database();
	}
	
	public function generate_report(){
		$pdata = $this->input->get();
		$retVal = array();
		$this->db->select('*')
						->from('contact_list')
						->where('callresult','AG');

		if(!empty($pdata['startDate']) && !empty($pdata['endDate'])){
			$this->db->where('calldate >= ' , "{$pdata['startDate']} 00:00:00")
				 ->where('calldate <= ' , "{$pdata['endDate']} 23:59:59");
		}


		$result = $this->db->get()->result_array();
		foreach($result as $details){
			$this->contactIDs[$details['id']] =$details['id'];
			$this->agDetails[$details['id']] = $details;
		}

		if(count($result)<=0){
			echo "No RECORD FOUND!";
		}else{
			$this->get_supple();
			$this->get_cards();
		}

	}
	
	function get_supple(){
		$result = $this->db->select('*')
					->from('supplementary')
					->where_in('baserecid',$this->contactIDs)
					->get()
					->result_array();
		
		foreach($result as $details){
			$this->suppleDetails[$details['baserecid']][$details['id']] = $details;
		}
	}
	
	function get_cards(){
		$result = $this->db->select('*')
					->from('card_details')
					->where_in('baserecid',$this->contactIDs)
					->order_by('issuer')
					->get()
					->result_array();
		
		foreach($result as $details){
			$this->cardDetails[$details['baserecid']][$details['issuer']][$details['id']] = $details;
		}
	}
}
?>