<?php
Class Reports extends Auth_Controller{
	var $data = array(); 
	
	function __construct(){
		parent::__construct();
		$this->load->model('reports_model');
		$this->load->helper('url');
		$this->load->library('session');    
		$this->data = $this->get_privs();
	}
	
	public function index(){
		$this->set_header_data(PROJECT_NAME.':Reports','Reports');
		
		$this->load->view('reports/index');
		$this->load->view('templates/footer');
	}
	
	public function generate_report(){
		
		 
		
	}
	
	public function generate_xls_report(){
		$this->reports_model->generate_report();
		$data = $this->input->get();
		$data['ownership'] = $this->getLookup('ownership');
		$data['car_ownership'] = $this->getLookup('car_ownership');
		$data['education_attainment'] = $this->getLookup('education_attainment');
		$data['yesno'] = $this->getLookup('yesno');
		$data['yesnoLU'] = $this->getLookup('yesno',1);
		$data['billingaddress'] = $this->getLookup('billingaddress');
		$data['employment'] = $this->getLookup('employment');
		$data['relationship'] = $this->getLookup('relationship');
		
		
		$data['details'] = $this->reports_model->agDetails;
		$data['cards'] = $this->reports_model->cardDetails;
		$data['supple'] = $this->reports_model->suppleDetails;
		$this->load->view('reports/generated_report',$data);
	}
}
?>
