<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	function __contruct(){
		parent::__construct();
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Dashboard",
							"submod" => "Cash.Branches");
							
	function index(){
		$page = $this->page;
		$page['main'] = "user/dashboard";
		$this->load->view($page['template'], $page);
		}
	
}
?>