<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overview extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Header.Settings");
	function index(){
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'settings/overview';
		$this->load->view($page['template'], $page);
	}
}
?>