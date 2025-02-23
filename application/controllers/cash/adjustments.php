<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adjustments extends CI_Controller {
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
		$page['transdate'] = $this->auth->localdate();
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branch']);
		$page['header'] = $this->UserMgmt->getheader();
			$page['subcontent'] = 'cash/adjustments';
			$page['main'] = "cash/overview";
			$this->load->view($page['template'], $page);		
	}
	
	function post(){
		if($_POST){
			$this->form_validation->set_rules('reference', "JV No", "required|trim|xss_clean");
			$this->form_validation->set_rules('particular', "Particulars", "required|trim|xss_clean");
			$this->form_validation->set_rules('addorless', "add or less", "required|trim|xss_clean");
			$this->form_validation->set_rules('bankID', "Bank", "required|trim|xss_clean");
			$this->form_validation->set_rules('amount', "Amount", "required|trim|xss_clean|is_numeric");
			$this->form_validation->set_rules('explanation', "Explanation", "trim|xss_clean");
			$this->form_validation->set_rules('transtype', "Adjustment Type", "required|trim|xss_clean");
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
				
				echo '<div class="form-group">';
				echo '<div class="col-md-6"><label>JV No : </label> '.$_POST['reference'].'</div>';
				echo '<div class="col-md-6"><label>Particular : </label> '.$_POST['particular'].'</div>';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<div class="col-md-6"><label>Adjustment Type : </label> '.$transtype.'</div>';			
				echo '<div class="col-md-6"><label>+/- : </label> '.$_POST['addorless'].'</div>';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<div class="col-md-6"><label>Bank : </label> '.$bankCode.'</div>';
				echo '<div class="col-md-6"><label>Amount : </label> '.number_format($_POST['amount'],2).'</div>';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<div class="col-md-12"><label>Explanation : </label> '.($_POST['explanation'] ? $_POST['explanation'] : "(none)").'</div>';
				echo '</div>';				
				foreach ($_POST as $key=>$value){
					echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
				}
				
				
			}		
		}
	}
}
?>