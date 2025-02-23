<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collections extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
	}
	public $page = array ( "pagetitle" => "Account - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'final/template',
							"menu" => 'final/sidemenu',
							"module" => "Cash",
							"submod"=> "Cash.Collections");
	
	public function index(){
		$page = $this->page;
		$page['branch'] = $this->auth->branch_id();
		$this->cash->addtransaction();
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branch']);
		$page['header'] = $this->UserMgmt->getheader();
		$page['subcontent'] = 'cash/collections';
		$page['main'] = "cash/overview";
		$this->load->view($page['template'], $page);		
	}
	
	function orcheck(){
		$data = array("referenceNo"=>$_POST['reference'],
							"Amount_IN"=>$_POST['amount']);
		if($this->Loansmodel->get_data_from('bankstransactions', $data)->num_rows() > 0){
			$this->form_validation->set_message("orcheck", "Transaction has been posted already.");
			return false;
		}else
		return true;
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
				echo '<div class="modal-dialog ">';
				echo '<div class="modal-content ">';
				echo '<div class="modal-body ">';
				echo '<div class="alert alert-danger">Please complete required fields.</div>';
				echo validation_errors();
				echo '</div>';
				echo '<div class="modal-footer ">';
				echo '<input type="button" class="btn btn-sm btn-success" id="backadjust"  data-toggle="modal" data-dismiss="modal" data-target="#collection"  value="Back">';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}else{
				echo '<div class="modal-dialog ">';
				echo '<div class="modal-content ">';
				echo '<div class="modal-header ">';
				echo '<h4 class="modal-title" id="myModalLabel">Confirm Collection</h4>';
				echo '</div>';
				if(!isset($_POST['transid']))
				echo '<form class="form-horizontal" id="adjustmentform" method="post" action="'.base_url().'cash/collections">';
				else
				echo '<form class="form-horizontal" id="adjustmentform" method="post" action="'.base_url().'cash/daily/transaction/'.$_POST['transid'].'">';				
				echo '<div class="modal-body">';	
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
				echo '</div>';	
				echo '<div class="modal-footer" align="right">';
				echo '<input type="button" class="btn btn-sm btn-default" id="backadjust"  data-toggle="modal" data-dismiss="modal" data-target="#collection"  value="Back">';
				echo '<input type="submit" class="btn btn-sm btn-success"  name="submit"  value="Add Collection">';				
				echo '</div>';
				echo '</form>';	
				echo '</div>';
				echo '</div>';
				
			}		
		}
	}
}
?>