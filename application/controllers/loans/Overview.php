<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overview extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	public $page = array("pagetitle" => "Loans ",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'template/loanmenu',
							"module" => "Header.Loans");
							
	public $debug = false; // turn to false if live
	
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	   $this->auth->restrict();
	}
	
	public function index()
	{			
		$page = $this->page;		
		$page['header'] = $this->UserMgmt->getheader();
		$page['main']="loans/overview";		
		$this->load->view($page['template'], $page);		
	}	
	
	function status(){
	
		$page = $this->page;
		$page['status'] = strtoupper($this->uri->segment(3));
		$page['header'] = $this->UserMgmt->getheader();
		//$page['status'] = strtolower($page['status']);
		switch ($page['status']){
			case ("DCRR"):								
				$page['main']="loans/dcrr";
			break;
			
			case "all":
				$page['submod']="All Loans";
				$page['main']="loans/consolidated";	
			break;
			
			default:				
				$page['main']="loans/consolidated";	
			break;
		
		}			
		$this->load->view($page['template'], $page);
	}
	function print_dcrr(){
		$page = $this->page;
		$page['module'] = "DCRR";
		$page['main'] = "reports/print_dcrr";
		$page['formtitle'] = "Daily Collection Preparation Report";		
		$page['branch']= $this->Branches->getDataById($_POST['branch_id']);
		$this->load->view('template/new/report', $page);
	}
	function status_old(){
	
		$page = $this->page;
		$page['status'] = strtoupper($this->uri->segment(4));
		$page['header'] = $this->UserMgmt->getheader();
		//$page['status'] = strtolower($page['status']);
		switch ($page['status']){
			case ("DCRR"):								
				$page['main']="loans/dcrr";
			break;
			
			case "all":
				$page['submod']="All Loans";
				$page['main']="loans/newconsolidated";	
			break;
			
			default:				
				$page['main']="loans/newconsolidated";	
			break;
		
		}			
		$this->load->view($page['template'], $page);
	}
	
	function newstatus(){
	
		$page = $this->page;
		$page['status'] = strtoupper($this->uri->segment(4));
		$page['header'] = $this->UserMgmt->getheader();
		//$page['status'] = strtolower($page['status']);
		switch ($page['status']){
			case ("DCRR"):								
				$page['main']="loans/dcrr";
			break;
			
			case "all":
				$page['submod']="All Loans";
				$page['main']="loans/consolidated_old";	
			break;
			
			default:				
				$page['main']="loans/consolidated_old";	
			break;
		
		}			
		$this->load->view($page['main'], $page);
	}
	
	function form(){
		$form = $this->uri->segment(3);
		$page['loanid'] = $this->uri->segment(4);
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($this->auth->branch_ID());
		$content = $this->load->view('forms/'.$form, $page, true);
		$url = 'cash/disbursements/post';
		$title = 'Check Voucher';
		$footer = $this->form->cvfooter();
		$modalid = "disburseform";
		$div = '<div class="modal-dialog modal-lg">';
				$div .= '<form action="'.base_url().$url.'" method="post" id="disburseform">';
					$div .= '<div class="modal-content">';
						$div .= '<div class="modal-header">';
							$div .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
							$div .= '<h4 class="modal-title" id="myModalLabel">'.$title.'</h4>';
						$div .= '</div>';
						$div .= '<div class="modal-body">';						
		
		 $div.= $content;
		echo $div;
		echo $this->form->modalformclose($footer);
	}
	
	function details(){
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		//$page['submod'] = "details";
		//$page['template']= "template/new/report";
		$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		$content = $this->load->view("loans/loanformdetails", null, true);
			
		//echo $this->form->modallg($content, $footer);
		
		$modalid = 'approval';
		$posturl = '';
		$formtitle = 'Loan Application';
		
			$div = '<div class="modal-dialog modal-lg">';
				$div .= '<form action="'.base_url().$posturl.'" method="post" class="formpost">';
					$div .= '<div class="modal-content">';
						$div .= '<div class="modal-header">';
							$div .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
							$div .= '<h4 class="modal-title" id="myModalLabel">'.$formtitle.'</h4>';
						$div .= '</div>';
						$div .= '<div class="modal-body">';						
		
		 $div.= $content;
		
		
		//echo $this->form->modalformopen($modalid, $posturl, $formtitle);
		echo $div;
		echo $this->form->modalformclose($footer);
		//$this->load->view($page['template'], $page);
	}
	
	public function newapplication(){
		$page = $this->page;		
		$page['header'] = $this->UserMgmt->getheader();
		//$page['submod'] = "New";
		if($_POST){
			if($_POST['submit'] == "Proceed to Loan Application"){
				$newdata = array(
					   'clientid' => $_POST['clientid'],
					   'loantype' => $_POST['loantype']                
				   );
				$this->session->set_userdata($newdata);	
			}elseif($_POST['submit'] == "Submit Application"){
				$this->submit();
			}					
		}
		if($this->uri->segment(3) == "cancel"){
				$url = base_url()."client/profile/".$this->session->userdata("clientid");
				$newdata = array(
					   'clientid' => '',
					   'loantype' => ''                
				   );
				$this->session->unset_userdata($newdata);
		}
		if($this->session->userdata("clientid") != null){
			$page['main']="loans/loanform";	
			$this->load->view($page['template'], $page);
		}else{
			if(empty($url))
			$url = base_url().'loans';
			redirect($url);
		}
					
	}
	
	function submit(){		
		if($this->product->validateform()== true){
			$loanID = $this->loansetup->addloandetails();
			if($loanID != false){
				$newdata = array(
					   'clientid' => '',
					   'loantype' => ''                
				   );
				$this->session->unset_userdata($newdata);
				redirect(base_url()."client/profile/".$_POST['clientid']."/loan/".$loanID);
			}
		}
		
	}	
	
	function verifyamount(){
		if($_POST){
			$loan = $_POST['loanapplied'];
			$terms = $_POST['terms'];
			$monthly = $loan/$terms;	
			$pensionID = $_POST['pensionaccount'];
			$pension = $this->product->getpensionamount($pensionID) - 100;
			
			if($monthly > $pension){
				$this->form_validation->set_message("verifyamount","Monthly Pension is not enough to pay the amount applied");
				return false;
			}else {
			  return true;
			 }
		}
	 }
	
	function loanterms(){
		header("content-type:application/json");
		if($_POST){						
			$loantype = $_POST['loancode'];
			$method = $_POST['method'];
			$loanstatus = $_POST['loanstatus'];
			$loan = explode(".", $_POST['loancode']);
					
			$data = array("loantypes.productID"=>$loan[0],
						"LoanCode"=>$loan[1],
						"LoanSubCode"=>$loanstatus,
						"PaymentTerm"=>$method,
						"computation"=>$_POST['computation'],
						"loantypes.active"=>1);
			$term = $this->Loansmodel->getProductTerms($data);
			
			if($term != false){
				//$term['post'] = $loantype.$loanstatus.$method;
				echo json_encode($term);
			}else{
				echo json_encode('error');
			}
		}else{
			echo json_encode('error');
		}
	}
	
	function feedetails(){
		$tmpl = array ('table_open' => '<table class="table table-condensed " width="50%" align="center">' );
				$this->table->set_template($tmpl);
				
		if($_POST){						
				$loantype = $_POST['loancode'];
				$method = $_POST['method'];
				$loanstatus = $_POST['loanstatus'];
				$pension = $_POST['pensionamount'];
				$comp = $_POST['computation'];
				//$pid = $this->Loansmodel->getproductid($loantype, $loanstatus, $method);			
			
			
			$loan = $_POST['loanapplied'];
			$terms = $_POST['terms'];
			$status = $loanstatus;
			$monthly = $loan/$terms;
			$excess = $pension - $monthly;
			
			//$fff = $this->Loansmodel->get_loan_fees($loantype, $loanstatus, $method, $loan, $terms,$comp);
			$fff = $this->Loansmodel->get_loan_fees($pid,  $loan, $terms,$extendedTerm);
			//echo "fee";
			$pid =  element('pid', $fff);	
			$fees =  element('fees', $fff);	
			$totalfees =  element('totalfees', $fff);
			$net =  element('netproceeds', $fff);
			$principal =  element('principal', $fff);
			$int = element('interest', $fff);
			
			if($loantype == 'PL'){
				if($excess < 0) {
					echo '<div class="alert alert-danger" role="alert">
							  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							  <span class="sr-only">Error:</span>
							  Monthly Pension cannot pay the monthly amortization. Please choose other terms.
							</div>';
				}
			}
			
			echo '<input type="hidden" name="pid" value="'.$pid.'">';
			echo "<h4>";			
			echo "Loan Computation";
			echo "</h4>";
		
			$this->table->add_row(array("data"=>"<label>Principal </label>", "width"=>"30%"),'<div class="input-group"><span class="input-group-addon">Php</span><input type="text" name="principal" value="'.number_format($principal['value'],2).'" class="input-sm form-control" readonly/></div>' );
			
			if($method != 'L'){
				$meth =  '<div class="input-group">'.'<span class="input-group-addon">Php</span>';
					$monthlyamort = array("class"=>'input-sm form-control', "name"=>'monthly', 'value'=>number_format($monthly,2), "readonly"=>'readonly');
				$meth .= form_input($monthlyamort);					
				$meth .= '</div>';
				$this->table->add_row("<label>Monthly</label>", $meth);
			}
			
			
			
			if($loantype == 'PL'){
				$meth =  '<div class="input-group">'.'<span class="input-group-addon">Php</span>';
					$exc = array("class"=>'input-sm form-control', "name"=>'excess', 'value'=>number_format($excess,2), "readonly"=>'readonly');
				$meth .= form_input($exc);					
				$meth .= '</div>';
				$this->table->add_row('<label>Excess </label>', $meth);
			}
			
				$ir =  '<div class="input-group">';
				$exc = array("class"=>'input-sm form-control', "name"=>'interest', 'value'=>number_format($int,2), "readonly"=>'readonly');
				$ir .= form_input($exc);					
				$ir .= '<span class="input-group-addon">%</span></div>';
				$this->table->add_row('<label>Interest </label>', $ir);
			
			
			
			$this->table->add_row(array("data"=> "<h4>LOAN FEES</h4>","colspan"=>2));
			
			if($fees != ''){
				
				foreach($fees as $fee){		

					$meth =  '<div class="input-group">'.'<span class="input-group-addon">Php</span>';
					 $meth .='<input type="text" class="input-sm form-control" name="fee['.$fee['feeID'].']" value="'.number_format($fee['feevalue'],2).'" readonly/>';					
					$meth .= '</div>';
					$this->table->add_row('<label>'.$fee['feename'].' </label>', $meth);
					
				}
			}
			
			$tf =  '<div class="input-group">';
			$tf .= '<span class="input-group-addon">Php</span>';
			$exc = array("class"=>'input-sm form-control', "name"=>'totalfees', 'value'=>number_format($totalfees['value'],2), "readonly"=>'readonly');
			$tf .= form_input($exc);					
			$tf .= '</div>';
			$this->table->add_row('<label>'.strtoupper($totalfees['name']).' </label>', $tf);
			
			$n =  '<div class="input-group">';
			$n .= '<span class="input-group-addon">Php</span>';
			$exc = array("class"=>'input-sm form-control', "name"=>'netproceeds', 'value'=>number_format($net['value'],2),"style"=>"font-weight: bold; font-size:13px; color: red", "readonly"=>'readonly');
			$n .= form_input($exc);					
			$n .= '</div>';
			$this->table->add_row('<label style="color:red">'.strtoupper($net['name']).' </label>', $n);
				
			echo $this->table->generate();				
			
		}
	}
	
	function action(){
		$action = $this->uri->segment(3);
		$loanid = $_POST['loanid'];
		switch ( $action ){
			case 'approve':
				$footer = "";
				$content = "For approval";
				
				if($this->loansetup->approveLoan($loanid) == true){
					$footer = "";
					$content = "Loan is approved. Check and check voucher will be prepared for release.";
					echo $content;
					
				}else {
					$footer = "";
					$content = "Loan is not approved. Please check the details and try again.".validation_errors();
					echo $content;
					
				}
			break;
			
			case 'decline':
				$this->loansetup->updateLoanStatus('decline',$loanid);
			break;
			
			case 'cancel':
				$this->loansetup->updateLoanStatus('canceled',$loanid);
			break;
			
			case 'release':
				
				//echo "</pre>";
				if($this->loansetup->loanrelease() ==true){
					$footer = "";
					$content = "Loan was released.";
					//echo $content;
					echo $this->form->modal($content, $footer);
				}
			break;	
			
			case 'assignpn':
				header("content-type:application/json");
				$pn = $this->loansetup->assignpn();
				if($pn == false){
					$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
					$msg['content'] = validation_errors();
					$msg['stat'] = false;					
				}else{
					$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
					$msg['content'] = "New PN was assigned.";
					$msg['stat'] = true;									
				}
				
				echo json_encode($msg);
			break;
			
			case 'update':
				if (isset($_POST['update'])){					
					switch ($_POST['update']){
						case 'maturity':
						header("content-type:application/json");
							$this->form_validation->set_rules("maturityDate", "Maturity Date", "callback_validMaturity");
							if($this->form_validation->run() === FALSE){
								$msg['stat'] = false;
								$msg['content'] = validation_errors();
							}else{
								$data = array("MaturityDate"=>$_POST['maturityDate'],
														"DateDisbursed"=>$_POST['DisburseDate'],
														"status"=>$_POST['status'],
														"Term"=>$_POST['term'],
														"extension"=>$_POST['extension'],
														"dateStartPayment"=>$_POST['startpaymentdate'],
														"paymentmethod"=>$_POST['paymentmethod'],
														"dateModified"=>$this->auth->localtime());
								$where = array("loanID"=>$_POST['loanid']);							
								$this->Loansmodel->update_data("loanapplication", $where, $data);
								$msg['stat'] = true;
								$msg['content'] = "Date of transactions was changed.";
							}
							echo json_encode($msg);
						break;
					}
				}
			break;
			
		}		
	}
	
	function validMaturity(){
		if($_POST['maturityDate'] != '00/00/0000' and $_POST['DisburseDate'] !='00/00/0000' and $_POST['startpaymentdate'] != '00/00/0000'){
			if($_POST['maturityDate'] < $_POST['startpaymentdate']){
				$this->form_validation->set_message("validMaturity", "MaturityDate is invalid.");
				return false;
			}elseif($_POST['maturityDate'] < $_POST['DisburseDate']){
				$this->form_validation->set_message("validMaturity", "MaturityDate is invalid.");
				return false;
			}else{
				return true;
			}
		}else{
			$this->form_validation->set_message("validMaturity", "Invalid Dates were entered.");
			return false;
		}
	}
	
	function pnexist(){
		//check PN from Old records
		//$data = array("PN"=> $this->input->post('bookpn'));		
		//$old = $this->Loansmodel->get_data_from("loanrecords", $data);
		
		//check PN from new table loanapplication
		$data2	 = array("PN"=> $this->input->post('bookpn'),
							"branchID"=>$this->input->post('branchid'),
							"status <>"=>'canceled');		
		$new = $this->Loansmodel->get_data_from("loanapplication", $data2);
		
		if( $new->num_rows() > 0)
		{
			$this->form_validation->set_message('pnexist', "PN is already used.");
			return false;
		}else return true;
	}
	
	function generate_loanschedule(){
		$term = $_POST['term'];
		$approve = $_POST['approveamount'];
		$date = $_POST['dateDisbursed'];
		$loanid = $_POST['loanid'];
		$method = $_POST['method'];
		//$this->updateMaturity($term, $date);
		$this->loansetup->update_loanschedule($term, $approve,$date, $loanid, $method);
		echo json_encode(true);
	}
	
	function updateMaturity($term, $releasedDate){
		$matdate = $releasedDate."+".$term." month";
		$data = array("MaturityDate"=>date("Y-m-d", strtotime($matdate)));
		$id = array("loanID"=>$_POST['loanid']);
		$this->Loansmodel->update_data("loanapplication", $id, $data);
		
		/*$mdata = array("DateDisbursed"=>$releasedDate." 00:00:00");
		//$lid = array("loanID"=>$_POST['loanid'],
					"DateDisbursed"=>NULL,
					"status"=>'granted');
		$this->Loansmodel->update_data("loanapplication", $lid, $mdata);*/
	}
	
	function extendloan(){
		$page = $this->page;
		$page['submod'] = "Loan Application";
		$page['main'] = "loans/forms/extendloan";
		$this->load->view($page['template'], $page);
	}
	
	function loancount($status){
		echo $this->Loans->loancount($status);
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */