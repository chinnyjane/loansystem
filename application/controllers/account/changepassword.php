<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Changepassword extends CI_Controller {

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
	public $page = array ( "pagetitle" => "Change Password - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "My Account",
							"submod"=> "Change Password");
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');
	  $this->auth->restrict();
	
	}
	public function index()
	{		
		$page = $this->page;
		if($_POST)
		$page['status'] = $this->action_user();
		$page['header'] = $this->UserMgmt->getheader();
		$page['main']="user/password";
		//$page['main']="settings/overview";
		$this->load->view($page['template'], $page);
	}
	
	public function action_user(){
		$action = $this->input->post('Submit');
		if($action =='Update Account'){
			$this->input->post(NULL, TRUE);
			$this->form_validation->set_rules("firstname", "First Name", "trim|required|xss_clean");
			$this->form_validation->set_rules("lastname", "Last Name", "trim|required|xss_clean");
			$this->form_validation->set_rules("email", "Email", "required|valid_email");
			$this->form_validation->set_rules("contact", "Contact Number", "is_numeric|required|trim|xss_clean");
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
			if($this->form_validation->run() == FALSE){
				//echo "error updating";
				return $poststatus = "Error was encountered.";
			}else{
				//echo "Updating...";
				if($this->UserMgmt->update_user($_POST))
				$poststatus= "User details saved.";
				else $poststatus = "User was not added.";
				return $poststatus;
			}
		}elseif($action =="Change Password"){
			//echo "Changing Password...";
					$this->input->post(NULL, TRUE);
					$this->form_validation->set_rules("oldpassword", "Old Password", "required|callback_check_password");
					$this->form_validation->set_rules("newpassword", "New Password", "required|matches[confirmpassword]|min_length[6]");
					$this->form_validation->set_rules("confirmpassword", "Confirm Password", "required");
					$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
					if($this->form_validation->run() == FALSE){
						$poststatus = validation_errors();
					}else{
						$this->UserMgmt->update_password($_POST);
						return $poststatus = "Password was updated.";
					}
		}
		//return $poststatus;
	}
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */