<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holidays extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Control Panel - Holidays - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Settings",
							"submod" => "Holidays",
							"active" => "Holidays");
	function index(){
		$page = $this->page;
		if($_POST){
			$this->settings->addholiday();
		}
		$page['header'] = $this->UserMgmt->getheader();
		//$page['main'] = 'settings/overview';
		$page['main'] = 'settings/holidays/list';
		$page["active"] = "Settings.Holidaus";
		$this->load->view($page['template'], $page);
	}
	
	function holidayexist(){
		$this->settings->holidayexist();
	}
}
?>