<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
	}
	public $page = array ( "pagetitle" => "Payment - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod"=> "Payment");
	
	
	function index(){
		$page = $this->page;
		$page['main'] = 'cash/payment';
		$this->load->view($page['template'], $page);
	}
}
?>