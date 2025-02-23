<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'final/template',
							"menu" => 'final/sidemenu',
							"module" => "Settings",
							"submod" => "Settings.User");
	function index(){
		$page = $this->page;
		$config['full_tag_open'] = '<ul class="pagination">';
			$config['full_tag_close'] = '</ul>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['next_link'] = 'Next &rarr;';
			$config['next_tag_open'] = '<li class="next">';
			$config['next_tag_close'] = '</li>';
			$config['prev_link'] = '&larr; Previous';
			$config['prev_tag_open'] = '<li class="previous">';
			$config['prev_tag_close'] = '</li>';
			$config['base_url'] = base_url()."settings/user/index";				
			$config['per_page'] = 10;
			$config['total_rows'] = $this->UserMgmt-> get_total_user();
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$segment = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$page['users'] = $this->UserMgmt->get_users($config['per_page'], $segment);
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'settings/overview';
		$page['subcontent'] = 'settings/user/list';
		$this->load->view($page['template'], $page);
	}
	
	/*function profile(){
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
		
		$page['userid'] = $this->uri->segment(4);
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'settings/overview';
		$page['subcontent'] = 'settings/user/profile';
		$this->load->view($page['template'], $page);
	}*/
}
?>