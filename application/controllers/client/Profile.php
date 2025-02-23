<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
		public $page = array ( "pagetitle" => "Client Profile",
							"nav" => 'final/navheader',
							//"template" => 'final/template',
							"template" => 'template/new/body',
							"menu" => 'final/sidemenu',
							"module" => "Header.Clients"
							
							);
	function __construct()
	{
	  parent::__construct();
	  $this->load->helper('security');	
	  $this->auth->restrict();
	}
	
	function index(){
		$page = $this->page;		
		$page['header'] = $this->UserMgmt->getheader();
		$page['name'] = $this->session->userdata('searchname');
		$page['main'] = 'cash/overview';
		$page['submod'] = 'Profile';
		$clientid = $this->uri->segment(3);
		$page['clientid']=$clientid;
		$client = $this->Clientmgmt->getclientinfoByID($clientid);
		$page['client']=$client;
		
		$page['spouse'] =  $this->Clientmgmt->getspouse($clientid);
		$page['dependents'] =  $this->Clientmgmt->getdependents($clientid);
		$page['creditor'] =  $this->Clientmgmt->getcreditor($clientid);
		$page['emp'] = $this->Clientmgmt->getEmployer($clientid);
		$page['incomeexpense'] = $this->Clientmgmt->getIncomeExpense($clientid);	
		$page['pension'] = 'loans/pensioninfo';
		$page['loaninfo'] = "client/loaninfo";
		$page['due'] = "client/due";
		$page['branchid'] = $this->auth->branch_id();
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branchid']);
		
		//$page['p'] = $p;
		
		$page['main'] = 'client/clientdetails';
		$page['content'] = 'client/clientinfo';
		$this->load->view($page['template'], $page);
	}	
	

	public function getinfo(){
		$where = "firstName like '%".$_GET['term']."%' or ".
				"LastName like '%".$_GET['term']."%'";
		$client = $this->Loansmodel->get_data_from('clientinfo', $where);
		
		if($client->num_rows() > 0){		
			foreach ($client->result() as $c){				
				//echo $c->LastName;
				$data[] = array('value'=> $c->LastName.", ".$c->firstName,
                 'label'=>$c->LastName.", ".$c->firstName,
				 'clientid' => $c->ClientID);				
			}	
			
		}
		$data[] = array('value'=> "Add New Client",
                 'label'=>"Add New Client",
				 'clientid' => 0,
				 'load' => 'client/addnew' );	
		echo json_encode($data);
	}
	
	function updateinfo(){
		
		if(isset($_POST['info'])){
			
			if($_POST['info'] == 'spouse'){
				if($this->loansetup->validate_spouse() == false){
					$footer = '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#spouseinfo" data-dismiss="modal">Back '.$_POST['info'].'</button>';
					echo $this->form->modal(validation_errors(), $footer);				
				}else{					
					$table = "spouseinfo";
					$where = array("clientID"=>$_POST['client']);
					if($this->Loansmodel->get_data_from($table, $where)->num_rows() > 0){
						$sp = array("firstname" => $_POST['spfirstname'],
							"middlename" => $_POST['spmname'],
							"lastname" => $_POST['splname'],
							"dateOfBirth" => $_POST['spbdate'],
							"occupation" => $_POST['spwork'],
							"companyname" => $_POST['spcompany'],
							"salary" => $_POST['spsalary'],
							"contact" => $_POST['spcontact'],
							"dateModified" => $this->auth->localtime(),
							"modifiedBy" => $this->auth->user_id(),
							"active"=> 1);					
						if($this->Loansmodel->update_data($table, $where, $sp) == true){
							$clientprofile = base_url() . "client/profile/".$_POST['client'];
							$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
							$content = '<div class="alert alert-success">Spouse Information was updated.</div>';
							echo $this->form->modal($content, $footer);
						}else{
							$footer = '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#spouseinfo" data-dismiss="modal">Back</button>';
							$content = '<div class="alert alert-warning">Something went wrong. Please try again.</div>';
							echo $this->form->modal($content, $footer);
						}
					}else{
						$sp = array("firstname" => $_POST['spfirstname'],
							"middlename" => $_POST['spmname'],
							"lastname" => $_POST['splname'],
							"dateOfBirth" => $_POST['spbdate'],
							"occupation" => $_POST['spwork'],
							"companyname" => $_POST['spcompany'],
							"salary" => $_POST['spsalary'],
							"contact" => $_POST['spcontact'],
							"dateAdded" => $this->auth->localtime(),
							"addedBY" => $this->auth->user_id(),
							"clientID"=>$_POST['client'],
							"active"=> 1);
						if($this->Loansmodel->addtotable($table, $sp) != false){
							$clientprofile = base_url() . "client/profile/".$_POST['client'];
							$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
							$content = '<div class="alert alert-success">Spouse Information was updated.</div>';
							echo $this->form->modal($content, $footer);
						}else{
							$footer = '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#spouseinfo" data-dismiss="modal">Back</button>';
							$content = '<div class="alert alert-warning">Something went wrong. Please try again.</div>';
							echo $this->form->modal($content, $footer);
						}
					
					}
				}
			}elseif($_POST['info'] == 'dependents'){				
				$this->loansetup->add_dependents($_POST['client']);
				$this->loansetup->update_dependents();
				$clientprofile = base_url() . "client/profile/".$_POST['client'];
				$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
				$content = '<div class="alert alert-success">Dependents Information was updated.</div>';
				echo $this->form->modal($content, $footer);
			}elseif($_POST['info'] == 'credit'){				
				$cre =  $this->loansetup->add_creditor($_POST['client']);
				$this->loansetup->update_creditor();
				$clientprofile = base_url() . "client/profile/".$_POST['client'];
				$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
				if($cre == true)
				$content = '<div class="alert alert-success">Creditors Information was updated.</div>';
				else $content = '<div class="alert alert-success">Creditors Information was not updated.</div>';
				echo $this->form->modal($content, $footer);
			}elseif($_POST['info'] == 'image'){			
			
				$config['upload_path'] = './assets/img/clients/';
				//var_dump($config['upload_path']); 
				$config['allowed_types'] = '*';
				$config['max_size']	= '100';
				$config['max_width'] = '1024';
				$config['max_height'] = '768';
				$this->load->library('image_lib');
				$this->load->library('upload', $config);
				// Alternately you can set preferences by calling the initialize function. Useful if you auto-load the class:
				//$this->upload->initialize($config);

				if ( ! $this->upload->do_upload('userfile'))
				{
					$p = array('error' => $this->upload->display_errors());
					$content = $this->upload->display_errors();
				}
				else
				{
					$image=$this->upload->data();
					$content = $config['upload_path'].$image['file_name'];
					
					$img = array("image" => $content,
								"dateModified" => $this->auth->localtime(),
								"modifiedBy" => $this->auth->user_id());
					$where = array("ClientID"=>$_POST['client']);	
					$table = 'clientinfo';				
					if($this->Loansmodel->update_data($table, $where, $img) == true){
						$content = '<img src="'.$content.'" width="200px">';
					}else $content = 'Image was not stored to client\'s record.';
					
				}
			
				$clientprofile = base_url() . "client/profile/".$_POST['client'];
				$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';				
				echo $this->form->modal($content, $footer);
			}
		}else{
			
			if($this->loansetup->validation_client() == true){
				if($_POST['bdate'] == '0000-00-00')
				$error[] = "Birthday is invalid.";
					if(!isset($error)){			
						$clientid = $_POST['client'];
						if($this->loansetup->update_clientinfo($clientid) == true){
							$clientprofile = base_url() . "client/profile/".$_POST['client'];
							$footer = '<a href="'.$clientprofile.'" class="btn btn-default btn-sm">Ok</a>';
							$content = '<div class="alert alert-success">Client Information was updated.</div>';
							echo $this->form->modal($content, $footer);						
						}else{
							$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
							$content = '<div class="alert alert-danger">Failed to Update client info. Please try again.</div>';
							echo $this->form->modal($content, $footer);							
						}
					
					}else{
						foreach($error as $e){
							echo $e."<br/>";
						}
					}	
				//update_data($table, $id, $data)
			}else{					
				$footer = '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#personalinfo" data-dismiss="modal">Back</button>';
				$content = validation_errors();
				echo $this->form->modal($content, $footer);
			}
		}
		
	}
	
	
	function loan_old(){
		$page = $this->page;
		$page['header'] = $this->UserMgmt->getheader();
		$page['main']="client/applicationflow";	
		$page['clientid'] = $this->uri->segment(3);
		$page['loanid'] = $this->uri->segment(5);
		$clientid = $page['clientid'];
		if($clientid != ''){
			$page['client'] = $this->Clientmgmt->getclientinfoByID($clientid);	
			$loans = $this->Loansmodel->getLoanbyID($page['loanid']);
			$page['loans'] = $loans;
			
			$page['loantype'] = $loans->row()->LoanCode; 	
		}
	
		$this->load->view($page['template'], $page);
	}
	
	function loan(){
		$page = $this->page;		
		$page['module'] = 'Header.Loans';
		$page['header'] = $this->UserMgmt->getheader();
		$page['clientid'] = $this->uri->segment(3);
		$page['loanid'] = $this->uri->segment(5);
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($this->auth->branch_id());
		$loanid = $page['loanid'];
		
		if(empty($page['clientid']) or empty($page['loanid'] )){
			
			redirect(base_url()."client");
			
		}else{
			
			//Loan info
			//$page['loans'] = $this->Loansmodel->getLoanbyID($loanid);
			//Collateral info
			$page['loans'] = $this->Loansmodel->getLoanDetails($loanid);
			
		}
		
		$page['main'] = 'loans/loandetails';
		
			
		$this->load->view($page['template'], $page);
	}
	
	function loan_old_march2015(){
		$page = $this->page;		
		$page['header'] = $this->UserMgmt->getheader();
		$page['name'] = $this->session->userdata('searchname');
		$page['branchid'] = $this->auth->branch_id();
		//$page['module'] = "Loan Details";
		$page['submod'] = "Loan Details";
		
		$page['clientid'] = $this->uri->segment(3);
		$page['loanid'] = $this->uri->segment(5);
		
		if(empty($page['clientid']) or empty($page['loanid'] )){
			redirect(base_url()."client");
		}
		$loanid = $page['loanid'];
		$page['ci'] = $this->Products->getcireport($loanid);
		$loans = $this->Loansmodel->getLoanbyID($loanid);
		$page['loans'] = $loans;
		$tmpl = array ('table_open'          => '<table class="table  table-condensed table-hover " >',
				'thead_open' => '<thead class="header">'	); 
					$this->table->set_template($tmpl); 
		if($loans->num_rows() > 0){
			foreach($loans->result() as $loan){
				$page['loanname'] = $loan->LoanName;
				$page['loantype'] = $loan->LoanType;
				$page['pn'] = $loan->PNno;
				$page['amount'] = $loan->AmountApplied;
				$page['approved'] = $loan->approvedAmount;
				$page['status'] = $loan->status;
				$page['pensionid'] = $loan->pensionID;
				$page['terms'] = $loan->Term ;
				$amount = $page['amount'];
				$terms = $page['terms'];
				$status =$page['status'];
				$approved = $page['approved'];
				$loantype = $page['loantype'];
				$monthy = number_format($amount/$terms,2);
				$applied = $loan->dateApplied;
				$agent = $this->UserMgmt->get_user_byid($loan->LoanProcessor);
				$page['agent'] = $agent;
				if($agent->num_rows() > 0 ){
				$a = $agent->row();
				$ag= $a->lastname.", ".$a->firstname;
				}
			}
		}else{
			$page['loanname'] = $loan->LoanName;
				$page['loantype'] = $loan->LoanType;
				$page['pn'] = $loan->PNno;
				$page['amount'] = $loan->AmountApplied;
				$page['approved'] = $loan->approvedAmount;
				$page['status'] = $loan->status;
				$page['pensionid'] = $loan->pensionID;
				$page['terms'] = $loan->Term ;
				$amount = $page['amount'];
				$terms = $page['terms'];
				$status =$page['status'];
				$approved = $page['approved'];
				$loantype = $page['loantype'];
				$monthy = number_format($amount/$terms,2);
				$applied = $loan->dateApplied;
				$agent = '';
				$page['agent'] = $agent;				
				if($agent->num_rows() > 0 ){
				$a = $agent->row();
				$ag= '';
			}
		}
			$clientid = $page['clientid'];
			$amount = ($approved ? $approved : $amount);
			$req = $this->loansetup->requirements($loanid, $loantype); 
			
			if($status =='processing'){
				$complete = $req['complete'];
				if(in_array("0",$complete) == true){
					$reqcom = false;
					$approve = "disabled";	
				}else{
					//update status to ci
					$this->loansetup->updateLoanStatus('CI', $loanid);
					$reqcom = true;
					$approve = "";	
				}
			}elseif($status == 'approval'){
				$approve = "";	
			}
			
			//get loaninfo
			$product = $this->Loansmodel->getproductsbyID($loantype);
			if($product->num_rows() > 0){
				foreach ($product->result() as $pro){
					$pcode = $pro->LoanCode;
					$page['pname'] = $pro->LoanName;
					$pdesc = $pro->LoanDescription;
					$minA = $pro->minAmount;
					$maxA = $pro->maxAmount;
					$minT = $pro->minTerm;
					$maxT = $pro->maxTerm;
					$penalty = $pro->penalty;
				}
			}
			
			//client info 
			/*$client = $this->Clientmgmt->getclientinfoByID($clientid);
			$page['client'] = $client;
			if($client->num_rows() > 0){

				foreach($client->result() as $c){
					$p['firstname'] = $c->firstName;
					$p['mname'] = $c->MiddleName;
					$p['lname'] = $c->LastName;
					$p['dob'] = $c->dateOfBirth;
					$p['city'] = $c->city;
					$p['address']=$c->address;
					$p['contact'] = $c->contact;
					$p['civilstatus'] = $c->civilStatus;
					$p['city'] = $c->cityname;
					$p['cityid'] = $c->city;
					$p['provid'] = $c->province;
					$p['barangay'] = $c->barangay;
					$p['address'] = $c->address;
					$p['gender'] = $c->gender;
					$p['age'] = $this->loansetup->get_age($p['dob']);
					if($c->dateOfBirth == '0000-00-00')
					$p['alert'] = "Please update client's birthday.";

					switch (strtolower($p['gender'])) {
					case 'f':
						$g = "Female";
						break;
					case "m": // never reached because "a" is already matched with 0
						$g = "Male";
						break;
					default:
						$g = "-";
					}
					
					$profileurl = base_url()."client/profile/".$clientid;
				}
				$page['p'] = $p;
			}
			*/
			$datedisbursed = $loan->DateDisbursed ;
			$dateapproved = $loan->dateApproved ;
			if($datedisbursed == '0000-00-00 00:00:00')
			$datedisbursed= "-";
			else
			$datedisbursed = date("F d, Y", strtotime($datedisbursed));
			if($dateapproved == '0000-00-00 00:00:00')
			$dateapproved= "-";
			else
			$dateapproved = date("F d, Y", strtotime($dateapproved));
			$page['comp'] = $this->loansetup->loancomputation($amount,$terms,$loantype, $loanid);		
		
		$page['main'] = 'loans/loandetails';
		
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branchid']);
		//$page['main'] = 'cash/overview';
		
		$this->load->view($page['template'], $page);
	}
	
	function pension(){
		$page = $this->page;		
		$page['header'] = $this->UserMgmt->getheader();
		$page['name'] = $this->session->userdata('searchname');		
		$page['main'] = 'cash/overview';
		$clientid = $this->uri->segment(3);
		$page['clientid']=$clientid;
		$client = $this->Clientmgmt->getclientinfoByID($clientid);
		$page['client']=$client;
		$page['pensionid'] = $this->uri->segment(5);
		$page['pension']=$this->Loansmodel->get_pensioninfo($page['pensionid']);
		$page['subcontent'] = 'loans/pensiondetails';
		$page['branchid'] = $this->auth->branch_id();
		$page['banks'] = $this->Cashmodel->getbanklistonbranch($page['branchid']);
		//$page['content'] = 'loans/pensioninfo';
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
	
	function addpension(){
		if($_POST){
			if($this->loansetup->validate_pension() == false){
				echo '<div class="modal-dialog ">';
				echo '<div class="modal-content ">';
				echo '<div class="modal-body ">';
				echo '<div class="alert alert-danger">Please complete required fields.</div>';
				echo validation_errors();
				echo '</div>';
				echo '<div class="modal-footer ">';
				echo '<input type="button" class="btn btn-sm btn-default" id="backadjust"  data-toggle="modal" data-dismiss="modal" data-target="#pension"  value="Back">';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}else{
				if($this->loansetup->addpensioninfo() == false){
					echo '<div class="modal-dialog ">';
					echo '<div class="modal-content ">';
					echo '<div class="modal-body ">';
					echo '<div class="alert alert-danger">failed to add pension</div>';
					echo '</div>';
					echo '<div class="modal-footer ">';
					echo '<input type="button" class="btn btn-sm btn-default" id="backadjust"  data-toggle="modal" data-dismiss="modal" data-target="#pension"  value="Back">';
					echo '</div>';
					echo '</div>';
					echo '</div>';				
				}else{
					echo '<div class="modal-dialog ">';
					echo '<div class="modal-content ">';
					echo '<div class="modal-body ">';
					echo '<div class="alert alert-success">New Pension was added.</div>';
					echo '</div>';
					echo '<div class="modal-footer ">';
					echo '<a class="btn btn-sm btn-success" href="">Continue</a>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
			}
		}
	}
	
	
	function lock(){
		
		$cid = $_GET['cid'];
		$lock = $_GET['lock'];
		
	}
}
?>