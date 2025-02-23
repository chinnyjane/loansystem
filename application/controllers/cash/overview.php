<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overview extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Cash");
	function index(){
		$page = $this->page;
		//$this->cash->transactions($this->auth->localdate());
		
		$module = "CMC ALL Branches";
		$module2 = "CMC By Branch";
		if($this->auth->perms($module,$this->auth->user_id(),2) == true){
			$page['subcontent'] = "cash/consolidated";
		}
		else if($this->auth->perms($module2, $this->auth->user_id(), 2) == true ){
			redirect(base_url()."cash/daily");
		}
		
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'cash/main';
		$this->load->view($page['template'], $page);
	}
	
	
	
}
?>