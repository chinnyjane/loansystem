<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collections extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
	}
	public $page = array ( "pagetitle" => "Collections - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod"=> "Collections");
	
	public function index(){
		$page = $this->page;
		$page['branch'] = $this->auth->branch_id();
		$this->cash->addtransaction();
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branch']);
		$page['header'] = $this->UserMgmt->getheader();
		$page['main'] = 'cash/collections';
		$this->load->view($page['template'], $page);		
	}
	
		
	function orcheck(){
		$data = array("referenceNo"=>$_POST['reference'],
							"Amount_IN"=>$_POST['amount'],
							"PN"=>$_POST['PN'],
							"isdeleted" => 0);
		if($this->Loansmodel->get_data_from('bankstransactions', $data)->num_rows() > 0){
			$this->form_validation->set_message("orcheck", "Transaction has been posted already.");
			return false;
		}else
		return true;
	}

	function orcheck_new(){
		$data = array("referenceNo"=>$_POST['reference'],
							"Amount_IN"=>$_POST['amount'],
							"isdeleted" => 0);
		if($this->Loansmodel->get_data_from('bankstransactions', $data)->num_rows() > 0){
			$this->form_validation->set_message("orcheck_new", "Transaction has been posted already.");
			return false;
		}else
		return true;
	}
	
	
	function form(){
		$page = $this->page;
		$page['main'] = 'cash/paymentform';
		$this->load->view($page['template'], $page);
	}
	
	
	function post(){
		if($_POST){
			$this->form_validation->set_rules('reference', "OR No", "required|trim|xss_clean|callback_orcheck");
			$this->form_validation->set_rules('PN', "PN", "required|trim|xss_clean");
			$this->form_validation->set_rules('particular', "Collection Name", "required|trim|xss_clean");
			$this->form_validation->set_rules('bankID', "Bank", "required|trim|xss_clean");
			$this->form_validation->set_rules('amount', "Amount", "required|trim|xss_clean|is_numeric");
			$this->form_validation->set_rules('transtype', "Collection Type", "required|trim|xss_clean");
			$this->form_validation->set_rules('paymentType', "Payment Type", "required|trim|xss_clean");
			if($this->form_validation->run() == False){					
				echo '<div class="alert alert-danger">Please complete required fields.</div>';
				echo validation_errors();
				echo '</div>';				
			}else{
				
				$bank = $this->Cashmodel->getBankbyID($_POST['bankID']);
				$bank = $bank->row();
				$bankCode = $bank->bankCode;
				$trans = $this->Cashmodel->getTransType($_POST['transtype'])->row();
				$transtype = $trans->transType;
				$data = array('paymentTypeID'=>$_POST['paymentType']);
				$payment = $this->Loansmodel->get_data_from("paymenttype", $data)->row();
				$payType = $payment->typeOfPayment;
				
				echo '<div class="form-group">';
				echo '<div class="col-md-6"><label>OR No : </label> '.$_POST['reference'].'</div>';
				echo '<div class="col-md-6"><label>PN No : </label> '.$_POST['PN'].'</div>';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<div class="col-md-12"><label>Collection Name : </label> '.$_POST['particular'].'</div>';			
				echo '</div>';
				echo '<div class="form-group">';
				echo '<div class="col-md-6"><label>Bank : </label> '.$bankCode.'</div>';
				echo '<div class="col-md-6"><label>Amount : </label> '.number_format($_POST['amount'],2).'</div>';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<div class="col-md-6"><label>Collection Type : </label> '.$transtype.'</div>';
				echo '<div class="col-md-6"><label>Payment Type : </label> '.$payType.'</div>';
				echo '</div>';				
				foreach ($_POST as $key=>$value){
					echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
				}
			
				
			}		
		}
	}
	
	
	function validatewithdraw(){
		if($_POST['amount'] > $_POST['beginbal'])
		{
			$this->form_validation->set_message('validatewithdraw', "Amount withdrawn is invalid");
			return false;
		}else{
			return true;
		}
	}
	
	function addplcollection(){			
		$this->form_validation->set_rules('beginbal', "Beginning Balance", "required|is_numeric");
		$this->form_validation->set_rules('amount', "amount withdrawn", "required|is_numeric|callback_validatewithdraw");
		$this->form_validation->set_rules('reference', "OR number", "required|is_numeric|callback_orcheck_new");
		
		if($this->form_validation->run() == false){
			echo '<div class="hide">error</div>';
			echo '<div class="alert alert-danger">'.validation_errors().'</div>';
			
		}else{
			if($this->Loansmodel->addCollection() == true)
			{
				echo "Payment has successfully made.";
			}else{
				echo "Error. Please try again.";
			}
		}
		
	}
	
	function add(){
		$page = $this->page;
		$page['main'] = "cash/collections/add";
		$this->load->view($page['main'], $page);	
	}
	
	function read(){
		$page = $this->page;
		$page['branch'] = $this->auth->branch_id();
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branch']);
		$page['main'] = "cash/collections/read";
		$this->load->view($page['main'], $page);		
	}
	
	
	function pl(){
		$page = $this->page;
		$page['branch'] = $this->auth->branch_id();
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branch']);
		$page['main'] = "cash/collections/plcollection";
		$this->load->view($page['main'], $page);		
	}
	
	function addpl(){
		$page = $this->page;
		$page['branch'] = $this->auth->branch_id();
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branch']);
		$page['main'] = "cash/forms/plcollection";
		$this->load->view($page['main'], $page);		
	}
	
	function plcol(){
		if($_POST){
			$this->form_validation->set_rules('reference', "OR No", "required|trim|xss_clean|callback_orcheck_new");
			$this->form_validation->set_rules('PN', "PN", "required|trim|xss_clean");
			$this->form_validation->set_rules('particular', "Collection Name", "required|trim|xss_clean");
			$this->form_validation->set_rules('bankID', "Bank", "required|trim|xss_clean");
			$this->form_validation->set_rules('amount', "Amount", "required|trim|xss_clean|is_numeric");
			$this->form_validation->set_rules('transtype', "Collection Type", "required|trim|xss_clean");
			$this->form_validation->set_rules('paymentType', "Payment Type", "required|trim|xss_clean");
			if($this->form_validation->run() == False){		
				echo validation_errors();
			}
		}
		/* [particulars] => ANDAN, DIANA 
			[reference] => 
			[transtype] => 6
			[paymentType] => POS
			[bankID] => 1
			[beginbal] => 12,000.00
			[amountleft] => 1,000
			[amount] => 11,000.00
			[excess] => 3,000
			[amountcash] => 
			[amountreceived] => 
			[check] => 
			[bankfcheck] => 
			[amountdue] => Array
				(
					[8726] => Array
						(
							[105275] => 8000
						)

				)

			[pdi] => Array
				(
					[8726] => Array
						(
							[105275] => 191.56
							[105276] => 351.56
							[105277] => 511.56
							[105278] => 671.56
							[105279] => 831.56
							[105280] => 991.56
						)

				)

			[totaldue] => 8,000.00
			[clientID] => 9456
			[pensionID] => 309
		)*/
	}
}
?>