<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends CI_Controller {

	
	public $page = array ( "pagetitle" => "",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Settings",
							"submod" => "Roles",
							"active" => "Settings.Roles");
							
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	
	
	public function index()
	{
		$page = $this->page;
		if($this->auth->perms("Settings.Roles",$this->auth->user_id(),2) == TRUE){
			if($_POST){
				$this->input->post(NULL, TRUE);
				$this->form_validation->set_rules("role", "Roles", "trim|required|xss_clean");
				$this->form_validation->set_rules("description", "Description", "trim|required|xss_clean");
				if($this->form_validation->run() == FALSE){
					echo "error";
				}else{
					echo "add role na";
					$role = $this->UserMgmt->add_role($_POST);
					if($role == true)
					echo  "New Role was added";
					else
					echo "wala nagsulod";
				}
			}			
			$page['main'] = 'settings/user/roles';	
			$page['pagetitle'] =  "Roles - Fruits Consulting Inc";
		}else{
			$page['main']="user/noperm";
		}
		
		$page['header'] = $this->UserMgmt->getheader();
		$page['pagetitle'] =  "Roles - Fruits Consulting Inc";
		//$page['main'] = 'settings/overview';		
		$this->load->view($page['template'], $page);
	}
	
	public function edit(){
		$page = $this->page;
		$page['groupid'] = $this->uri->segment(5);
		if ($this->auth->perms("Settings.Roles",$this->auth->user_id(),3) == true){
		if($_POST){
			if($_POST['Submit']=="Edit Role"){
				$this->input->post(NULL, TRUE);
				$this->form_validation->set_rules("role", "Roles", "trim|required|xss_clean");
				$this->form_validation->set_rules("description", "Description", "trim|required|xss_clean");
				if($this->form_validation->run() == FALSE){
					echo "error";
				}else{
					//echo "add role na";
					$data = array("name" => $_POST['role'],
								"description" => $_POST['description'],							
								"date_modified" => date("Y-m-d h:i:s"),
								"last_modified_by" => 0,
								"user_ip" => $this->input->ip_address(),
								"active" => 1);
					$role = $this->UserMgmt->edit_role($data, $page['groupid']);
					if($role == true)
					$page['success']= "Role was updated";
					else
					$page['error']= "Role was not updated.";
				}
			}elseif($_POST['Submit'] == "Update Rights"){
				$this->update_rights();
			}
		}		
		
		$page['main']="settings/user/roles_details";		
		}else{
			$page['main']="user/noperm";
		}
		$page['header'] = $this->UserMgmt->getheader();
		//$page['main'] = 'settings/overview';		
		$this->load->view($page['template'], $page);
	}
	
	function update_rights(){
		$groupid = $this->input->post('groupid');
		foreach($_POST['rights'] as $module => $value)
		{
			if($this->UserMgmt->get_group_rights($groupid,$module)->num_rows() > 0){
				//update rights
				//echo "update";
				foreach ($value as $ri => $perm){
					$active = array("active"=>$perm,
								"date_modified"=>date("Y-m-d h:i:s"),
								"last_modified_by"=>1);
					$where = array("group_id"=>$groupid,
								"module_id"=>$module,
								"module_right"=>$ri);
					$this->UserMgmt->groupright_update($where, $active);
				}
				
			}else{
				//add_rights
				foreach ($value as $ri => $perm){
					$data = array("group_id"=>$groupid,
								"module_id"=>$module,
								"module_right"=>$ri,
								"active"=>$perm,
								"date_created"=> date("Y-m-d h:i:s"),
								"created_by"=> 1,
								"user_ip"=>$this->input->ip_address());
					$this->UserMgmt->add_rights($data,"group");
					
				}
			}
		}			
			
	}
}
?>