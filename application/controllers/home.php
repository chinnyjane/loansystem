<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

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
	 public $page = array ( "pagetitle" => "Login",
							"nav" => 'final/navheader',
							"template" => 'template/login');
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');
	
	}
	
	public function index()
	{
		$page = $this->page;
		//redirect("http://www.ycfcsystems.com");
		//exit();
		$module = "CMC ALL Branches";
		$module2 = "CMC By Branch";
		//if($this->auth->perms($module,$this->auth->user_id(),2) == true){
		$redirect = base_url()."dashboard";
		//}
		//else if($this->auth->perms($module2, $this->auth->user_id(), 2) == true ){
			//$redirect = base_url()."cash";
		//}else{
		
	
		if($this->auth->is_loggedin() == TRUE){
		
			redirect($redirect);
		}
		
		if($_POST){
			if($this->auth->login($this->input->post('username'), $this->input->post('password')) === TRUE)
			redirect($redirect);
			else
			$page['loginerror'] = "Invalid login information.";
		
		}else
		
		$page['template']="template/login";
		$this->load->view($page['template'], $page);
		
	}
	
	public function old_index()
	{
		if($this->auth->is_loggedin() == TRUE)
		redirect(base_url().'dashboard');
		$page = $this->page;
		if($_POST){
			if($this->auth->login($this->input->post('email'), $this->input->post('password')) === TRUE)
			redirect(base_url().'dashboard');
		}
		$page['section']="user/login";
		$this->load->view('template/login', $page);
		
	}
	
	public function log(){
		$pcontent = $this->pcontent;
		$pcontent['template'] = 'template/logintemp';
		$pcontent['main']="user/forms/login";
		$this->load->view($pcontent['template'], $pcontent);
	}
	
	public function resetpassword()
	{
		$page = $this->page;
		if($_POST){
			$this->input->post(NULL, true);
			$this->form_validation->set_rules("email", "Email","trim|required|xss_clean|valid_email|callback_check_email");
			$this->form_validation->set_rules("newpassword", "New Password", "required|matches[confirmpassword]");
			$this->form_validation->set_rules("confirmpassword", "Confirm Password", "required");
			if($this->form_validation->run() != FALSE){			
				if($this->auth->change_password($this->input->post('newpassword'), $this->input->post('email')) != true)
				$page['error'] = 'Password was not reset.';
				else
				$page['error'] = "Your password was reset successfully.";
				
			}
		}
		$page['section']="user/reset";
		$this->load->view('template/login', $page);
		
	}
	
	function check_email(){
		if($this->UserMgmt->email_exist($this->input->post('email')) == true) return true;
		else{
			$this->form_validation->set_message('check_email','Email is not registered.');
			return false;
		}
	}
	
	function login(){
		$this->input->post(NULL, true);
		$this->form_validation->set_rules("email", "Email","trim|required|xss_clean|valid_email");
		$this->form_validation->set_rules("password", "password", "trim|required|xss_clean|callback_checkdb");
		if($this->form_validation->run() == FALSE){
		}else{
			//if valid, update user table, activity
			
			//set session
			redirect(base_url().'dashboard');
		}
	}
	
	function checkdb($pass){
		$email = $this->input->post('email');
		
		//validate From Database
		$user = $this->UserMgmt->login($email,$pass);
		if($user->num_rows() > 0 )	
		return true;
		else{
			$this->form_validation->set_message('checkdb', "Invalid email or password" );
			return false;
		}
	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */