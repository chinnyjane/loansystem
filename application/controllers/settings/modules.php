<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules extends CI_Controller {

	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();
	}	
	
	public $page = array ( "pagetitle" => "Control Panel - Modules - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Settings",
							"submod" => "Modules",
							"active" => "Settings.User.Modules");
	
							
	
	public function index()
	{
		$page = $this->page;
		//if($this->auth->perms("User.Modules",$this->auth->user_id(),2) == TRUE){
			if($_POST)
			$page['status'] = $this->action_module();
		
		$data = array();
		$page['modules'] = $this->Module->get($data);
		$page['main']="settings/user/modules";
		//}else{
			//$page['main'] = "user/noperm";
		//}
		$page['header'] = $this->UserMgmt->getheader();
		//$page['main'] = 'settings/overview';		
		$this->load->view($page['template'], $page);
	}
	
	public function action_module(){
		$action = $this->input->post("submit");
		if($this->auth->perms("Settings.Modules",$this->auth->user_id(),3) == TRUE){
			if($action == 'Add Module'){
				$this->input->post(NULL, TRUE);
				$this->form_validation->set_rules('module', 'Module Name', 'required|xss_clean|is_unique[modules.module_name]');
				$this->form_validation->set_rules('description', 'Description', 'required|xss_clean');
				$this->form_validation->set_rules('link', 'Module Link', 'trim|required|xss_clean');
				if($this->form_validation->run() == FALSE){
					$status = validation_errors();
				}else{
					if($this->UserMgmt->add_module())
						$status = "New Module was added";
					else
						$status = "New Module failed to be added.";
				}
			}elseif($action=="Deactivate"){
				$module = $this->input->post("module");
				if(count($module) >0){
					foreach($module as $mod){
						$this->UserMgmt->delete_module($mod);						
					}
					$status = "Module(s) were updated";
				}
			}elseif($action=="Activate"){
				$module = $this->input->post("module");
				if(count($module) >0){
					foreach($module as $mod){
						$this->UserMgmt->activate_module($mod);
					}
					$status = "Module(s) were updated";
				}
			}
			
		}else{
			$status = "You have no permission to update module.";
		}
		
		return $status;
	}
	
	public function manage(){
		$page = $this->page;
		$page['moduleid'] = $this->uri->segment(5);
		if($_POST){
			if($_POST['submit'] == "Update Module"){
				if($this->auth->updatemodule($page['moduleid']) == true)
					$page['success'] = "Module was updated.";
				else
					$page['error'] = "Module was not updated.";
			}
		}
		
		$data = array("id"=>$page['moduleid']);
		$page['modules'] = $this->Loansmodel->get_data_from('modules', $data);
		$page['main']="settings/user/manage_modules";
		$page['header'] = $this->UserMgmt->getheader();
		//$page['main'] = 'settings/overview';		
		$this->load->view($page['template'], $page);
	}
	
	function checkmodule(){
		$where = array("id !=" => $_POST['moduleid'],
								"module_name" => $_POST['module']);
		$table = "modules";
		if($this->Loansmodel->get_data_from($table, $where)->num_rows() > 0)
		{
			$this->form_validation->set_message("checkmodule", "Module name already exists.");
			return false;
		}else
			return true;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */