<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fee extends CI_Controller {
	
	public $page = array ( "pagetitle" => "Product ",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Header.Product",
							"submod"=>"Interest Rates Table",
							"active" => "Products.Interest Rates Table"
							);
							
	public $debug = false; // turn to false if live
	
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	   $this->auth->restrict();
	}
	
	public function index()
	{			
		$page = $this->page;		
		$page['header'] = $this->UserMgmt->getheader();
		$page['products'] = $this->Loansmodel->get_productcodes();
		$page['main'] = 'settings/fees';
		$this->load->view($page['template'], $page);
	}
	
	function add(){
		header("content-type:application/json");
		if($_POST){
			$this->form_validation->set_rules("var_name","Variable Name", "required|alpha|xss_clean");
			$this->form_validation->set_rules("feename","Fee Name", "required|xss_clean");
			if($this->form_validation->run() === false){
				$msg['stat'] = false;
				$msg['msg'] = validation_errors();
			}else{
				if($this->Fees->add()){
					$msg['stat']= true;
				}else{
					$msg['stat'] = false;
				}
			}
		}else{
			$msg['stat'] = false;
			$msg['msg'] = "NO Posts here";
		}
		echo json_encode($msg);
	}
	
	function details(){
		$page = $this->page;
		$feeID = $this->uri->segment(4);
		
		$where = array("fees.id"=>$feeID);
		$fees = $this->Fees->getFee($where);
		
		
	}
}
?>