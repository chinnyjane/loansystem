<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

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
	 public $page = array ( "pagetitle" => "Fruits Consulting Inc",
							"nav" => 'template/usermgmtnav'); 
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');
	
	}
	public function index()
	{
		$this->auth-> logout();
		redirect(base_url());
	}
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */