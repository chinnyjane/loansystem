<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

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
	 public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'template/usermgmtnav',
							"template" => 'template/new/body',
							"menu" => 'template/setupmenu',
							"module"=> 'Header.My Account');
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');
	  $this->auth->restrict();
	
	}
	public function index()
	{
		if($this->auth->is_loggedin()==TRUE){
			$page = $this->page;
			if($_POST){
			$page['poststatus'] = $this->action_user();
			}
			$page['nav'] = "template/nonav";
			$page['main']="user/myaccount";
			$this->load->view($page['template'], $page);
		}else
		redirect(base_url());
	}
	
	public function action_user(){
		$action = $this->input->post('Submit');
		if($action =='Update Account'){
			$this->input->post(NULL, TRUE);
			$this->form_validation->set_rules("firstname", "First Name", "trim|required|xss_clean");
			$this->form_validation->set_rules("lastname", "Last Name", "trim|required|xss_clean");
			$this->form_validation->set_rules("email", "Email", "required|valid_email");
			$this->form_validation->set_rules("contact", "Contact Number", "required|trim|xss_clean");
			if($this->form_validation->run() == FALSE){
				//echo "error updating";
				$poststatus = "Error was encountered.";
			}else{
				//echo "Updating...";
				if($this->UserMgmt->update_user($_POST))
				$poststatus= "User details saved.";
				else $poststatus = "User was not added.";
			}
		}elseif($action =="Update Password"){
			//echo "Changing Password...";
					$this->input->post(NULL, TRUE);
					$this->form_validation->set_rules("oldpassword", "Old Password", "required|callback_check_password");
					$this->form_validation->set_rules("newpassword", "New Password", "required|matches[confirmpassword]");
					$this->form_validation->set_rules("confirmpassword", "Confirm Password", "required");
					if($this->form_validation->run() == FALSE){
						$poststatus = validation_errors();
					}else{
						$this->UserMgmt->update_password($_POST);
						$poststatus = "Password was updated.";
					}
		}
		return $poststatus;
	}
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */