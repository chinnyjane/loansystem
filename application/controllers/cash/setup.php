<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod"=>"Settings");
	function index(){
		$page = $this->page;
		if(isset($_POST['submit'])){
			switch ($_POST['submit']) {
			case "Add Transaction Type":
				$this->addtransaction();
				break;
			case "Add Transaction Category":
				$this->addTransCategory();
				break;
			case "Add Payment Type":
				$this->addPaymentTpe();
				break;
			}
		}
		$page['header'] = $this->UserMgmt->getheader();
		$page['subcontent'] = 'cash/settings';
		$page['main'] = "cash/overview";
		$this->load->view($page['template'], $page);
		//$this->load->view('testing/forms');
	}
	
	function addPaymentTpe(){
		$this->form_validation->set_rules('payment','payment name', 'required|xss_clean|callback_checkpayment_exist');
		if($this->form_validation->run() != false){
			$data = array("typeOfPayment"=>$_POST['payment'],
						"active"=>1,
						"dateAdded"=>$this->auth->localtime());
			$this->UserMgmt->insert_data_to('paymenttype',$data);
		}
	}
	
	function addTransCategory(){
		$this->form_validation->set_rules('transcat','transaction category name', 'required|xss_clean|callback_checktranscat_exist');
		if($this->form_validation->run() != false){
			$data = array("transCatName"=>$_POST['transcat'],
						"active"=>1,
						"dateAdded"=>$this->auth->localtime());
			$this->UserMgmt->insert_data_to('transcategory',$data);
		}
	}
	
	function checktranscat_exist(){
		$data = array("active"=>1,
					"transCatName" => $_POST['transcat']);
		if($this->Loansmodel->get_data_from("transcategory", $data)->num_rows() > 0)
		{
			$this->form_validation->set_message("checktranscat_exist", "Transaction Category Name already exists.");
			return false;
		}else return true;
	}
	
	function addtransaction(){
		$this->form_validation->set_rules('transaction','transaction name', 'required|xss_clean|callback_checktrans_exist');
		$this->form_validation->set_rules('transcat','transaction category name', 'required');
		if($this->form_validation->run() != false){
			$data = array("transType"=>$_POST['transaction'],
						"active"=>1,
						"transCategory"=>$_POST['transcat'],
						"dateAdded"=>$this->auth->localtime());
			$this->UserMgmt->insert_data_to('transactiontype',$data);
		}
	}
	function checktrans_exist(){
		$data = array("active"=>1,
					"transType" => $_POST['transaction']);
		if($this->Loansmodel->get_data_from("transactiontype", $data)->num_rows() > 0)
		{
			$this->form_validation->set_message("checktrans_exist", "Transaction Name already exists.");
			return false;
		}else return true;
	}
	
	function checkpayment_exist(){
		$data = array("active"=>1,
					"typeOfPayment" => $_POST['payment']);
		if($this->Loansmodel->get_data_from("paymenttype", $data)->num_rows() > 0)
		{
			$this->form_validation->set_message("checkpayment_exist", "Payment Type already exists.");
			return false;
		}else return true;
	}
	
}
?>