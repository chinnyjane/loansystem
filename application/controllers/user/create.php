<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create extends CI_Controller {

public $page = array ( "pagetitle" => "User Management - Fruits Consulting Inc",
							"nav" => 'template/usermgmtnav',
							"template" => 'template/green',
							"menu" => 'template/sidemenu'); 
	public function index(){
			$page = $this->page;
			if($this->auth->perms("User.Users",$this->auth->user_id(),2) == TRUE){			
				if($_POST){
				$this->action_user();
				}
				$page['main'] = "user/forms/create-user";
			}
			$this->load->view($page['template'], $page);
		}
	function action_user(){
		$action = $_POST['submit'];
		if($action == "Delete"){
				//DELETE USER from USER TABLE
				$users = $this->input->post('user');
				if(count($users) > 0){
					foreach ($users as $u){
						$data = array('deleted'=>1,
									'active'=>0);
						 $this->UserMgmt->user_status($data,$u);						
					}
				}
		}elseif($action == "Activate"){
			//Activate USER from USER TABLE
			$users = $this->input->post('user');
				if(count($users) > 0){
					foreach ($users as $u){
						$data = array('deleted'=>0,
									'active'=>1);
						 $this->UserMgmt->user_status($data,$u);						
					}
				}
			
		}elseif($action == "Deactivate"){
			//deactivate USER from USER TABLE
			$users = $this->input->post('user');
				if(count($users) > 0){
					foreach ($users as $u){
						$data = array('deleted'=>0,
									'active'=>0);
						 $this->UserMgmt->user_status($data,$u);						
					}
				}
		
		}elseif($action == "Permanent Delete"){
			//Permanently Delete User from DB
			$users = $this->input->post('user');
				if(count($users) > 0){
					foreach ($users as $u){
						 $this->UserMgmt->delete_user($u);						
					}
				}
		}elseif($action == "Add User"){
			//ADD USER
			if($_POST){
				$this->input->post(NULL, TRUE);
				$this->form_validation->set_rules("firstname", "First Name", "trim|required|xss_clean");
				$this->form_validation->set_rules("lastname", "Last Name", "trim|required|xss_clean");
				$this->form_validation->set_rules("email", "Email", "required|valid_email|is_unique[user.email]");
				$this->form_validation->set_rules("contact", "Contact Number", "required|trim|xss_clean");
				$this->form_validation->set_rules("password", "Password", "required|matches[confirmpassword]");
				$this->form_validation->set_rules("confirmpassword", "Confirm Password", "required");
				if($this->form_validation->run() == FALSE){
					$page['poststatus'] = "Error was encountered.";
				}else{
					$userid = $this->UserMgmt->add_user($_POST);
					if($userid)
					$page['poststatus'] = "New User was added.";
					else $page['poststatus'] = "User was not added.";
					
					//ADD Rights based on role
					$module = $this->UserMgmt->get_module();
					if($module->num_rows() > 0){
						foreach ($module->result() as $mod){
							$rights = $this->UserMgmt->get_group_rights($_POST['group'],$mod->id);
							if($rights->num_rows() > 0){
								foreach($rights->result() as $r){
									$data = array("user_id"=>$userid,
														"module_id"=>$r->module_id,
														"module_right"=>$r->module_right,
														"group_id"=>$r->group_id,
														"active"=>$r->active,
														"date_created"=> date("Y-m-d h:i:s"),
														"created_by"=> 1,
														"user_ip"=>$this->input->ip_address());
									$this->UserMgmt->add_rights($data,"user");
								}
							}
						}
					}
				
				}
			}		
		}
	}
	
} ?>