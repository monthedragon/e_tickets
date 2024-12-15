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
	
	//get random record
	public function get_rnd_record()
	{
		$id = 0;
        $id = $this->get_virgin_record();

        if($id == 0)
			$id = $this->get_recon();
			
		if($this->is_with_locked($id) && $id!=0)
			return 'A'; //with locked record ;errMsg
		elseif($this->is_locked($id) && $id!=0)
			return 'B'; //if record picked is already locked to other agent ;errMsg
		elseif($id == 0)
			return 'C'; //no more records, please check helpers/site_helper.php for err msg reference
		else
			return $id; 
			
	}

    function get_fixed_where($leadIdentity){
        $userType = $this->session->userdata('user_type');
        $userID = $this->session->userdata('username');

        //if not admin then get all records assign to user
        if($userType != ADMIN_CODE){
            $this->db->where('assigned_agent',$userID);

            $li = array();
            if(count($leadIdentity) > 0){
                foreach($leadIdentity as $key=>$val){
                    $li[] = $key;
                }
                $this->db->where_in('lead_identity',$li);
            }else{
                $this->db->where('lead_identity',"'1'"); //no lead identity assigned
            }
        }
    }

	function get_virgin_record()
	{
        $userID = $this->session->userdata('username');
        $leadIdentity = $this->get_lead_identity_assigned($userID);
        $this->db->select('id')
            ->from('contact_list')
            ->where('is_active',1)
            ->where('is_locked',0)
            ->where('calldate is null');

        $this->get_fixed_where($leadIdentity);

        $result = $this->db->order_by('calldate','DESC')
            ->limit('1')
            ->get()
            ->result_array();

		if(count($result) > 0)
			return $result[0]['id'];
		else
			return 0;
	}
	
	//bs and NA
	function get_recon()
	{
        $userID = $this->session->userdata('username');
        $leadIdentity = $this->get_lead_identity_assigned($userID);
		$this->db->select('id')
				->from('contact_list')
				->where('is_active',1)
				->where('is_locked',0)
				->where_in('callresult',array('BS','NA',''))
                ->where('calldate is not null');

        $this->get_fixed_where($leadIdentity);

        $result = $this->db->order_by('calldate','DESC')
				->limit('1')
				->get()
				->result_array();


		if(count($result) > 0)
			return $result[0]['id'];
		else
			return 0;
	}
	  
	  
	public  function get_locked_record(){
		$username = $this->session->userdata('username');
		return $this->db->select('*')->from('contact_list')
						->where('is_locked',1)
						->where('is_active',1)
						->where('agent',$username)
						->get()
						->result_array();
	}

	public  function get_popout_records(){
		$username = $this->session->userdata('username');
		return $this->db->select('*')->from('contact_list')
						->where('forcedpop',1)
						->where('is_active',1)
						->where('agent',$username)
						->get()
						->result_array();
	}
	
	public  function get_callback_record(){
		$username = $this->session->userdata('username');
        $leadIdentity = $this->get_lead_identity_assigned($username);
		$this->db->select('*')->from('contact_list')
						->where('callresult',CB_TAG)
						->where('agent',$username)
						->where('is_active',1);

        $this->get_fixed_where($leadIdentity);

        return $result = $this->db->get()
						->result_array();
	}	
	
	public function search_contact_list($all){
		$retVal = array();
        $userType = $this->session->userdata('user_type');
        $userID = $this->session->userdata('username');
        $leadIdentity = $this->get_lead_identity_assigned($userID);
        $selectFields = 'id,
                        firstname,
                        lastname,
                        pd_name,
                        calldate,
                        callresult,
                        sub_callresult,
                        is_active,
                        forcedpop,
                        ag_type';

        $this->db->select($selectFields)->from('contact_list');

		if($all)
            $this->db->where('is_active',1);
		else{
		
			$pdata = $this->input->post();

			if($pdata['firstname'] != '')
				$this->db->like('firstname', $pdata['firstname']);

			if($pdata['lastname'] != '')
				$this->db->like('lastname', $pdata['lastname']);

            if($pdata['pd_name'] != '')
                $this->db->like('pd_name', $pdata['pd_name']);

            if(!isset($pdata['show_all']))//show all record even not active for admin use only
				$this->db->where('is_active',1);
		}

        $this->get_fixed_where($leadIdentity);

		$retVal = $this->db->order_by('calldate is null','DESC')
						->order_by('callresult = ""','DESC')
						->order_by('callresult = "BS"','DESC')
						->order_by('callresult = "NA"','DESC')
						->order_by('callresult = "CB"','DESC')
						->order_by('calldate')
						->limit(LIMIT)
						->get()
						->result_array();
		return $retVal; 
	}

    function get_assign_lead_identity($userid){

    }
	
	//$action = 0 edit; 1 = view onely ;
	function get_details_byID($id,$action){
		if($this->is_with_locked($id) && $action==0)
			return 0; //with locked record ;errMsg
		elseif($this->is_locked($id) && $action==0)
			return 1; //if record picked is already locked to other agent ;errMsg
		else{
			$username = $this->session->userdata('username');

			if($action == 0){ //set locked to record if action = edit
				$update = array('is_locked'=>1,'agent'=>$username);
				$this->db->where('id',$id)->update('contact_list',$update);
			}
			
			return $this->get_record_by_id($id);
		}
	}
	
	function get_record_by_id($id)
	{
		return $this->db->get_where('contact_list',array('id'=>$id))->result_array();
	}
	
	public function dopop($id,$action)
	{
        $pdata = $this->input->post();
        $this->db->where('id',$id)->update('contact_list',array('forcedpop'=>$action,'agent'=>$pdata['pop_to']));
	}
	
	function get_history($id){
		return $this->db->select("trans_log.*")
				->from('trans_log')
				->where('contact_id',$id)
				->order_by('time_stamp','DESC')
				->get()
				->result_array();
	}
	
	
	function is_with_locked($id){
		$username = $this->session->userdata('username');
		
		//check if with locked record and the ID is not equal to picked record
		$result = $this->db->select('*')
						->from('contact_list')
						->where('is_locked',1)
						->where('agent',$username)
						->where('id !=',$id)
						->get();
		
		//with locked record
		if($result->num_rows() > 0)
			return 1;
		else
			return 0;
		
	}
	
	function is_locked($id){  
		$username = $this->session->userdata('username');
		$result = $this->db->select('*')
						->from('contact_list')
						->where('is_locked',1)
						->where('id',$id)
						->where('agent !=',$username)
						->get();
		
		//locked record to other agent
		if($result->num_rows() > 0)
			return 1;
		else
			return 0;
		
	}
	
	function save($id){
		$pdata = $this->input->post();   
		if($id != 0){
			$pdata['calldate'] = date('Y-m-d H:i:s');
			
			//this field is only for trans log 
			unset($pdata['remarks']);
			
			//unlocked the record,even the pop to 0
			$pdata  = array_merge($pdata,array('is_locked'=>0,'forcedpop'=>0)); 
			$this->db->where("id",$id)->update('contact_list',$pdata);
			
			
			//add to trans logs
			$this->update_trans_logs($id);
		}else
			$this->db->insert('contact_list',$pdata);
		
	}
	
	function update_trans_logs($id,$callresult=''){
		$pdata = $this->input->post(); 
		
		if($callresult == '')
			$callresult = $pdata['callresult'];
		
		//IF CALLBACK, then set the callback date and time set by the user in remarks
		if($pdata['callresult'] == CB_TAG)
			$pdata['remarks'] .= "<br><br> Callback date and time : {$pdata['callbackdate']}  {$pdata['callbacktime']}";

        $subCallresult= (isset($pdata['sub_callresult']) ? $pdata['sub_callresult'] : '') ;
        $agType= (isset($pdata['ag_type']) ? $pdata['ag_type'] : '') ;
		
		$username = $this->session->userdata('username');
		$transLog = array('contact_id'=>$id,
						  'callresult'=>$callresult, 
						  'sub_callresult'=>$subCallresult,
                          'ag_type'=>$agType,
						  'dob'=>$pdata['c_dob'], 
						  'gender'=>$pdata['c_gender'],   
						  'user_id'=>$username,
						  'remarks'=>$pdata['remarks']);
						  
		$this->db->insert('trans_log',$transLog);
	}
	
	public function set_uploading_folder(){
		$rootFolder = './uploads/';
		return $rootFolder;
	}
	
	function is_restricted_field($col){
		//add restricted field here
		$restrictedFields  = array('id','lead_identity');
		 
		if(in_array($col,$restrictedFields))
			return 1;
		else
			return 0;
	}
	
	function check_column_alter($col){
		if(!$this->is_restricted_field($col)){
		
			$fields = mysql_list_fields(MAIN_DB, CONTACT_LIST);
			$columns = mysql_num_fields($fields);
			for ($i = 0; $i < $columns; $i++) {$field_array[] = mysql_field_name($fields, $i);}

			if (!in_array($col, $field_array)){
				
				$sql = 'ALTER TABLE '.CONTACT_LIST.
						' ADD `' . $col . '` CHAR(60) NOT NULL';
				$this->db->query($sql);
			}
			
		}
	}
		  
	function structure_column($col){
		$col = str_replace("'","",$col);
		$col = str_replace(' ','_',$col);
		$col = preg_replace('/[^A-Za-z0-9\_-]/', '', $col);
		return strtolower(trim($col));
	}
	
	function do_batch_disable(){
		$udata = $this->upload->data();  
		$pdata = $this->input->post(); 
		$doDelete = (isset($pdata['do_delete']) ? 1 : 0);
		
		$allowedTypes = array('application/msword','application/vnd.ms-excel');
		 
		$validValues = array('id','is_active');
		
		if(!in_array($udata['file_type'],$allowedTypes))
			return 2;
		else{
		
			//read the db xls
			$dataXls = new Spreadsheet_Excel_Reader();
			$dataXls->read($udata['full_path']); 
			
			for ($j = 1; $j < $dataXls->sheets[0]['numRows']; $j++){
				//var to hold data of valid record
				$updateData = array();
				$whereArr = array();
				
				for($a=1;$a<=$dataXls->sheets[0]['numCols'];$a++){
					
					$xlsCurCol =  $this->structure_column($dataXls->sheets[0]['cells'][1][$a]);
					
					if(in_array($xlsCurCol,$validValues)){
						$val = isset($dataXls->sheets[0]['cells'][$j+1][$a]) ? $dataXls->sheets[0]['cells'][$j+1][$a] : '';
							
						if($val != '')
							$val = trim(str_replace("'","''",$val));
						
						if($xlsCurCol == 'id')
							$whereArr['id'] = $val;
						
						if($xlsCurCol == 'is_active')
							$updateData['is_active'] = $val;
							
					}else{
						return 3;
					}					
				} 
				if($doDelete == 1){
					$this->db->delete('contact_list',$whereArr);
				}else{
					$this->db->update('contact_list',$updateData,$whereArr);
				}
				
			}
			
			return 1;
		}
	
	}
	
	function do_batch_upload(){

		$udata = $this->upload->data();  
		$pdata = $this->input->post();
		
		$allowedTypes = array('application/msword','application/vnd.ms-excel');

        /*for reference only*/
		$validValues = array('firstname',
                            'middlename',
                            'lastname',
                            'telno',
                            'mobileno',
                            'officeno',
                            'credit_limit',
                            'ava_cred_limit',
                            'bill_cycle',
                            'double_cl');
		
		
		
		if(!in_array($udata['file_type'],$allowedTypes))
			return 2;
		else{
		
			//read the db xls
			$dataXls = new Spreadsheet_Excel_Reader();
			$dataXls->read($udata['full_path']); 
			
			$this->add_non_existing_column($dataXls);
			
			for ($j = 1; $j < $dataXls->sheets[0]['numRows']; $j++){
				//var to hold data of valid record
				$insertData = array();
				$insertData['lead_identity'] = $pdata['lead_identity'];
				for($a=1;$a<=$dataXls->sheets[0]['numCols'];$a++){
					
					$xlsCurCol =  $this->structure_column($dataXls->sheets[0]['cells'][1][$a]);
					
					#$this->check_column_alter($xlsCurCol);
					
					#if(in_array($xlsCurCol,$validValues)){
					$val = isset($dataXls->sheets[0]['cells'][$j+1][$a]) ? $dataXls->sheets[0]['cells'][$j+1][$a] : '';
						
					if($val != '')
						$val = trim(str_replace("'","''",$val));
					
					$insertData[$xlsCurCol] = $val;
						
					#} 
				}  
				$this->db->insert('contact_list',$insertData);
			}
			
			$this->db->insert('leads_details',array('lead_identity'=>$pdata['lead_identity'])); //insert lead_identity in the list
			
			return 1;
		}
	
	}
	
	public function add_non_existing_column($dataXls){ 
		for ($j = 1; $j <= 1; $j++){
					//var to hold data of valid record 
					for($a=1;$a<=$dataXls->sheets[0]['numCols'];$a++){ 
						$xlsCurCol =  $this->structure_column($dataXls->sheets[0]['cells'][1][$a]); 
						$this->check_column_alter($xlsCurCol);
					}
		}
	}
	
	public function get_card_details($id)
	{
		return $this->db->select('*')->from('card_details')->where('baserecid',$id)->get()->result_array();
	}
	
	public function get_card_details_by_ID($cardID)
	{ 
		return $this->db->select('*')->from('card_details')->where('id',$cardID)->get()->result_array();
	}
	
	public function get_supple($id)
	{
		return $this->db->select('*')->from('supplementary')->where('baserecid',$id)->get()->result_array();
	} 
	
	public function get_supple_details_by_ID($suppleID)
	{
		return $this->db->select('*')->from('supplementary')->where('id',$suppleID)->get()->result_array();
	}
	
	public function save_supple($id,$suppleID)
	{
		$pdata = $this->input->post();
		
		if($suppleID == 0) //insert
			$this->db->insert('supplementary',array_merge(array('baserecid'=>$id),$pdata));
		else //update
			$this->db->where('id',$suppleID)->update('supplementary',$pdata);
	}
	
	public function save_card($id,$cardID)
	{
		$pdata = $this->input->post();
		
		if($cardID == 0) //insert
			$this->db->insert('card_details',array_merge(array('baserecid'=>$id),$pdata));
		else //update
			$this->db->where('id',$cardID)->update('card_details',$pdata);
	} 
	
	public function get_allocated_leads($userid)
	{ 
		$retVal = array();
		$result = $this->db->select('lead_identity,
								count(case when calldate is null then 1 else null end ) as VIRGIN')
				->from('contact_list')
				->where('is_active',1)
				->where('assigned_agent',$userid)
				->group_by('lead_identity')
				->get()
				->result_array();
				
		foreach($result as $r)
			$this->retVal[$r['lead_identity']]['V'] = $r['VIRGIN'];
			
		$this->get_allocated_leads_touched($userid);
		
		
		return $this->retVal;
	}
	
	function get_allocated_leads_touched($userid){
		$retVal  = array();
		$query = "SELECT 
					lead_identity,callresult,count(*) as CTR
				FROM contact_list
				WHERE is_active = 1 
				AND calldate is not null
				AND assigned_agent = '{$userid}'
				GROUP BY lead_identity,callresult";
		
		$result =  $this->db->query($query)->result_array();
		
		foreach($result as $r)
			$this->retVal[$r['lead_identity']][$r['callresult']] = $r['CTR'];
	
		return $this->retVal;
	}
	 
	
	public function get_unallocated_leads()
	{
		$retVal =array();
		$sql = "Select 
					lead_identity,callresult,count(*) as CTR 
				from contact_list 
				where is_active = 1 
				and assigned_agent = '' 
				and calldate is not null
				group by callresult,lead_identity";
				
		$result =  $this->db->query($sql)->result_array();
		
		foreach($result as $r)
			$retVal[$r['lead_identity']][$r['callresult']] = $r['CTR'];
		
		
		return $retVal;
	}
	
	public function get_unallocated_virgin_leads()
	{
		$retVal =array();
		$sql = "Select lead_identity,count(*) as CTR 
				from contact_list 
				where calldate is null  
				and is_active = 1 
				and assigned_agent = '' 
				group by lead_identity";
		
		$result =  $this->db->query($sql)->result_array();
		 
		foreach($result as $r)
			$retVal[$r['lead_identity']] = $r['CTR'];
		
		
		return $retVal;	
	}
	
	public function allocate_leads(){
		$post = $this->input->post();
		$agentID = $this->session->userdata('agentID');
		
		//Allocation for removing leads
		if($post['allocType'] == 1){
			$sql = "UPDATE contact_list
					SET 
						assigned_agent = '',
						callresult = ''
					WHERE is_active = 1
					AND assigned_agent = '{$agentID}'";
			
			if($post['callresult'] == VIRGIN_CODE)//if de-allocation for virgin
				$sql .= " AND calldate is null ";
			else
				$sql .= " AND callresult = '{$post['callresult']}' ";
		}else{
			
			$sql = "UPDATE contact_list
				SET assigned_agent = '{$agentID}'
				WHERE is_active = 1 
				and assigned_agent = '' ";
			
			if($post['callresult'] == VIRGIN_CODE)//if allocation for virgin
				$sql .= " AND calldate is null ";
			else
				$sql .= " AND callresult = '{$post['callresult']}' ";
		}
		
		$sql .= " AND lead_identity = '{$post['li']}'
		          AND is_locked = 0
				  AND forcedpop = 0 
				 limit {$post['value']} ";

		$this->db->query($sql);
	}

    //get lead identity in users_lead_identity
    public function get_lead_identity_assigned($userID){
        $retVal = array();

        $result = $this->db->select('*')
                        ->from('users_lead_identity')
                        ->where('is_assign',1)
                        ->where('user_id',$userID)
                        ->get()
                        ->result_array();

        foreach($result as $data){
            $retVal[$data['lead_identity']] = $data['lead_identity'];
        }

        return $retVal;
    }

    public function lead_iden_activator(){
        $agentID = $this->session->userdata('agentID');
        $post = array_merge($this->input->post(),array('user_id'=>$agentID));
        $this->db->on_duplicate('users_lead_identity',$post);
    }
	
	public function check_lead_identity(){
		$post = $this->input->post();
		
		$result = $this->db->select('count(*) as ctr')
				->from('leads_details')
				->where('lead_identity',$post['li'])
				->get()
				->result_array();
		
		if($result[0]['ctr'] > 0)
			echo  1; //existing then alert user this is not allowed!
		else
			echo  0;
	}
	
	public function li_activator(){
		$post = $this->input->post();
		
		$this->db->update('leads_details',array('is_active'=>$post['is_active']),array('id'=>$post['id']));
	}
}