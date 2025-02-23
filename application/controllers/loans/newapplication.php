<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newapplication extends CI_Controller {

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
	
	public $page = array("pagetitle" => "Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'template/loanmenu',
							"module" => "Header.Loans");
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	  $this->auth->restrict();
	}
	
	public function index()
	{			
		if($_POST){
			if(!empty($_POST['clientid'])){
				$array = array('applicant_id' => $_POST['clientid']);
				$this->session->set_userdata($array);
				
				if($this->session->userdata("applicant_id"))
				redirect(base_url().'client/addnew');
			}			
		}
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */