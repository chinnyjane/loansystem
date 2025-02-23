<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Disbursements extends CI_Controller {
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
			$page['subcontent'] = 'cash/disbursements';
			$page['main'] = "cash/overview";
			$this->load->view($page['template'], $page);		
	}
	
	function cvcheck(){
		$data = array("referenceNo"=>$_POST['reference'],
							"Checkno"=>$_POST['checkno']);
		if($this->Loansmodel->get_data_from('bankstransactions', $data)->num_rows() > 0){
			$this->form_validation->set_message("cvcheck", "Transaction has been posted already.");
			return false;
		}else
		return true;
	}
	
	function post() {
		
		if($_POST){
			$this->form_validation->set_rules('reference', "CV No", "required|trim|xss_clean|callback_cvcheck");
			$this->form_validation->set_rules('checkno', "Check No", "required|trim|xss_clean|is_numeric");
			$this->form_validation->set_rules('PN', "PN", "required|trim|xss_clean");
			$this->form_validation->set_rules('particular', "Payee Name", "required|trim|xss_clean");
			$this->form_validation->set_rules('bankID', "Bank", "required|trim|xss_clean");
			$this->form_validation->set_rules('amount', "Amount", "required|trim|xss_clean|is_numeric");
			$this->form_validation->set_rules('transtype', "Disbursement Type", "required|trim|xss_clean");
			$this->form_validation->set_rules('explanation', "Explanation", "required|trim|xss_clean");
			if($this->form_validation->run() == False){
				echo '<div class="modal-dialog ">';
				echo '<div class="modal-content ">';
				echo '<div class="modal-body ">';
				echo '<div class="alert alert-danger">Please complete required fields.</div>';
				echo validation_errors();
				echo '</div>';
				echo '<div class="modal-footer ">';
				echo '<input type="button" class="btn btn-sm btn-success" id="backadjust"  data-toggle="modal" data-dismiss="modal" data-target="#disburse"  value="Back">';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}else{
				echo '<div class="modal-dialog ">';
				echo '<div class="modal-content ">';
				echo '<div class="modal-header ">';
				echo '<h4 class="modal-title" id="myModalLabel">Confirm Disbursement</h4>';
				echo '</div>';
				echo '<form class="form-horizontal" id="adjustmentform" method="post" action="'.base_url().'cash/daily/transaction/'.$_POST['transid'].'">';				
				echo '<div class="modal-body">';	
				$bank = $this->Cashmodel->getBankbyID($_POST['bankID']);
				$bank = $bank->row();
				$bankCode = $bank->bankCode;
				$trans = $this->Cashmodel->getTransType($_POST['transtype'])->row();
				$transtype = $trans->transType;
				
				
				echo '<div class="form-group">';
				echo '<div class="col-md-6"><label>CV No : </label> '.$_POST['reference'].'</div>';
				echo '<div class="col-md-3"><label>Check No : </label> '.$_POST['checkno'].'</div>';
				echo '<div class="col-md-3"><label>PN No : </label> '.$_POST['PN'].'</div>';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<div class="col-md-6"><label>Payee Name : </label> '.$_POST['particular'].'</div>';					
				echo '</div>';
				echo '<div class="form-group">';
				echo '<div class="col-md-6"><label>Disburse Type: </label> '.$transtype.'</div>';	
				echo '<div class="col-md-3"><label>Bank : </label> '.$bankCode.'</div>';
				echo '<div class="col-md-3"><label>Amount : </label> '.number_format($_POST['amount'],2).'</div>';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<div class="col-md-12"><label>Explanation : </label> '.($_POST['explanation'] ? $_POST['explanation'] : "(none)").'</div>';
				echo '</div>';				
				foreach ($_POST as $key=>$value){
					echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
				}
				echo '</div>';	
				echo '<div class="modal-footer" align="right">';
				echo '<input type="button" class="btn btn-sm btn-default" id="backadjust"  data-toggle="modal" data-dismiss="modal" data-target="#disburse"  value="Back">';
				echo '<input type="submit" class="btn btn-sm btn-danger"  name="submit"  value="Add Disbursement">';				
				echo '</div>';
				echo '</form>';	
				echo '</div>';
				echo '</div>';
				
			}		
		}
	}
}
?>