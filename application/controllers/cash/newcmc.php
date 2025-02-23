<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newcmc extends CI_Controller {
	function __construct()
	{
	  parent::__construct();
	  $this->auth->restrict();	
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod" => "Create New CMC");
	function index(){
		$page = $this->page;
		
		if(isset($_POST['submit'])){
			$transid = $this->cash->createtrans();
			if($transid != false)
			redirect(base_url()."cash/daily/transaction/".$transid);
		}		
		
		$page['header'] = $this->UserMgmt->getheader();
		$page['subcontent'] = 'cash/forms/createcmc';
		$page['main'] = "cash/overview";
		$this->load->view($page['template'], $page);
	}
	function checkTransExist(){
		$data = array("dateTransaction"=>$_POST['date'],
					"branchID"=>$this->auth->branch_id());
		if($this->Loansmodel->get_data_from("cmctransaction", $data)->num_rows() > 0)
		{
			$this->form_validation->set_message("checkTransExist", "Transaction exists already.");
			return false;
		}else return true;		
	}
	
	function checkDateAdvance(){
		if($_POST['date'] > $this->auth->localdate())
		{
			$this->form_validation->set_message("checkDateAdvance", "You cannot open transaction in advance");
			return false;
		}else return true;
	}
	
}
?>