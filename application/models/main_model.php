<?php
class Main_model extends CI_Model {
	var $retVal = array();

	public function __construct()
	{
		$this->load->database();
	}
	
	public function change_conn($cd){
		$config['hostname'] = $cd['hostname'];
		$config['username'] = $cd['username'];
		$config['password'] = $cd['password'];
		$config['database'] = $cd['campaign_db'];
		$config['dbdriver'] = "mysql";
		$config['dbprefix'] = "";
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;

		return $this->load->database($config,true);
	}
	

    function get_fixed_where($leadIdentity,$record_type=''){
        $userType = $this->session->userdata('user_type');
        $userID = $this->session->userdata('username');

        //if not admin then get all records assign to user
        if($userType != ADMIN_CODE){
					
          $this->db->where('assigned_agent',$userID);
				
					/*  NOT needed for onlineapp					
					$li = array();
					if(count($leadIdentity) > 0){
							foreach($leadIdentity as $key=>$val){
									$li[] = $key;
							}
							$this->db->where_in('lead_identity',$li);
					}else{
							$this->db->where('lead_identity',"'1'"); //no lead identity assigned
					} */
        }
    }
	
	function setPostDataWhere(){	
		$pdata = $this->input->post();

		if($pdata['category'] != '')
			$this->db->where('tickets.category', $pdata['category']);

		if($pdata['priority'] != '')
			$this->db->where('tickets.priority', $pdata['priority']);
		 
		if(isset($pdata['include_completed'])){
			//IF set to completed then search all the category
		}else{
			$this->db->where('tickets.status != ', 'completed');
		}
		
		if($pdata['ticket_id'] != '')
			$this->db->where('tickets.id', $pdata['ticket_id']);
		
		
	}

	public function search_ticket_list(){
		$pdata = $this->input->post();
		$retVal = array(); 
		$userType = $this->session->userdata('user_type');
		$userID = $this->session->userdata('username');
		$selectFields = '*' ;

		$this->db->select($selectFields,FALSE)->from('tickets');	
		
		if(isset($pdata['all_tickets'])){
			//AS of now ONLY admin has this priv
			//search to all tickets regardless the send_to
		}elseif(isset($pdata['include_own_ticket'])){
			$where = "(send_to = '{$userType}' OR created_by = '{$userID}')";
			$this->db->where($where);
		}else{
			$this->db->where('send_to',$userType);
		}
		
		$this->setPostDataWhere();
		
		// need to GROUP BY contact_list.id because it might get many records on supplementary table (06/11/2016)
		$retVal = $this->db
						->order_by('priority = "high"','DESC')
						->order_by('priority = "medium"','DESC')
						->order_by('priority = "low"','DESC')
						->order_by('date_created','ASC')
						->get()
						->result_array();
						
		//echo $this->db->last_query();				
		return $retVal; 
	}

	/**
	
	**/
	public function saveTicket(){
		$pdata = $this->input->post(); //make it sure all the inputs from the SCREEN is available in the ticket table
		$today = date('Y-m-d H:i:s');
		$userID = $this->session->userdata('username');
		$dontcount = isset($pdata['dont_count']) ? 1 : 0;
		$insert_arr = array_merge(
									$pdata,
									array('date_created'=>$today,'created_by'=>$userID)
								);
						
		$this->db->insert('tickets',$insert_arr);
		
	}
	
	public function getTicketInfo($ticket_id){
		$ticket_info = $this->db->from('tickets')
								->where('id',$ticket_id)
								->get()
								->result_array();	
		return $ticket_info[0]; //single array
	}
	
	
	
	function get_history($id){
		$user_role = $this->session->userdata('user_type');
		$this->db->from('ticket_history')
				->where('ticket_id',$id);
				
		
		return $this->db->order_by('date_created','DESC')
					->get()
					->result_array();
	}
	
	public function addTicketRemarks($ticket_id){
		$pdata = $this->input->post(); //make it sure all the inputs from the SCREEN is available in the ticket table
		$send_to = $pdata['send_to'];
		$priority = $pdata['priority'];
		$today = date('Y-m-d H:i:s');
		$userID = $this->session->userdata('username');
		$insert_arr = array(
							'date_created'=>$today,
							'created_by'=>$userID,
							'ticket_id'=>$ticket_id,
							'comments'=>$pdata['remarks'],
							'send_to'=>$send_to,
							'status'=>$pdata['ticket_status'],
							);
						
		$this->db->insert('ticket_history',$insert_arr);
		
		
		$ticket_update = array('status'=>$pdata['ticket_status']);
		
		$send_to_arr = array();
		if(!empty($send_to)){	
			//since send_to is not required during adding remarks on the ticket
			$send_to_arr = array('send_to'=>$send_to);
			$ticket_update = array_merge($ticket_update,$send_to_arr);
		}
		
		if(!empty($priority)){	
			//since send_to is not required during adding remarks on the ticket
			$priority_arr = array('priority'=>$priority);
			$ticket_update = array_merge($ticket_update,$priority_arr);
		}
		
		$this->db->where('id',$ticket_id)->update('tickets',$ticket_update);
		
	}
	
}