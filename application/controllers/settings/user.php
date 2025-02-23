<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Settings",
							"submod" => "User",
							"active" => "Settings.User");
	function index(){
		if($_POST)
			$this->settings->action_user();
	
			$config['base_url'] = base_url()."settings/user/index";				
			$config['per_page'] = 8;
			$config['total_rows'] = $this->UserMgmt-> get_total_user();
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$page = $this->page;
			$segment = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$page['users'] = $this->UserMgmt->get_users(NULL, $segment);
		$page['header'] = $this->UserMgmt->getheader();
		//$page['main'] = 'settings/overview';
		$page['main'] = 'settings/user/list';
		$page["active"] = "Settings.User";
		$this->load->view($page['template'], $page);
	}
	
	function profile(){
		$page = $this->page;
		$page["subact"] = "Profile";
		if($_POST){
			
			if($_POST['Submit'] =="Update Password"){
				if($this->auth->update_password() == true)
				$page['poststatus'] = "Password was changed";
			}
			elseif($_POST['Submit'] == "Save User"){
				if($this->auth->updateProfile() == true)
				$page['poststatus'] = "Profile was updated";
			}elseif($_POST['Submit'] == "Update Rights"){
				if($this->auth->perms("User.Users",$this->auth->user_id(),3) == TRUE){
					$this->update_rights();
					}else $page['poststatus'] = "You are not allowed to update user's rights.";
			}
		}
		
		$page['userid'] = $this->uri->segment(3);
		$page['header'] = $this->UserMgmt->getheader();
		
		$page['sub'] = $this->uri->segment(2);
		if($page['sub'] == 'overview')
		$page['main'] = 'settings/user/profile';
		elseif($page['sub'] == 'rights')
		$page['main'] = 'settings/user/rigths';
		elseif($page['sub'] == 'branch')
		$page['main'] = 'settings/user/branch';		
		
		//$page['main'] = 'settings/overview';		
		$this->load->view($page['template'], $page);
	}
	
	function branch(){		
		$page = $this->page;
		$page["subact"] = "Profile.Branch";
		$page['userid'] = $this->uri->segment(3);
		$link = 'profile/branch/'.$page['userid'];
		if($_POST){
			if($this->auth->updatebranchrights() == true){			
					$action="approve";					
					//$this->auth->notify($foruser, $moduleid, $notification, $link, $action);
			 $page['error'] = '<div class="alert alert-info">Rights need to be approved</div>';
			 }else
				$page['error'] = '<div class="alert alert-danger">Error</div>';
		}		
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'settings/user/userbranchrights';
		//$page['main'] = 'settings/overview';
		$this->load->view($page['template'], $page);
	}
	
	function rights(){		
		$page = $this->page;
		$page["subact"] = "Profile.Rights";
		$page['userid'] = $this->uri->segment(3);
		if($_POST){
			if($_POST['Submit'] == "Update Rights"){
					//if($this->auth->perms("Profile.Rights",$this->auth->user_id(),3) == TRUE){
						$this->update_rights();
					//}else $page['poststatus'] = "You are not allowed to update user's rights.";
			}
		}
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'settings/user/user_rights';
		//$page['main'] = 'settings/overview';
		$this->load->view($page['template'], $page);
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
	
	function create(){
	
		$page = $this->page;
		if($_POST){
			$page['poststatus'] = $this->settings->action_user();
		}
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'settings/user/create-user';
		//$page['main'] = 'settings/overview';
		$this->load->view($page['template'], $page);
		
	}
	
	function checkpassword(){
		$validpass = $this->UserMgmt->validate_password($this->input->post('oldpassword'),$this->input->post('userid'));
		if( $validpass == true){			
			return true;
		}else{
			$this->form_validation->set_message('checkpassword', 'Old Password is incorrect.');
		return false;
		}
	}
	
	function duplicateuser(){
		$data = array("username"=>$this->input->post('username'),
							"id <> " => $this->input->post('userid'));
		if($this->Loansmodel->get_data_from("user", $data)->num_rows() > 0){
			$this->form_validation->set_message('duplicateuser', "Username is not available. Try other unique username.");
			return false;
		}else{		
			return true;
		}
	}
}
?>