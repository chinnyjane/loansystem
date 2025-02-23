<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overview extends CI_Controller {

	public $page = array("pagetitle" => "Fruits Consulting Inc",
							"template" => 'template/new/body',
							"menu" => 'template/reportmenu',
							"module"=>'Reports');
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
		$page['main']="";
		$this->load->view($page['template'], $page);		
	}	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */