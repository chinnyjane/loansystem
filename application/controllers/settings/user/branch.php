<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branch extends CI_Controller {
	function __contruct(){
		parent::__construct();
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'final/template',
							"menu" => 'final/sidemenu',
							"module" => "Settings");
	function index(){
		$page = $this->page;
		if($_POST){
			
		}
		$page['userid'] = $this->uri->segment(4);
		$page['main']="user/userbranchrights";
		$this->load->view($page['template'], $page);
	}
	function branch(){
		$page = $this->page;
		if($_POST){
			if($_POST['Submit'] =="Update Password"){
				if($this->auth->perms("User.Users",$this->auth->user_id(),3) == TRUE){
					//echo "Changing Password...";
					$this->input->post(NULL, TRUE);
					$this->form_validation->set_rules("oldpassword", "Old Password", "required|callback_check_password");
					$this->form_validation->set_rules("newpassword", "New Password", "required|matches[confirmpassword]");
					$this->form_validation->set_rules("confirmpassword", "Confirm Password", "required");
					if($this->form_validation->run() == FALSE){
						
					}else{
						$this->UserMgmt->update_password($_POST);
						$page['poststatus'] = "Password was updated.";
					}
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
		
		$page['userid'] = $this->uri->segment(2);
		echo $page['userid'];
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'settings/overview';
		$page['subcontent'] = 'settings/user/profile';
		$this->load->view($page['template'], $page);
		
	}
}
?>