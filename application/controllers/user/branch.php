<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branch extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public $page = array ( "pagetitle" => "User Management - Fruits Consulting Inc",
							"nav" => 'template/usermgmtnav',
							"module" => "Branches",
							"template" => 'template/green',
							"menu" => 'template/setupmenu'); 
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public function index()
	{
		$page = $this->page;
		if($this->auth->perms("User.Branches",$this->auth->user_id(),2) == TRUE){
			if($_POST){
				$this->input->post(NULL, true);
				$this->form_validation->set_rules('branch', "Branch","trim|required|xss_clean");
				$this->form_validation->set_rules('address', "Address", "trim|required|xss_clean");
			}else{
			}
			
			$page['main']="user/branch";
		}else{
			$page['main']="user/noperm";
		}		
		$this->load->view($page['template'], $page);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */