<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public $page = array ( "pagetitle" => "User Management - Fruits Consulting Inc",
							"nav" => 'template/usermgmtnav',
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
		
		if($this->auth->perms("User.Users",$this->auth->user_id(),2) == TRUE){			
			if($_POST)
			$this->action_user();
			$config['base_url'] = base_url()."user/user/index";		
			$config['per_page'] = 5;
			$config['total_rows'] = $this->UserMgmt-> get_total_user();
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$segment = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$page['users'] = $this->UserMgmt->get_users($config['per_page'], $segment);
			$page['main']="user/user";
		}else {			
			$page['nav'] = "template/nonav";
			$page['main']="user/noperm";
		}		
		//$this->load->view('template/temp', $page);
		$this->load->view($page['template'], $page);
	}
	
	function test(){
		$this->load->library('pagination');
		$config['base_url'] = base_url()."user/user/test";	
		$config['total_rows'] = 200;
		$config['per_page'] = 20; 
		$config['first_link'] = 'First';
		$this->pagination->initialize($config); 

		echo $this->pagination->create_links();
				
		//$this->load->view('user/test',$page);
	}
	public function edit()
	{
		
		$page = $this->page;
		if($_POST){
			if($_POST['Submit'] =="Update Password"){
				if($this->auth->perms("User.Users",$this->auth->user_id(),3) == TRUE){
					//echo "Changing Password...";
					
					
				}else $page['poststatus'] = "You are not allowed to update user's password.";
			}
			elseif($_POST['Submit'] == "Save User"){
				if($this->auth->perms("User.Users",$this->auth->user_id(),3) == TRUE){
					//echo "update here";
					$this->input->post(NULL, TRUE);
					$this->form_validation->set_rules("firstname", "First Name", "trim|required|xss_clean");
					$this->form_validation->set_rules("lastname", "Last Name", "trim|required|xss_clean");
					$this->form_validation->set_rules("email", "Email", "required|valid_email");
					$this->form_validation->set_rules("contact", "Contact Number", "required|trim|xss_clean");
					if($this->form_validation->run() == FALSE){
						//echo "error updating";
						$page['poststatus'] = "Error was encountered.";
					}else{
						//echo "Updating...";
						if($this->UserMgmt->update_user($_POST))
						$page['poststatus'] = "User details saved.";
						else $page['poststatus'] = "User was not added.";
					}
				}else
					 $page['poststatus'] = "You are not allowed to update this user.";
			}elseif($_POST['Submit'] == "Update Rights"){
				if($this->auth->perms("User.Users",$this->auth->user_id(),3) == TRUE){
					$this->update_rights();
					}else $page['poststatus'] = "You are not allowed to update user's rights.";
			}
		}
		
		$page['userid'] = $this->uri->segment(4);
		$page['main']="user/user_profile";
		$this->load->view($page['template'], $page);
	}
	
	function rights(){
		$page = $this->page;
		if($_POST){
		if($_POST['Submit'] == "Update Rights"){
				if($this->auth->perms("Settings.User.Profile.Rights",$this->auth->user_id(),3) == TRUE){
					$this->update_rights();
				}else $page['poststatus'] = "You are not allowed to update user's rights.";
			}
		}
		$page['userid'] = $this->uri->segment(4);
		$page['main']="user/user_rights";
		$this->load->view($page['template'], $page);
	}
	function branch(){
		$page = $this->page;
		if($_POST){
			
		}
		$page['userid'] = $this->uri->segment(4);
		$page['main']="user/userbranchrights";
		$this->load->view($page['template'], $page);
	}
	
	function check_password(){
		$validpass = $this->UserMgmt->validate_password($this->input->post('oldpassword'),$this->input->post('userid'));
		if( $validpass == true){			
			return true;
		}else{
			$this->form_validation->set_message('check_password', 'Old Password is incorrect.');
		return false;
		}
	}
	
	function update_rights(){
		$userid = $this->input->post('userid');
		foreach($_POST['rights'] as $module => $value)
		{
			if($this->UserMgmt->check_rights($module,$userid) == true){
				//update rights
				//echo "update";
				foreach ($value as $ri => $perm){
					$active = array("active"=>$perm,
								"date_modified"=>date("Y-m-d h:i:s"),
								"last_modified_by"=>1);
					$where = array("user_id"=>$userid,
								"module_id"=>$module,
								"module_right"=>$ri);
					$this->UserMgmt->right_update($where, $active);
				}
				
			}else{
				//add_rights
				foreach ($value as $ri => $perm){
					$data = array("user_id"=>$userid,
								"module_id"=>$module,
								"module_right"=>$ri,
								"active"=>$perm,
								"date_created"=> date("Y-m-d h:i:s"),
								"created_by"=> 1,
								"user_ip"=>$this->input->ip_address());
					$this->UserMgmt->add_rights($data,"user");
					
				}
			}
		}			
			
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
					$page = "Error was encountered.";
				}else{
					$userid = $this->UserMgmt->add_user($_POST);
					if($userid)
					$page = "New User was added.";
					else $page = "User was not added.";
					
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
	
	function hashpassword(){
		$primarysalt = md5($email);
		$salt = substr($primarysalt, 0, 6);
		$hashpassword = hash("sha256", $password.$salt);
	}
	
	function validatepass($pass,$salt){
		
	}
}
	
	


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */