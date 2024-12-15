<?php$path = '/var/www/html/template/application/third_party/php/pear';set_include_path(get_include_path() . PATH_SEPARATOR . $path);$_SERVER['DOCUMENT_ROOT'] .= '/template';include_once($_SERVER['DOCUMENT_ROOT']. '/application/third_party/Spreadsheet/Excel/Writer.php');include_once($_SERVER['DOCUMENT_ROOT']. '/application/third_party/Spreadsheet/Excel/Reader/reader.php');Class Export extends Auth_Controller  {    var $data = array();    //use `Auth_Controller` if you want the page to be validated if the user is logged in or not, if you want to disable this then use `CI_Controller` instead    public  function __construct(){        parent::__construct();        $this->load->helper('html');        $this->load->model('export_model');        $this->load->helper('url');        $this->load->library('session');        $this->load->helper(array('form', 'url'));        $this->has_permission(188);        $this->data = $this->get_privs();    }    public function index(){        $this->set_header_data(PROJECT_NAME.':Export','Export');        $dataIndex['cols'] = $this->export_model->get_column('contact_list');        $dataIndex['users'] = $this->get_users_grp_by_id();        $dataIndex['leadIdentity'] = $this->get_leads_details(); //get available lead identity        $this->load->view('export/index',$dataIndex);        $this->load->view('templates/footer');    }    public function do_export(){        $data['filename'] = CONTACT_LIST_XLS;        $data['data'] = $this->export_model->do_export();        $data['dataSupple'] = $this->export_model->get_no_of_supple();        $data['dataCtrTouch'] = $this->export_model->get_no_of_touched();        $data['inSubCR'] = explode(',',CR_WITH_SUB_CR);        $data['cr'] = $this->getLookup('callresult',1);        $data['subCr'] = ($this->getLookup('NI',1)+$this->getLookup('CB',1));        $data['ag_type'] = $this->getLookup('AG_TYPE',1);        $this->load->view('export/export',$data);    }}?>