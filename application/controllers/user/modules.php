<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules extends CI_Controller {

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
							"template" => 'template/green',
							"menu" => 'template/sidemenu'); 
	function __construct()
	{
	  parent::__construct();
	  //$this->auth->restrict();
	 
	}
	public function index()
	{
		$page = $this->page;
		//if($this->auth->perms("User.Modules",$this->auth->user_id(),2) == TRUE){
			if($_POST)
			$this->action_module();
			$page['main']="user/modules";
		//}else{
			//$page['main'] = "user/noperm";
		//}
			$this->load->view($page['template'], $page);		
	}
	
	public function action_module(){
		$action = $this->input->post("submit");
		//if($this->auth->perms("User.Modules",$this->auth->user_id(),3) == TRUE){
			if($action == 'Add Module'){
				$this->input->post(NULL, TRUE);
				$this->form_validation->set_rules('module', 'Module Name', 'trim|required|xss_clean|is_unique[modules.module_name]');
				$this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');
				$this->form_validation->set_rules('link', 'Module Link', 'trim|required|xss_clean');
				if($this->form_validation->run() == FALSE){
					
				}else{
					if($this->UserMgmt->add_module())
					$page['status'] = "New Module was added";
				}
			}elseif($action=="Deactivate"){
				$module = $this->input->post("module");
				if(count($module) >0){
					foreach($module as $mod){
						$this->UserMgmt->delete_module($mod);
					}
				}
			}
		//}else{
			//$page['status'] = "You have no permission to update module.";
		//}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */