<?php
class Security_model extends CI_Model {
	public function __construct()
	{
		$this->load->database(); 		
	}
	
	function login(){

		$pdata = $this->input->post();
		
		$query = $this->db->select('*')
				->from('users')
				->where('user_name',$pdata['user_name'])
				->where("user_password","md5('{$pdata['user_password']}')",false)
				->where('is_active',1)
				->get();
		
		
		if($query->num_rows() > 0){
			//set user privs
			$this->set_user_privileges($pdata['user_name']);
			
			$newdata = array(
						'username'  => $pdata['user_name'], 
						'logged_in' => TRUE
					);
			$this->session->set_userdata($newdata);
			
			$result = $query->result_array();
			
			$this->session->set_userdata(array('full_name'=>$result[0]['firstname'] . ' ' . $result[0]['lastname'],
												'user_mobile'=>$result[0]['mobile_no'],
                                                'user_type'=>$result[0]['user_type'],
                                                'fcp'=>$result[0]['force_pw'], //2018-10-16 fcp
                                                'last_cp'=>$result[0]['last_cp'], //2018-10-16 fcp
                                        ));
			
			return 1;
		}else
			return 0;
	}
	
	function set_user_privileges($username)
	{
		$result = $this->db->select('*')
					->from('privilege')
					->where('user_id',$username)
					->where('is_active',1)
					->get()
					->result_array();
		
		$privs = array();	
		foreach($result as $r)
			$privs[$r['right_id']]= 1;
		
		$this->session->set_userdata(array('privs'=>$privs));
	}
	
	//$process = 1 timein ; 0 timeout
	public function do_time_proc($process){
		if(!$this->session->userdata('logged_in'))
			redirect('security/login');
		else{
			$this->load->database();
			$username = $this->session->userdata('username');
			$this->db->where("user_name",$username)->update('users',array('log_status'=>$process));
			
			
			$insertData = array('user_id'=>$username,
								'log_status'=>$process,
								'ip_address'=>$this->session->userdata('ip_address')
								);
								
			//log every time process
			$this->db->insert('login_trans_log',$insertData);
		}
	}

	function clock_details(){
		$username = $this->session->userdata('username');
		
		$result = $this->db->select('*')->from('login_trans_log')
				->where('user_id',$username)
				->order_by('time_stamp')
				->get()
				->result_array();
		
		return $result;
	}
	
	public function cp_save()
	{
		$username = $this->session->userdata('username');
		$pdata = $this->input->post();
		$oldPw = md5(trim($pdata['old_password']));
		$newPw = (trim($pdata['password']));
		$repPw = (trim($pdata['rep_password'])); //repeat pw
		
		$result = $this->db->select('user_password')->from('users')
				->where('user_name',$username)
				->get()->result_array();
			
		
		if($result[0]['user_password'] != $oldPw)
			return 3;
		elseif($newPw != $repPw)
			return 4;
		elseif(strlen($newPw) <5)
			return 5;
		elseif(!password_strength_check($newPw)){
			//2018-10-16 fcp
			return 'pw_str';
		}
		else{
			//2018-10-16 fcp reset force_pw to 0 everytime CP is do
			$this->db->where('user_name',$username)->update('users',array(	'user_password'=>md5($newPw),
																			'force_pw'=>0,
																			'last_cp'=>date('Y-m-d H:i:s')));
			$this->session->set_userdata(array('fcp'=>0));
			return 1;
		}
	}
}
?>
