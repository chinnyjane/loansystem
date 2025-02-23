<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

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
	public $page = array ( "pagetitle" => "Cash Movement - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'final/template',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod"=> "Cash.Transactions");
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');
	
	}
	
	public function index()
	{
		echo "hello";
	}
	
	function profile(){
		$page = $this->page;
		$cid = $this->uri->segment(4);		
		echo $this->clients->getclientByID($cid);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */