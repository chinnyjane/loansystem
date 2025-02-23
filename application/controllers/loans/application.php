<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application extends CI_Controller {

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
	
	public $page = array ( "pagetitle" => "Loans - Fruits Consulting Inc",
							"nav" => 'final/navheader',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Header.Loans",
							);
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	  $this->auth->restrict();
	}
	
	public function index()
	{	
	
		if(isset($_POST['clientid']))
		$clientid = $_POST['clientid'];
		else redirect(base_url().'loans');
		
		if(isset($_POST['submit'])){
			if($_POST['submit']=="Save Loan Information"){			
				
				$loanstatus = $_POST['loanstatus'];
				$method = $_POST['method'];
				$loantype = $_POST['pid'];
				$loancode= $_POST['loancode'];
				$computation = $_POST['computation'];
				$extendedTerm = ($_POST['extendedTerm'] ? $_POST['extendedTerm'] : '');
				$StartDatePayment = ($_POST['lastDate'] ? date("Y-m-d",strtotime($_POST['lastDate']."+1 month")) : '');
				$matdate = ($_POST['lastDate'] ? date("Y-m-d",strtotime($_POST['lastDate']."+".$extendedTerm." month")) : '');
				$parentLoan = ($_POST['parentLoan'] ? $_POST['parentLoan'] : '');
				$loanapplied = floatval(str_replace(",","",$_POST['loanapplied']));				
				$terms = $_POST['terms'];
				if(isset($_POST['pensionamount']))
				$pension =  floatval(str_replace(",","",$_POST['pensionamount']));
				if(isset($_POST['principal']))
				$principal =  floatval(str_replace(",","",$_POST['principal']));
				if(isset($_POST['monthly']))
				$monthly =  floatval(str_replace(",","",$_POST['monthly'])) ;
				if(isset($_POST['interest']))
				$interest = floatval(str_replace(",","",$_POST['interest'])) ;
				//$net = floatval(str_replace(",","",$_POST['netproceeds']));
				//$totalfees = floatval(str_replace(",","",$_POST['totalfees']));
				$fee = $_POST['fee'];
				
				if(isset($fee)){	
					$totalfees = 0;
					foreach($fee as $feeID=>$value){
						$totalfees += floatval(str_replace(",","",$value));
					}
					$net = $principal - $totalfees;
				}
				$fees = NULL;
				
				//data for loan application table
				$loandata = array("ClientID"=>$clientid,
							"status"=>"processing",
							"branchID"=>$this->auth->branch_id(),
							"LoanType"=>$loantype,
							"computation"=>$computation,
							"AmountApplied"=>$loanapplied,
							"extension"=>$extendedTerm,
							"parentLoan"=>$parentLoan,
							"Term"=>$terms,
							"principalAmount"=>$principal,
							"netproceeds"=>$net,
							"interest"=>$interest,
							"paymentmethod"=>$method,
							"dateStartPayment"=>$StartDatePayment,
							"MaturityDate"=>$matdate,
							"MonthlyInstallment"=>$monthly,
							"dateApplied"=>$this->auth->localtime(),
							"LoanProcessor"=>$this->auth->user_id(),
							"active"=>1);
				
				$this->db->trans_start(TRUE);
				$loan = $this->Loansmodel->addnewloan($loandata, $fee);
				
				if($loan != false){
					$sessiondata = array("loanid"=>$loan['loanid'],
												"pid"=>$loan['pid'],
												"clientid"=>$clientid);
												
					$this->addnewcollateraltoloan($loan['loanid']);
					$this->subreqtoloan($loan['loanid']);
					
				}
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}else{
					redirect(base_url()."client/profile/".$clientid."/loan/".$loan['loanid']);
				}
			}	
		}
		$page = $this->page;
		$page["submod"] = "Add Loan";
		$page['client'] = $this->Clientmgmt->getclientinfoByID($clientid);
		
		$page['main'] ='loans/addloan';
		
		//$page['main'] = 'template/new/content';
		$this->load->view($page['template'], $page);
	}
	
	
	function submit(){
		header("content-type:application/json");
				
		if($_POST){
			
			if(!isset($_POST['clientid']))
			{
				$msg['stat'] = false;
				$msg['data'] = "Please select Client. Loan application cannot proceed.";
			}else{
				$clientid = $_POST['clientid'];
				$loanstatus = $_POST['loanstatus'];
				$method = $_POST['method'];
				$loantype = $_POST['pid'];
				$loancode= $_POST['loancode'];
				$computation = $_POST['computation'];
				
				$loanapplied = floatval(str_replace(",","",$_POST['loanapplied']));				
				$terms = $_POST['terms'];
				if(isset($_POST['pensionamount']))
				$pension =  floatval(str_replace(",","",$_POST['pensionamount']));
				if(isset($_POST['principal']))
				$principal =  floatval(str_replace(",","",$_POST['principal']));
				if(isset($_POST['monthly']))
				$monthly =  floatval(str_replace(",","",$_POST['monthly'])) ;
				if(isset($_POST['interest']))
				$interest = floatval(str_replace(",","",$_POST['interest'])) ;
				
				$fee = $_POST['fee'];
				
				if(isset($_POST['extendedTerm']))
				$extendedTerm = $_POST['extendedTerm'] ;
				else $extendedTerm = '';
				
				
				if(isset($_POST['lastDate']))
				$StartDatePayment = date("Y-m-d",strtotime($_POST['lastDate']."+1 month"));
				else $StartDatePayment = '';
				
				if(isset($_POST['lastDate']))
				$matdate = date("Y-m-d",strtotime($_POST['lastDate']."+".$extendedTerm." month"));
				else $matdate = '';
				
				if(isset($_POST['parentLoan']))
				$parentLoan = $_POST['parentLoan'] ;
				else $parentLoan = '';
				
								
				
				if(isset($fee)){	
					$totalfees = 0;
					foreach($fee as $feeID=>$value){
						$totalfees += floatval(str_replace(",","",$value));
					}
					$net = $principal - $totalfees;
				}
				$fees = NULL;
				
				//data for loan application table
				$loandata = array("ClientID"=>$clientid,
							"status"=>"processing",
							"branchID"=>$this->auth->branch_id(),
							"LoanType"=>$loantype,
							"computation"=>$computation,
							"AmountApplied"=>$loanapplied,
							"extension"=>$extendedTerm,
							"parentLoan"=>$parentLoan,
							"Term"=>$terms,
							"principalAmount"=>$principal,
							"netproceeds"=>$net,
							"interest"=>$interest,
							"paymentmethod"=>$method,
							"dateStartPayment"=>$StartDatePayment,
							"MaturityDate"=>$matdate,
							"MonthlyInstallment"=>$monthly,
							"dateApplied"=>$this->auth->localtime(),
							"LoanProcessor"=>$this->auth->user_id(),
							"active"=>1);
				
				$this->db->trans_start(TRUE);
				$loan = $this->Loansmodel->addnewloan($loandata, $fee);
				
				if($loan != false){
					$sessiondata = array("loanid"=>$loan['loanid'],
												"pid"=>$loan['pid'],
												"clientid"=>$clientid);
												
					$this->addnewcollateraltoloan($loan['loanid']);
					$this->subreqtoloan($loan['loanid']);
					
				}				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$msg['stat'] = false;
					$msg['data'] = 'Error. Please try again.';
				}else{
					//redirect(base_url()."client/profile/".$clientid."/loan/".$loan['loanid']);
					$msg['stat'] = true;
					$msg['data'] = 'New Loan was saved. ';
					$msg['url'] = base_url()."client/profile/".$clientid."/loan/".$loan['loanid'];
				}
			}
		}else{
			$msg['stat'] = false;
			$msg['data'] = 'Error. You have not submitted any information.';
		}
		echo json_encode($msg);
	}
	
	function collateral(){		
		
		$page = $this->page;
		$page["submod"] = "Add Collateral on Loan";
		$page['client'] = $this->Clientmgmt->getclientinfoByID($this->session->userdata('clientid'));
		if($this->addcollateraltoloan() == true)
			echo "ok";
		else
			echo "not ok";
		$page['content'] = $this->load->view('loans/addcollateral', $page, true);
		
		$page['main'] = 'template/new/content';
		$this->load->view($page['template'], $page);
	}
	
	function addnewcollateraltoloan($loanid){							
		
		$pid = $this->input->post('pid');
		$clientid = $this->input->post('clientid');
		//$loantype = $_POST['loancode'];
		$loantype = explode(".", $_POST['loancode']);
		if($_POST['collateralID'] ==''){
			if($this->Loansmodel->addcollateralDetails($loanid, $clientid, $pid, $loantype[0]) ==true)
				return true;
			else
			return false;
		}else{
			$this->Loansmodel->addexistcoltoloan($_POST['collateralID'], $loanid);
			return true;
		}
			
		
	}
	
	public function process(){
		
		$page = $this->page;
		$page["submod"] = "Loans Processing";
		$page['header'] = $this->UserMgmt->getheader();
		
		$loanid = $this->uri->segment(4);
		
		if(empty($loanid)){
			$page['content'] = $this->load->view('client/applicationflow', $page, true);
		}else{
			$page['loanid'] = $loanid;
			$page['loans'] = $this->Loansmodel->getLoanDetails($loanid);
			$page['loaninfo'] = $page['loans']['loaninfo'];
			$page['loantype'] = $page['loaninfo']->row()->LoanCode;
			$page['status'] = $page['loaninfo']->row()->status;
			$page['pid'] = $page['loaninfo']->row()->loanTypeID;
			$page['colID'] = $page['loaninfo']->row()->pensionID;
			$page['client'] = $page['loans']['clientinfo'];
			$page['clientid'] = $page['client']->row()->ClientID;
			$page['spouse'] = $page['loans']['spouseinfo'];
			$page['emp'] = $page['loans']['employment'];
			$page['collateral'] = $page['loans']['collaterals'];
			$page['comaker'] = $page['loans']['comaker'];
			$page['incomeexpense'] = $page['loans']['incomeexpense'];
			$page['content'] = $this->load->view('client/applicationflow', $page, true);			
		}
		
		$page['main'] = 'template/new/content';
		$this->load->view($page['template'], $page);
	}
	
	public function loandetails(){
				
		if($_POST['clientid'] != '')
			$clientid = $_POST['clientid'];
		else
			$clientid = $this->session->userdata('applicant_id');
			
			if($_POST['loanid'] != ''){
				$loanid = $_POST['loanid'];
			}elseif($this->session->userdata('loanid') != ''){
				$loanid = $this->session->userdata('loanid');
			}else{
				$loanid = '';	
			}
			//echo $this->session->userdata('loanid');	
			//echo $loanid;
			if(empty($clientid)){			
				$content = "Please select/enter your client information first.";	
			}else{
				$content = "We have an active client here.";
				if(empty($loanid)){
					//check if he has a existing loan on process
					if($this->Loansmodel->getExistingProcessByClientID($clientid) == false)
					{
						$loan = $this->Loansmodel->addloandetails();
						if($loan == true){
							$this->session->set_userdata('loantype',$_POST['loancode']);					
							$content = "New Loan was added.".$this->session->userdata('loanid');
						}else{					
							$content = "An error was encountered.";
						}
					}else{
						$content = 'The client has an existing application.';
					}
				}else{
					$content = 'Loan details were updated.'.$loanid;
					$loan = $this->Loansmodel->updateloandetails($loanid);
				}
			}
		
		$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		echo $this->form->modal($content, $footer);
		
		/*
		if($_POST){	
			if($this->session->userdata('loanid') == '')				
			$loan = $this->Loansmodel->addloandetails();
			else
			$loan= false;			
			if(empty($clientid)){
				$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
				$content = "Please select/enter your client information first.";	
			}elseif($loan == true){
				$this->session->set_userdata('loantype',$_POST['loancode']);
				$footer = "<a href='#collateral'  class='btn btn-default btn-sm'>Continue</a>";
				$content = "New Loan was added.";			
			}else{
				$footer = "<a href='".base_url()."client/addnew/newapp#collateral'>Continue</a>";
				$content = "AN error encountered please try again.";	
			}
			*/
			
		
	}
	
	function updatecollateral(){
		header("content-type:application/json");
		if($_POST){
			if(isset($_POST['pensionID'])){
				if($_POST['pensionID'] != ''){
					$data = array('pensionID'=>$_POST['pensionID'],
										'dateModified'=>$this->auth->localtime()
										);	
					$where = array('loanID'=>$_POST['loanid']);
					$this->Loansmodel->update_data('loanapplication', $where, $data);	
				}
			}else{
				foreach($_POST['colvalue'] as $colID=>$value){
					$data = array("value"=>$value,
								"dateModified"=>$this->auth->localtime(),
								"modifiedBy"=>$this->auth->user_id());
					$where = array("colID"=>$colID);
					$this->Loansmodel->update_data('collaterals_details', $where, $data);		
					//echo "ok";
				}
			}
			echo json_encode(true);
		}
	}
	
	function collaterals(){
		if(isset($_POST['clientid']))
			$clientid = $_POST['clientid'];
		else
			$clientid = $this->session->userdata('applicant_id');		
		
		if($_POST['loanid'] != ''){
			$loanid = $_POST['loanid'];
		}elseif($this->session->userdata('loanid') != NULL){
			$loanid = $this->session->userdata('loanid');
		}else{
			$loanid = '';	
		}
		
		if(isset($_POST['comakerid'])){
			$clientID = $_POST['comakerid'];
		}elseif($this->session->userdata('comakerid') != NULL){
			$clientID = $this->session->userdata('comakerid');
		}else{
			$clientID = '';	
		}
		
		if($_POST['loantype'] != ''){
			$loantype = $_POST['loantype'];
		}elseif($this->session->userdata('loantype') != NULL){
			$loantype = $this->session->userdata('loantype');
		}else{
			$loantype = '';	
		}
		
		$pid = $this->session->userdata('pid');
		
		$content = $loanid;
		$footer = '';
		
		if(!empty($loanid)){			
			//if($this->Loansmodel->addcollaterals($loantype,$loanid,$clientID, $pid) == true)
			if($this->Loansmodel->addcollateralDetails($loanid, $clientID, $pid, $loantype) == true)
			{
				$footer = "<a href='#comaker'  class='btn btn-default btn-sm'>Continue</a>";
				$content = "Collateral was added.";
			}else{
				$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
				$content = "Please try again.";
			}
		}else{
			$content = "You haven't entered your Loan Details yet.";
			$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		}
		
		echo $this->form->modal($content, $footer);
	}
	
	function loanrequirements(){
		header("content-type:application/json");
		$loan = explode(".", $_POST['loancode']);
		$reqs =  $this->loansetup->requirements('', $loan[0]);
		$content['col'] = $this->collateralinfo($loan[0]);
		$content['req'] = $reqs['req'];
		echo json_encode($content);
	}
	
	function computation(){
		header("content-type:application/json");
		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";\
		$loan = explode(".", $_POST['loancode']);
		$pid = $this->Loansmodel->getproductid($loan[0], $loan[1], $_POST['loanstatus'], $_POST['method'],$_POST['computation']);
		$content['pid'] = $pid->loanTypeID;
		$content['fees'] = $this->feedetails($pid->loanTypeID);
		echo json_encode($content);
	}
	
	function loancomputation(){
		header("content-type:application/json");
		if(!isset($_POST['terms']))
			$_POST['terms'] = 1;
		
		$loan = explode(".", $_POST['loancode']);
		
		$pid = $this->Loansmodel->getproductid($loan[0], $loan[1], $_POST['loanstatus'], $_POST['method'],$_POST['computation']);
		//$reqs =  $this->loansetup->requirements('', $pid->productID);
		//$content['req'] = $reqs;
		//$data = $pid.'-'.$_POST['loancode'].'-'. $_POST['loanstatus'].'-'. $_POST['method'].'-'.$_POST['computation'];
		//$content['req'] = 
		$content['pid'] = $pid->loanTypeID;
		$content['fees'] = $this->feedetails($pid->loanTypeID);
		$content['terms'] =$_POST['terms'];
		echo json_encode($content);
	}
	
	function clientCollateral(){
		
		if(strpos($_POST['loancode'],'PL') !== false)
		{
			
		}else{
				
		}
		return $collateral;
	}
	
	function collateralinfo($pid){
		//$collateral = $this->clientCollateral();
		$content = '';	
		//$content .= $this->db->last_query();
		$tmpl = array ('table_open'  => '<table class="table table-bordered table-hover" >');
		$this->table->set_template($tmpl);
		$loan = explode(".", $_POST['loancode']);
		//$content .= $loan[1];
		if($loan[0]=='3')
		{
			$collateral = $this->Loansmodel->get_pensionofclient($this->input->post('clientid'), $this->input->post('cno'), $this->input->post('branchID'));
			//$content .= $this->db->last_query();
			if($collateral->num_rows() > 0){
				$content .="<label>SELECT PENSION : </label><br/>";
				foreach($collateral->result() as $col){
					$monthly = $col->monthlyPension ? $col->monthlyPension : 0;
					$this->table->add_row('<input type="radio" name="collateralID" value="'.$col->PensionID.'">', $col->PensionType,$col->PensionStatus,  number_format($monthly,2), $col->bankBranch."-".$col->Bankaccount);
					//$content .= '<label><input type="radio" name="collateralID" value="'.$col->PensionID.'">'.$col->bankBranch."-".$col->Bankaccount."</label>";
					//$content .= "<br/>";
				}
				$this->table->set_heading("Select","PensionType","Pension Status", "Monthly", "Bank & Branch");
				$content .= $this->table->generate();
				$content .= "<label>Or </label>";
			}
			
			$content .= '<br/><label><input type="radio" name="collateralID" value="" checked> Add New Pension</label>';
			$content .= $this->load->view('loans/forms/plform', '', true);
			
			return $content;
			
		}else{
			$collateral = $this->Products->getCollateralsbyClient($this->input->post('clientid'), $pid);
			//$content .= $this->db->last_query();			
			if($collateral->num_rows() > 0){
				$content .= "<b>SELECT COLLATERAL : </b><br/>";
				foreach($collateral->result() as $col){
					if(strpos($col->productCode, "REM") !== false)
						$type = "REM";
					if(strpos($col->productCode, "CM") !== false)
						$type =  "CM";
					if($loan[0] == $col->productID)
					$c = '<label><input type="radio" name="collateralID" value="'.$col->collateralID.'" class="input"> &nbsp; '.$type.'</label>';
					//else $c = '<label><input type="radio" name="collateralID" value="'.$col->collateralID.'" class="input"> </label>';
					$this->table->add_row(array("data"=>$c, "width"=>"25%"),  $col->collateralname, $col->value);
				}
				$this->table->set_heading("Select Collateral ", "Name", "Description");
				//$content .= $this->table->generate();
					//$content .= "<label>Or </label>";			
			}else{
				$content .= 'No Exisitng Collateral.';
			}
			
			$select = '<br/><label><input type="radio" name="collateralID" value="" checked>  NEW COLLATERAL</label>';
				$cols = $this->Products->getProCollaterals($pid);
				if($cols->num_rows() > 0){
					//$content = '';
					$new = '';
					foreach($cols->result() as $col){
						$new .= '<div class="row form-group">';						
						$new .= '<div class="col-md-12" >';
						$new .= '<label>'.$col->collateralname.'</label>';						
						$new .= '<input class="input-sm form-control" type="text" name="col['.$col->procolID.']" placeholder="'.$col->collateralname.'">';		
						$new .= '<input class="input-sm form-control" type="hidden" name="colname['.$col->procolID.']" value="'.$col->collateralname.'">';
						$new .= '</div>';
						$new .= '</div>';
					}		
					
					$this->table->add_row($select, array("colspan"=>2, "data"=>$new));
					$content .= $this->table->generate();
				}else{
					$content = 'No Collaterals for this product.';	
				}
			
		}
		return $content;
		
	}
	
	function requirements(){		
		
		$pid = $this->Loansmodel->getproductid($_POST['loancode'], $_POST['loanstatus'], $_POST['method'],$_POST['computation']);
		
		//$loanid = '555';
		//$pid = $this->session->userdata('pid');
		$loanid = ($this->session->userdata('loanid') ? $this->session->userdata('loanid') : '');
		$reqs =  $this->loansetup->requirements($loanid, $pid);
		$col = $this->form->collateralForm($pid, $loanid,$_POST['loancode']);
		
		//$this->load->view('loans/forms/requirements', $page);
		$content['req'] = $reqs['req'];
		$content['col'] = $col;
		
		echo json_encode($content);
		
	}
	
	function loanterms(){		
		if($_POST){						
			$loantype = $_POST['loancode'];
			$method = $_POST['method'];
			$loanstatus = $_POST['loanstatus'];	
			
			$loan = explode(".", $loantype);
			
			$data = array("loantypes.productID"=>$loan[0],
						"LoanCode"=>$loan[1],
						"LoanSubCode"=>$loanstatus,
						"PaymentTerm"=>$method,
						"computation"=>$_POST['computation'],
						"active"=>1);
			$term = $this->Loansmodel->getProductTerms($data);
			
			if($term != false){
				$term['post'] = $loantype.$loanstatus.$method;
				return $term;
			}
		}
	}
	
	function feedetails($pid){
		$tmpl = array ('table_open' => '<table class="table table-condensed " width="50%" align="center">' );
				$this->table->set_template($tmpl);
		$ret = "";
		if($_POST){						
				$loantype = $_POST['loancode'];
				$method = $_POST['method'];
				$loanstatus = $_POST['loanstatus'];
				if(isset($_POST['pensionamount']))
				$pension = floatval(str_replace(",","",$_POST['pensionamount']));
				else $pension = '';
				$comp = $_POST['computation'];
				//$pid = $this->Loansmodel->getproductid($loantype, $loanstatus, $method);			
			
			$terms = $_POST['terms'];
			if(isset($_POST['extendedTerm']))
			$extendedTerm = ($_POST['extendedTerm'] ? $_POST['extendedTerm'] : '');
			else $extendedTerm ='';
			
			if($pension != ''){
				$maxloan = ($pension) * $terms;
			}
				$loan = floatval(str_replace(",","",$_POST['loanapplied']));		
			
			$status = $loanstatus;
			$monthly = ($extendedTerm ? $loan/$extendedTerm :$loan/$terms);
			$excess =  ($pension) - $monthly;
			
			//$fff = $this->Loansmodel->get_loan_fees($pid, $loantype, $loanstatus, $method, $loan, $terms,$comp);
			$fff = $this->Loansmodel->get_loan_fees($pid,  $loan, $terms,$extendedTerm);
			//echo "fee";
			//$pid =  element('pid', $fff);	
			$fees =  element('fees', $fff);	
			$totalfees =  element('totalfees', $fff);
			$net =  element('netproceeds', $fff);
			$principal =  element('principal', $fff);
			$int = element('interest', $fff);
			
			if(strpos($loantype,'PL') !== false)
			{
				if($excess < 0) {
					$ret .= '<div class="alert alert-danger" role="alert">
							  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							  <span class="sr-only">Error:</span>
							  Monthly Pension cannot pay the monthly amortization. Please choose other terms.
							</div>';
				}
				//$this->table->add_row(array("data"=>"<label>Max. Loan </label>", "width"=>"30%"),'<div class="input-group"><span class="input-group-addon">Php</span><input type="text" name="principal" value="'.number_format($maxloan,2).'" class="input-sm form-control" readonly/></div>' );
			}
			
			$ret .= '<input type="hidden" name="pid" value="'.$pid.'">';
			$ret .= "<h4>";			
			$ret .= "Loan Computation";
			$ret .= "</h4>";
		
			$this->table->add_row(array("data"=>"<label>Principal </label>", "width"=>"30%"),'<div class="input-group"><span class="input-group-addon">Php</span><input type="text" name="principal" value="'.number_format($principal['value'],2).'" class="input-sm form-control" readonly/></div>' );
			
			if($method != 'L'){
				$monthly = ($extendedTerm ? $principal['value']/$extendedTerm :$principal['value']/$terms);
				$meth =  '<div class="input-group">'.'<span class="input-group-addon">Php</span>';
					$monthlyamort = array("class"=>'input-sm form-control', "name"=>'monthly', 'value'=>number_format($monthly,2), "readonly"=>'readonly');
				$meth .= form_input($monthlyamort);					
				$meth .= '</div>';
				$this->table->add_row("<label>Monthly</label>", $meth);
			}
			
			
			
			if(strpos($loantype,'PL') !== false)
			{
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
					if($fee['comp'] == 'fixed') $read = ''; else $read = 'readonly' ;
					 $meth .='<input type="text" class="input-sm form-control" name="fee['.$fee['feeID'].']" value="'.number_format($fee['feevalue'],2).'" '.$read.'/>';	
					 $meth .= '<input type="hidden" name="feename['.$fee['feeID'].']" value="'.$fee['feename'].' ">';
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
				
			$ret .= $this->table->generate();					
		}
		
		return $ret;
	}
	
	function updatefees(){
		header("content-type:application/json");
		$this->Loansmodel->updateloanfees($_POST);
		echo json_encode(true);		
	}
	
	function remarks(){
		header("content-type:application/json");
		$loanid = $_POST['loanid'];	
			if(isset($loanid)){
				$this->form_validation->set_rules("pn_remarks", "PN Remarks", "required|xss_clean");
				if($this->form_validation->run() !== FALSE){
					$data = array("PN_remarks"=>$_POST['pn_remarks'],
											"dateModified"=>$this->auth->localtime());
					$where = array("loanID"=>$loanid);
					$table = 'loanapplication';
					$this->Loansmodel->update_data($table, $where, $data);
					$msg['stat']=true;
					$msg['data'] = "PN Remarks was changed.";
				}else{
					$msg['stat']=false;
					$msg['data'] = validation_errors();
				}
				
			}else{
				$msg['stat']=false;
					$msg['data'] = "Error. Please try again.";
			}
		echo json_encode($msg);
	}
	
	function cireport(){	
		header("content-type:application/json");
		$loanid = $_POST['loanid'];	
			if(isset($loanid)){
				foreach($_POST['ci'] as $ciid=>$value){
					$data = array("loanid"=>$loanid,
								"ci_id"=>$ciid);
					if($this->Loansmodel->fieldIn("ci_details",$data) == true)
					{
						//update ci_details	
						$cidetails = array(
									"value"=>$value,
									"dateModified"=>$this->auth->localtime(),
									"ModifiedBy"=>$this->auth->user_id());
						$where = $data;
						$this->Loansmodel->updatecireport($cidetails, $where);
					}else{
						//add ci_details
						$cidetails = array("loanid"=>$loanid,
									"ci_id"=>$ciid,
									"value"=>$value,
									"dateAdded"=>$this->auth->localtime(),
									"addedBy"=>$this->auth->user_id(),
									"active"=>1);
						$this->Loansmodel->addcireport($cidetails);
					}
				}
			}	
		echo json_encode(true);
	}
	
	function comaker(){
		
		if(isset($_POST['clientid']))
			$clientid = $_POST['clientid'];
		else
			$clientid = $this->session->userdata('applicant_id');
			
			if(isset($_POST['loanid'])){
				$loanid = $_POST['loanid'];
			}elseif($this->session->userdata('loanid') != NULL){
				$loanid = $this->session->userdata('loanid');
			}else{
				$loanid = '';	
			}
						
		$comaker = element('comaker',$_POST);
		if(!empty($loanid)){
			if($this->loansetup->comaker($loanid, $clientid, $comaker)){
				$content = "Comaker Information was submitted";
				$footer = '<a href="#require"  class="btn btn-default btn-sm">Continue</a>';
			}else{
				$content = "Please check data submitted";
				$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
			}
		}else{
			$content = "You haven't entered your Loan Details yet.";
			$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		}
		echo $this->form->modal($content, $footer);
	}
	
	
	function finalsubmission(){
		header("content-type:application/json");
		$loanid = $_POST['loanid'];
		//update status for approval
		if(!empty($loanid)){
			if(isset($_POST['submit'])){
				if($_POST['submit']=='Submit for Approval')
				$status = "approval";
				else $status = 'processing';
			}else $status = 'processing';
			$this->loansetup->updateLoanStatus($status,$loanid);
			$return = true;
		}else{			
			$return = 'Complete the Loan Application process before you proceed.';
			$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		}
		echo json_encode($return);
	}
	
	function submitforapproval(){
		$loanid=$_POST['loanid'];
		if(isset($_POST['submit'])){
				if($_POST['submit']=='Submit for Approval')
				$status = "approval";
				else $status = 'processing';
			}else $status = 'processing';
		$this->loansetup->updateLoanStatus($status,$loanid);
		
		$content = 'Loan Application was submitted for approval.';
		$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		echo $this->form->modal($content, $footer);
	}
	
	function changestatus(){
		$loanid=$_POST['loanid'];
		$status = $_POST['status'];
		$this->loansetup->updateLoanStatus($status,$loanid);
		
		$content = 'Loan Application status was changed.';
		$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		echo $this->form->modal($content, $footer);
	}
	
	function subreqtoloan($loanid){
		$this->loansetup->updateSubmittedReqs($loanid);
		return true;
	}
	
	function submitrequirements(){
		
		$clientid = $this->session->userdata("applicant_id");
		$loanid = $this->session->userdata("loanid");
		if(empty($clientid) and empty($loanid))
		{
			$content = "Please complete the client and loan details before submitting the requirements.";
			$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		}else{
			if($_POST['step'] == 'requirements'){
				$this->loansetup->updateSubmittedReqs($loanid);
				$content = "Requirements was submitted.";
				$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
			}
		}
		echo $this->form->modal($content, $footer);
	}
	
	public function newloan(){
		$page = $this->page;
		$url = $this->uri->segment(4);
		if($_POST){
			
			if($_POST['submit'] == 'PersonalInfo')
			{
				if( $this->loansetup->validate_clientinfo()  != false){
					redirect(base_url().'loans/application/newloan/pensioninfo');
				}
			}else if($_POST['submit'] == 'pensioninfo'){
				if( $this->loansetup->addpensioninfo() != false)
				redirect(base_url().'loans/application/newloan/loaninfo');
			}elseif($_POST['submit'] == 'Compute Net Proceeds'){
				$this->form_validation->set_rules('pension', "Monthly Pension", "callback_money_multi|required");
				$this->form_validation->set_rules('loanapplied', "Loan Applied", "callback_money_multi|required");
				$this->form_validation->set_rules("terms", "Terms of Payment", "less_than[25]|required");
				if($this->form_validation->run() != false){
					$terms = $this->input->post("terms");
					$loanamount = $this->input->post("loanapplied");
					$pension = $this->loansetup->monthlypension();
					$monthly = $loanamount/$terms;
					$excess = 0;
					if($monthly > $pension)
					$page['errors'] = "Loan amount cannot be paid by your monthly Pension. ";
									
					$excess = $pension-$monthly;
					//interest
					IF($terms<=12)
						$int = (0.02*$terms)*$loanamount;
					else 
						$int = ((0.02*12)+(($terms-12)*0.01)) *$loanamount;
						
					//$servicefee
					$servicefee = number_format("400",2);
					
					//RFPL
					$rfpl = $loanamount/1000*1.5*$terms;
					
					//ATM
					$atm = 15*$terms;
					
					//notarial
					$notarial = 100;
					
					$totalcharges = $int+$servicefee+$rfpl+$atm+$notarial;
					$net = $loanamount - $totalcharges;
					
					$page['notarial'] = number_format($notarial,2);
					$page['totalcharges'] = number_format($totalcharges,2);
					$page['net'] = number_format($net,2);
					$page['rfpl'] = number_format($rfpl,2);
					$page['atm'] = number_format($atm,2);
					$page['monthly'] = number_format($monthly,2);
					$page['servicefee'] = $servicefee;
					$page['int'] = $int;
					$page['excess'] = number_format($excess,2);
				}
			}elseif($_POST['submit'] == 'Submit Loan Info'){
				//add to DB
			}
		}
		//vars
		$page['gen']="disabled";
		$page['peninfo']="disabled";
		$page['loaninfo']="disabled";
		$page['loanreq']="disabled";
		$page['ci']="disabled";
		$page['app']="disabled";
		$page['client'] = $this->loansetup->clientid();
		
		if(empty($url)){
			$page['gen']="active";
			$page['form'] = 'user/loans/clientinfoform';
		}elseif($url == "loaninfo"){
			$page['form'] = 'user/loans/loaninfo';		
			$page['loaninfo']="active";
			$page['gen']="";
			$page['peninfo']="";
		}elseif($url == "pensioninfo"){
			if(!isset($page['client']))
				redirect($this->newloan);
			$page['form'] = 'user/loans/pensioninfo';		
			$page['peninfo']="active";
			$page['gen']="";			
		}
		$page['main']="user/loans/loaninfoform";
		$this->load->view($page['template'], $page);		
	}
	
	public function upload(){
		$val = $this->loansetup->validate_loan();
		if($val == false)
		echo "Invalid amount. Please try again";
		else{
			echo base_url().'client/profile/'.$_POST['clientid'].'/loan/'.$val;
		}
	}
	
	public function form(){
		$page = $this->page;
		$page['main']="user/loans/clientinfoform";
		$this->load->view($page['template'], $page);		
	}
	
	public function money_multi($input, $params) {   
		@list($thousand, $decimal, $message) = explode(',', $params);
		$thousand = (empty($thousand) || $thousand === 'COMMA') ? ',' : '.';
		$decimal = (empty($decimal) || $decimal === 'DOT') ? '.' : ',';
		$message = (empty($message)) ? 'The money field is invalid' : $message;

		$regExp = "/^\s*[$]?\s*((\d+)|(\d{1,3}(\{thousand}\d{3})+)|(\d{1,3}(\{thousand}\d{3})(\{decimal}\d{3})+))(\{decimal}\d{2})?\s*$/";
		$regExp = str_replace("{thousand}", $thousand, $regExp);
		$regExp = str_replace("{decimal}", $decimal, $regExp);

		$ok = preg_match($regExp, $input);
		if(!$ok) {
			$CI =& get_instance();
			$CI->form_validation->set_message('money_multi', $message);
			return FALSE;
		}
		return TRUE;
	}
	
	function compute(){
			$this->form_validation->set_rules('pension', "Monthly Pension", "callback_money_multi|required");
			$this->form_validation->set_rules('loanapplied', "Loan Applied", "callback_money_multi|required");
			$this->form_validation->set_rules("terms", "Terms of Payment", "less_than[25]|required");
			if($this->form_validation->run() != false){
				$terms = $this->input->post("terms");
				$loanamount = $this->input->post("loanapplied");
				$pension = $this->input->post("pension");
				$monthly = $loanamount/$terms;
				if($monthly > $pension)
				$page['errors'] = "Loan amount cannot be paid by your monthly Pension. ";
								
				$excess = $pension-$monthly;
				//interest
				IF($terms<=12)
					$int = (0.02*$terms)*$loanamount;
				else 
					$int = ((0.02*12)+(($terms-12)*0.01)) *$loanamount;
					
				//$servicefee
				$servicefee = number_format("400",2);
				
				//RFPL
				$rfpl = $loanamount/1000*1.5*$terms;
				
				//ATM
				$atm = 15*$terms;
				
				//notarial
				$notarial = 100;
				
				$totalcharges = $int+$servicefee+$rfpl+$atm+$notarial;
				$net = $loanamount - $totalcharges;
				
				$page['notarial'] = number_format($notarial,2);
				$page['totalcharges'] = number_format($totalcharges,2);
				$page['net'] = number_format($net,2);
				$page['rfpl'] = number_format($rfpl,2);
				$page['atm'] = number_format($atm,2);
				$page['monthly'] = number_format($monthly,2);
				$page['servicefee'] = $servicefee;
				$page['int'] = $int;
				$page['excess'] = number_format($excess,2);
			}
	}
	
	function getCollateralForm(){
		$pid = $this->Loansmodel->getproductid($_POST['loancode'], $_POST['loanstatus'], $_POST['method']);
		$cols = $this->Products->getProCollaterals($pid, $loanid, $_POST['loancode']);
	}
	
	function action(){
		$action = $this->uri->segment(4);
		$loanid = $_POST['loanid'];
		switch ( $action ){
			case 'approve':
				$this->approveLoan();
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
			
				$pn = $this->loansetup->assignpn();
				if($pn == false){
					$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
					$content = validation_errors();
					//echo $content;
					echo $this->form->modal($content, $footer);
				}else{
					$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
					$content = "New PN was assigned.";
					//echo $content;
					echo $this->form->modal($content, $footer);
				}
			break;
			
		}		
	}
	
	function approveLoan(){
		$approve = floatval(str_replace(",","",$_POST['approvedamount'] ));
		$appliedamount = $_POST['appliedamount'];
		$principalAmount = $_POST['principalAmount'];
		$method = $_POST['method'];
		$term = $_POST['term'];
		//$exterm = ($_POST['extendedTerm'] ? $_POST['extendedTerm'] : '');
		if(isset($_POST['extendedTerm'])){
			$exterm = ($_POST['extendedTerm'] ? $_POST['extendedTerm'] : '');
			$dateStart = date("Y-m-d",strtotime($_POST['startpayment']."-1 month"));
			$schedTerm = $exterm;
		}else{ 
			$exterm = '';
			$dateStart = $this->auth->localdate();
			$schedTerm = $term;
		}
		$loanid = $_POST['loanid'];
		$pid = $_POST['pid'];
		$remarks = $_POST['remarks'];
		
		$fees = $this->Loansmodel->loanfees($_POST['pid'],$approve, $_POST['term'], $exterm);
		$net = $approve;
		
		//update loan fees
		foreach($fees['fees'] as $fee){
			if($fee['comp'] != 'fixed'){
				if($fee['feename'] == 'UID'){
					if($fee['upfront'] =='add'){
						if($approve == $principalAmount){
							$net = $approve / (1 + ($fees['interest'] /100));
							$interest = $approve - $net;
							$data = array("value"=>$interest,
									"dateModified"=>$this->auth->localtime(),
									"modifiedBy"=>$this->auth->user_id());
						}else{
							$data = array("value"=>$fee['feevalue'],
									"dateModified"=>$this->auth->localtime(),
									"modifiedBy"=>$this->auth->user_id());
						}
					}else{
						$data = array("value"=>$fee['feevalue'],
									"dateModified"=>$this->auth->localtime(),
									"modifiedBy"=>$this->auth->user_id());
					}
				}else{
					$data = array("value"=>$fee['feevalue'],
									"dateModified"=>$this->auth->localtime(),
									"modifiedBy"=>$this->auth->user_id());
				}
				
				$where = array("feeID"=>$fee['feeID'],
									"loanID"=>$loanid);
				
				$table = 'loanfees';
				$this->Loansmodel->update_data($table, $where, $data);
			}
		}
		
		//update loan information
		
		//exit();
		header("content-type:application/json");
		
		$net = $approve;
		//if($approve ==  $principalAmount){
			//update status
			//$matdate = $this->auth->localdate()."+".$term." month"; "MaturityDate"=>date("Y-m-d", strtotime($matdate))
			if($method == 'M')
			$monthly = $approve/$schedTerm;
			else $monthly = $approve;
			$pn = $this->loansetup->generatePN($loanid);
			$f =  $this->Loansmodel->getLoanFees($loanid);
			$total = 0;
			
			if($f->num_rows() > 0){
				foreach($f->result() as $fee) {
					$fe=  floatval(str_replace(",","",$fee->value));
					if($fee->upfront == 'deduct' or $fee->upfront != 'add')
						$net -= $fe;
					if($fee->upfront =='add'){
						if($approve == $principalAmount){
							$netpro = $approve / (1 + ($fees['interest'] /100));
							$net -= ($approve - $netpro);
						}else{
							$net -= $fe;
						}
					}
				}
				//$net = $approve - $total;
			}
			
			$data = array("status"=>"approved",
								"approvedamount"=>$approve,
								"MonthlyInstallment"=>round($monthly,2),
								"PNno"=>$pn,
								"extension"=>$exterm,
								"interest"=>$fees['interest'],
								"remarks"=>$remarks,
								"netproceeds"=>$net,
								"Term"=>$term,
								"dateApproved"=>$this->auth->localtime(),
								"approvedBy"=>$this->auth->user_id()
								);
			$where = array("loanid"=>$loanid);
			$this->Loansmodel->update_data("loanapplication", $where, $data);
			
			//update loan schedule
			$this->loansetup->update_loanschedule($schedTerm, $approve, $dateStart, $loanid, $method);
			
			$msg['msg'] = "Loan is approved. No changes to be made";
			echo json_encode($msg);
		//}else{
			//$msg['msg'] = "Changed Approved Amount is under process. Please try again later. ";
			//echo json_encode($msg);
		//}		
	}
	
	function extendloan(){
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */