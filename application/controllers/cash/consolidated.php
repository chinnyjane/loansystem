<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consolidated extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Consolidated CMC - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod" => "Consolidated"
							);
	function index(){
		$page = $this->page;
		if(isset($_POST['date']))
		$date=$_POST['date'];
		else $date = $this->auth->localdate();
		//if($this->auth->holiday($date) == false)
		//$this->cash->transactions($date);
		$page['header'] = $this->UserMgmt->getheader();
		$page['subcontent'] = 'cash/consolidated';
		$page['main'] = "cash/overview";
		$this->load->view($page['template'], $page);
	}
	
	function details(){
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'cash/branches';
		$this->load->view($page['template'], $page);
	}
	
	function report(){
		$page = $this->page;
		if(isset($_POST['date']))
		$date=$_POST['date'];
		else $date = $this->auth->localdate();
		$page['date'] = $date;
		$page['template']= "final/reporttemplate";
		$page['main'] = 'cash/consolidatedreport';
		//$page['main'] = "cash/overview";
		$this->load->view($page['main'], $page);
	}
	
}
?>