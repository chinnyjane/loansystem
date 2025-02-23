<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Testing extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  //$this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'final/template',
							"menu" => 'final/sidemenu',
							"module" => "Settings");
	function index(){
		$page = $this->page;
		
		if($_POST){
			$this->addaction();
		}
		
		//get list of rights actions
		$data = array("active"=>1);
		$page['actionrights'] = $this->Loansmodel->get_data_from("rightactions", $data);
		
		
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'settings/overview';
		$page['subcontent'] = 'testing/addaction';
		$this->load->view($page['template'], $page);
	}
	
	function addaction(){
		$this->input->post(NULL, true);
		$this->form_validation->set_rules("action", "Action Name", "required|xss_clean|unique[rightactions.actionName]");
		if($this->form_validation->run() != false){
			$data = array("actionName"=>$_POST['action'],
						"active"=>1,
						"dateAdded"=>$this->auth->localtime());
			$this->UserMgmt->insert_data_to('rightactions',$data);
		}
	}
	
	function branch(){
	//echo "akjsd";
		$this->load->view('testing/branchstatus');
	}
} ?>