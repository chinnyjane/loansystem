<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Loansetup {

	function __construct(){
		$this->ci =& get_instance();
		$this->ip_address = $this->ci->input->ip_address();
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}
		if (!class_exists('LoansModel'))
		{
			$this->ci->load->model('loansmodel', 'loansmodel', TRUE);
		}
	}

	function addloanproduct(){
		$this->ci->form_validation->set_rules('pcode', "Product Code", "required");
		$this->ci->form_validation->set_rules('pname', "Product Name", "required");
		$this->ci->form_validation->set_rules('pdesc', "Product Description", "required");
		if($this->ci->form_validation->run() === FALSE)
		return "return";
		else{
			$this->ci->LoansModel->add_products($_POST['pcode'],$_POST['pname'],$_POST['pdesc']);
			return "continue";
		}
	}
	
	function financeinfo($clientid){
		$this->ci->db->trans_begin();
		//$clientid = $this->ci->session->userdata('applicant_id');
		
		//validate Employment Data
		$valemp = $this->validate_emp();
		
		//validate income data
		$valin = $this->validate_income();
		
		//validate expense data
		$valex =$this->validate_expense();	
		
		if( $valemp!= false or $valin!= false or $valex!=false){
			//echo 'save to db';
			$empID = $this->ci->Loansmodel->addEmployer($_POST['emp'], $clientid);
			$income = $this->addincome($clientid);
			$expense = $this->addexpenses($clientid);
			$this->removefinance();
			$this->add_dependents($clientid);
			$this->add_creditor($clientid);
			
			if ($this->ci->db->trans_status() === FALSE)
			{	
				$this->ci->db->trans_rollback();
				return false;			
			}else{
				$this->ci->db->trans_commit();
				return true;
			}
		}else return false;
		
		
	}
	
	
	function addpersonalinfo(){
		
		
		//===== VALIDATE DATA FROM HERE ==================//
		
		//validate Client Info
		$valclient = $this->validation_client();
		
		if($this->ci->input->post('civilstatus') != 'single' ) 
		$this->validate_spouse();
	
		//=======Validation ends here ==============//		
		
		if($valclient!=false ){		
	
			$this->ci->db->trans_begin();
			
			//Add details to DB
			
			$clientid =  $this->addclientinfo();
			$this->financeinfo($clientid);
			
			//$this->ci->db->trans_complete();
					
			if ($this->ci->db->trans_status() === FALSE)
			{
				$this->ci->db->trans_rollback();
				echo 'may sala ka';
				return false;			
			}else{
				$this->ci->db->trans_commit();
				return $clientid;
			}
			
		}else{
			//echo validation_errors();
			return false;
		}
 	
	}
	
	function validate_emp(){
		$this->ci->form_validation->set_rules("emp[employer]", "Employer", "xss_clean");
		$this->ci->form_validation->set_rules("emp[address]", "Address", "xss_clean");
		$this->ci->form_validation->set_rules("emp[nature]", "Nature", "xss_clean");
		$this->ci->form_validation->set_rules("emp[contact]", "Contact", "xss_clean");
		$this->ci->form_validation->set_rules("emp[position]", "position", "xss_clean");
		$this->ci->form_validation->set_rules("emp[length]", "length", "xss_clean");
		$this->ci->form_validation->set_rules("emp[status]", "status", "xss_clean");
		$this->ci->form_validation->set_rules("emp[salary]", "salary", "xss_clean");
		
		if($this->ci->form_validation->run() == False){		
			return false;
		}else{
			return true;
		}
	}
	
	function addincome($clientID){
		if(isset($_POST['income'])){
			$ex = $_POST['income']['nature'];
			$val = $_POST['income']['value'];
			if(isset($id))
			$id = $_POST['income']['id'];
			$table = 'income_expense';
			
			if(count($ex) > 0){				
				foreach( $ex as $key=>$value){
					
					if($value !='' and $val[$key] != ''){
						if(isset($id[$key])){
							$pars = array("id"=>$id[$key]);
							$data = array( "nature"=>$value,
										"value"=>$val[$key],																
										"dateModified"=>$this->ci->auth->localtime(),
										"modifiedBy"=>$this->ci->auth->user_id());	
							$this->ci->Loansmodel->update_data($table, $pars, $data);
						}else{
							$pars = array("type"=>'income',
										"nature"=>$value,
										"clientID"=>$clientID,
										"active"=>1);
							if($this->ci->Loansmodel->fieldIn($table,$pars)==true){
								$data = array(
										"value"=>$val[$key],																
										"dateModified"=>$this->ci->auth->localtime(),
										"modifiedBy"=>$this->ci->auth->user_id());	
								$this->ci->Loansmodel->update_data($table, $pars, $data);
							}else{
								$data = array("type"=>'income',
										"nature"=>$value,
										"value"=>$val[$key],
										"clientID"=>$clientID,
										"active"=>1,
										"dateAdded"=>$this->ci->auth->localtime(),
										"addedBy"=>$this->ci->auth->user_id());	
								$this->ci->Loansmodel->addtotable('income_expense', $data);	
							}
						}
					}
				}		
				
			}		
		
			
		}	
	}
	
	function addexpenses($clientID){
		if(isset($_POST['expense'])){
			$ex = $_POST['expense']['nature'];
			$val = $_POST['expense']['value'];
			if(isset($id))
			$id = $_POST['expense']['id'];
		
			$table = 'income_expense';
			
				
			if(count($ex) > 0){				
				foreach( $ex as $key=>$value){
					
					if($value !='' and $val[$key] != ''){
						if(isset($id[$key])){
							$pars = array("id"=>$id[$key]);
							$data = array( "nature"=>$value,
										"value"=>$val[$key],																
										"dateModified"=>$this->ci->auth->localtime(),
										"modifiedBy"=>$this->ci->auth->user_id());	
							$this->ci->Loansmodel->update_data($table, $pars, $data);
						}else{
							$pars = array("type"=>'expense',
										"nature"=>$value,
										"clientID"=>$clientID,
										"active"=>1);
							if($this->ci->Loansmodel->fieldIn($table,$pars)==true){
								$data = array(
										"value"=>$val[$key],																
										"dateModified"=>$this->ci->auth->localtime(),
										"modifiedBy"=>$this->ci->auth->user_id());	
								$this->ci->Loansmodel->update_data($table, $pars, $data);
							}else{
								$data = array("type"=>'expense',
										"nature"=>$value,
										"value"=>$val[$key],
										"clientID"=>$clientID,
										"active"=>1,
										"dateAdded"=>$this->ci->auth->localtime(),
										"addedBy"=>$this->ci->auth->user_id());	
								$this->ci->Loansmodel->addtotable('income_expense', $data);	
							}
						}
					}
				}		
				
			}
		}	
	}
	
	function removefinance(){
		$table = 'income_expense';
			if(isset($_POST['remove'])){
				foreach($_POST['remove'] as $iid=>$act){
					if($act == 1){
						$data = array('active'=>0,
											'dateModified'=>$this->ci->auth->localtime(),
											'modifiedBy'=>$this->ci->auth->user_id());
						$where = array("id"=>$iid);
						$this->ci->Loansmodel->update_data($table, $where, $data);					
					}
				}
			}
	}
	
	
	function validate_income(){
		$this->ci->form_validation->set_rules('income[nature]',"Nature of Income", "alpha_dash|xss_clean");
		$this->ci->form_validation->set_rules('income[value]',"Income Value", "is_numeric|xss_clean");		
		
		if($this->ci->form_validation->run() == False){		
			return false;
		}else{
			return true;
		}
		
	}
	
	function validate_expense(){
		$this->ci->form_validation->set_rules('expense[nature]',"Nature of Expenses", "alpha_dash|xss_clean");
		$this->ci->form_validation->set_rules('expense[value]',"Expenses Value", "is_numeric|xss_clean");	
		
		if($this->ci->form_validation->run() == False){		
			return false;
		}else{
			return true;
		}
		
	}
	
	function validation_client(){
	
		//PERSONAL INFO
		$this->ci->form_validation->set_rules("firstname", "First Name", "required");
		$this->ci->form_validation->set_rules("mname", "Middle Name", "required");
		$this->ci->form_validation->set_rules("lname", "Last Name", "required|callback_name_exist");
		$this->ci->form_validation->set_rules("contact", "Client's Contact #", "required|is_numeric");
		$this->ci->form_validation->set_rules("city", "City", "required");
		$this->ci->form_validation->set_rules("brgy", "Barangay", "required");
		$this->ci->form_validation->set_rules("address", "Address", "required");
		$this->ci->form_validation->set_rules("address", "Address", "required");
		$this->ci->form_validation->set_rules("bdate", "Date of Birth", "required");
		
			
		if($this->ci->form_validation->run() == False){		
			return false;
		}else{
			return true;
		}
	}
	
	function validate_spouse(){
		$this->ci->form_validation->set_rules('spfirstname', "Spouse first name", "required|xss_clean");
		$this->ci->form_validation->set_rules('spmname', "Spouse middle name", "required|xss_clean");
		$this->ci->form_validation->set_rules('splname', "Spouse last name", "required|xss_clean");
		$this->ci->form_validation->set_rules('spwork', "Spouse Occupation", "xss_clean");
		$this->ci->form_validation->set_rules('spsalary', "Spouse Salary", "xss_clean");
		$this->ci->form_validation->set_rules('spcompany', "Spouse Company", "xss_clean");
		$this->ci->form_validation->set_rules('spcontact', "Spouse Contact", "is_numeric|xss_clean");
		$this->ci->form_validation->set_rules('spbdate', "Spouse Date of Birth", "xss_clean");
		if($this->ci->form_validation->run() == False){		
			return false;
		}else{
			return true;
		}
	}
	
	function addclientinfo(){		
	
		//add to db client information		
		$data = array("firstName" => $_POST['firstname'],
					"MiddleName" => $_POST['mname'],
					"LastName" => $_POST['lname'],
					"dateOfBirth" => date("Y-m-d", strtotime($_POST['bdate'])),
					"address" => $_POST['address'],
					"barangay" => $_POST['brgy'],
					"contact" => $_POST['contact'],
					"city" => $_POST['city'],
					"civilStatus" => $_POST['civilstatus'],
					"gender" => $_POST['gender'],
					"dateAdded" => $this->ci->auth->localtime(),
					"branchID" => $this->ci->auth->branch_id(),
					"addedBy" => $this->ci->auth->user_id(),
					"active"=>1);
					
		$clientid = $this->ci->Loansmodel->add_client($data);
		
		
		$hash = md5(substr($this->ci->auth->localtime(), 0, 10).$this->ci->auth->user_id().$clientid);
		$id = array("clientID"=>$clientid);
		$hasharray = array("inlineHash"=>$hash);
		$this->ci->Loansmodel->update_inlinehash('clientinfo', $id,$hasharray);
		
		//add spouse info
		if($this->ci->input->post('civilstatus') != "single"){
			$sp = array("clientID"=>$clientid,
					"firstname" => $_POST['spfirstname'],
					"middlename" => $_POST['spmname'],
					"lastname" => $_POST['splname'],
					"dateOfBirth" => $_POST['spbdate'],
					"occupation" => $_POST['spwork'],
					"companyname" => $_POST['spcompany'],
					"salary" => $_POST['spsalary'],
					"contact" => $_POST['spcontact'],
					"dateAdded" => $this->ci->auth->localtime(),
					"addedBy" => $this->ci->auth->user_id(),
					"active"=>1);
			$this->ci->Loansmodel->addtotable('spouseinfo', $sp);
		}
		//$this->add_dependents($clientid);
		//$this->add_creditor($clientid);
		
		if($clientid)
			return $clientid;
		else 
			return false;
	}
	
	function update_clientinfo($clientid){
		
		if(isset($_POST['active'])) $active = $_POST['active'];
		else $active =1;
		
		$data =  array('firstName'=>$_POST['firstname'],
										'MiddleName'=>$_POST['mname'],
										'LastName'=>$_POST['lname'],
										'dateOfBirth'=>date('Y-m-d',strtotime($_POST['bdate'])),
										'address'=>$_POST['address'],
										'barangay'=>$_POST['brgy'],
										'id_presented'=>$_POST['id_presented'],
										'city'=>$_POST['city'],
										'contact'=>$_POST['contact'],
										'civilStatus'=>$_POST['civilstatus'],
										'active'=>$active,
										'gender'=>$_POST['gender']);
		$table="clientinfo";
		$id = array ('clientID'=> $clientid);
		if($this->ci->Loansmodel->update_data($table, $id, $data) == true)
		return true;
		else
		return false;
		
	}
	
	
	function add_dependents($clientid){
	
		if(isset($_POST['depfname'])){
			//echo 'ok';
		
			if(count($_POST['depfname']) > 0){
			// echo 'ok2';
				$data = array();
				$fname = $_POST['depfname'];
				$mname = $_POST['depmname'];
				$lname = $_POST['deplname'];
				$bday = $_POST['depbday'];
				
				foreach($fname as $key=>$value){
				//echo $key;
				//echo $value.'ss';
					if($value != ''){
						$data[] = array("firstname"=>$value,
									"middlename"=>$mname[$key],
									"lastname"=>$lname[$key],
									"dateOfBirth"=>$bday[$key],
									"clientID"=>$clientid,
									"dateAdded"=>$this->ci->auth->localtime(),
									"addedBy"=>$this->ci->auth->user_id(),
									"active"=>1);
					}
				}
				
				if(count($data) > 0){
					if($this->ci->db->insert_batch('dependents', $data))
						return true;
					else 
						return false;	
				}	else{
					return false;
				}
			}
		}		
	}
	
	function update_dependents(){
	
		if(isset($_POST['dfname'])){
		
			if(count($_POST['dfname']) > 0 ){
			
				$data = array();
				$fname = $_POST['dfname'];
				$mname = $_POST['dmname'];
				$lname = $_POST['dlname'];
				$bday = $_POST['dbday'];
				
				foreach($fname as $key=>$value){
					if($value != ''){
						$data = array("firstname"=>$value,
									"middlename"=>$mname[$key],
									"lastname"=>$lname[$key],
									"dateOfBirth"=>$bday[$key],
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id());	
						$where = array("depID"=>$key);
						$this->ci->Loansmodel->update_data("dependents", $where, $data);
					}
				}
				
				if(isset($_POST['depid'])){
					if(count($_POST['depid']) > 0){
						foreach($_POST['depid'] as $depid){
							$data = array("active"=>0,
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id());	
							$where = array("depID"=>$depid);
							$this->ci->Loansmodel->update_data("dependents", $where, $data);
						}
					}
				}
			}
		}		
	}
	
	function add_creditor($clientid){
	
		if(isset($_POST['creditor'])){
		
			if(count($_POST['creditor']) > 0){
				$data = array();
				$name = $_POST['creditor'];
				$address = $_POST['creditadd'];
				$amount = $_POST['creditamount'];
				$remarks = $_POST['remarks'];
				
				foreach($name as $key=>$value){
					if($value != ''){
					$data[] = array("clientID"=>$clientid,
									"name"=>$value,
									"address"=>$address[$key],
									"amount"=>$amount[$key],
									"remarks"=>$remarks[$key],
									"dateAdded"=>$this->ci->auth->localtime(),
									"addedBy"=>$this->ci->auth->user_id(),
									"active"=>1);
					}
				}
				if(count($data) > 0){
					if($this->ci->db->insert_batch('creditobligations', $data))
						return true;
					else 
						return false;
				}else{
					return false;
				}
			}else return false;
			
		}else return false;
	
	}	
	
	function update_creditor(){
	
		if(isset($_POST['credtor'])){
		
			if(count($_POST['credtor']) > 0){
				$data = array();
				$name = $_POST['credtor'];
				$address = $_POST['credadd'];
				$amount = $_POST['credamount'];
				$remarks = $_POST['credremarks'];
				
				foreach($name as $key=>$value){
					
					$data = array("name"=>$value,
									"address"=>$address[$key],
									"amount"=>$amount[$key],
									"remarks"=>$remarks[$key],
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id());		
					$where = array("creditID"=>$key);
					$this->ci->Loansmodel->update_data("creditobligations", $where, $data);		
				}						
			}
			
			if(isset($_POST['crid'])){
					if(count($_POST['crid']) > 0){
						foreach($_POST['crid'] as $depid){
							$data = array("active"=>0,
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id());	
							$where = array("creditID"=>$depid);
							$this->ci->Loansmodel->update_data("creditobligations", $where, $data);
						}
					}
				}
			
		}else return false;
	
	}	
	
	function pensionage($bday){
		$age = $this->get_age($bday);
		if($age > 70){
			$this->form_validation->set_message('get_age', 'The applicant is not qualified. '.$age." yrs old");			
			return false;
		}elseif($age<18){
			$this->form_validation->set_message('get_age', 'The applicant is not qualified. '.$age." yrs old");			
			return false;
		}else{
			return true;
		}
	}
	
	public function addpensioninfo(){
		$p =$_POST['pension'];
		
		$hash = md5(substr($this->ci->auth->localtime(),0,10).$p['sss'].$_POST['clientid']);
			$data = array("PensionType" => $p['pensiontype'],
					"PensionNum" => $p['sss'],
					"monthlyPension" => $p['pension'],
					"PensionStatus" => $p['pensionstatus'],
					"BankID"=> $p['bank'],
					"Bankaccount"=> $p['accountnum'],
					"bankBranch" => $p['branch'],
					"pensionDate" => $p['date'],
					"clientID" => $_POST['clientid'],
					"dateAdded" => $this->ci->auth->localtime(),
					"addedBy" => $this->ci->auth->user_id(),
					"active" => 1,
					"inlineHash" => $hash);
			if($this->ci->Loansmodel->addtotable('pensioninfo', $data) == true){			
				return true;
			}else
				return false;
				
		
	}
	
	public function addpension($clientid, $p){
				
		$hash = md5(substr($this->ci->auth->localtime(),0,10).$p['sss'].$clientid);
			$data = array("PensionType" => $p['pensiontype'],
					"PensionNum" => $p['sss'],
					"monthlyPension" => $p['pension'],
					"PensionStatus" => $p['pensionstatus'],
					"BankID"=> $p['bank'],
					"Bankaccount"=> $p['accountnum'],
					"bankBranch" => $p['branch'],
					"pensionDate" => $p['date'],
					"clientID" => $clientid,
					"dateAdded" => $this->ci->auth->localtime(),
					"addedBy" => $this->ci->auth->user_id(),
					"active" => 1,
					"inlineHash" => $hash);
			$this->ci->db->insert('pensioninfo', $data);
			$colID = $this->ci->db->insert_id();
			if($colID != ''){						
				return $colID;
			}else
				return false;
				
		
	}
	
	function validate_pension(){
		$this->ci->form_validation->set_rules("pension[sss]", "SSS/GSIS/AFP/PNP/BFP Number", "required|is_unique[pensioninfo.PensionNum]");
		$this->ci->form_validation->set_rules("pension[pension]", "Monthly Pension", "callback_money_multi");
		$this->ci->form_validation->set_rules("pension[pensiontype]", "Pension by", "required");
		$this->ci->form_validation->set_rules("pension[pensionstatus]", "Pension Type", "required");
		$this->ci->form_validation->set_rules("pension[branch]", "Bank branch", "required");
		$this->ci->form_validation->set_rules("pension[accountnum]", "Bank account number", "required");
		$this->ci->form_validation->set_rules("pension[date]", "Date of Pension", "required");
		if($this->ci->form_validation->run() == false){		
			return false;			
		}else{				
			return true;			
		}
	}
	
	public function addpensioninfo2(){
		$this->ci->form_validation->set_rules("sss", "SSS/GSIS Number", "is_numeric|min_length[10]|required|is_unique[pensioninfo.PensionNum]");
		$this->ci->form_validation->set_rules("pension", "Monthly Pension", "callback_money_multi");
		$this->ci->form_validation->set_rules("pensiontype", "Pension by", "required");
		$this->ci->form_validation->set_rules("pensionstatus", "Pension Type", "required");
		$this->ci->form_validation->set_rules("branch", "Bank branch", "required");
		$this->ci->form_validation->set_rules("accountnum", "Bank account number", "required");
		if($this->ci->form_validation->run() !=false){
			$hash = md5(substr($this->ci->auth->localtime(),0,10).$this->ci->input->post('sss').$this->ci->input->post('client'));
			$data = array("PensionType" => $this->ci->input->post("pensiontype"),
					"PensionNum" => $this->ci->input->post('sss'),
					"monthlyPension" => $this->ci->input->post('pension'),
					"PensionStatus" => $this->ci->input->post('pensionstatus'),
					"BankID"=> $this->ci->input->post('bank'),
					"Bankaccount"=> $this->ci->input->post('accountnum'),
					"bankBranch" => $this->ci->input->post('branch'),
					"clientID" => $this->clientid(),
					"dateAdded" => $this->ci->auth->localtime(),
					"addedBy" => $this->ci->auth->user_id(),
					"clientID" => $this->ci->input->post('client'),
					"active" => 1,
					"inlineHash" => $hash);
			if($this->ci->Loansmodel->addtotable('pensioninfo', $data) == true){
			$this->ci->session->set_userdata('pension',true);
			$this->ci->session->set_userdata('monthly',$this->ci->input->post('pension'));
			return true;
			}else
			return false;
		}else
		return false;
	}
	
	public function clientid(){
		return (int) $this->ci->session->userdata("clientid");
	}
	
	public function monthlypension(){
		return $this->ci->session->userdata("monthly");
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
	
	function addbank(){
		$this->ci->form_validation->set_rules("bank", "Bank Name", "required");
		$this->ci->form_validation->set_rules("bankcode", "Bank Code", "required|is_unique[banks.bankCode]");
		if($this->ci->form_validation->run() != false){
			//add to db
			$hash = md5(substr($this->ci->auth->localtime(),0,10).$this->ci->auth->user_id().$this->ci->input->post('bankcode'));
			$data = array("bankCode"=>$this->ci->input->post("bankcode"),
						"BankName"=>$this->ci->input->post("bank"),
						"dateAdded" => $this->ci->auth->localtime(),
						"addedBy" => $this->ci->auth->user_id(),
						"inlineHash" => $hash);
			if ($this->ci->Loansmodel->addtotable('banks', $data) == true)
			return true;
			else
			return false;
		}
	}
	
	function validate_loan(){
		$monthly = $_POST['monthly'] - 100 ;
		$applied = $_POST['loanapplied'];
		$terms = $_POST['terms'];
		
		$monthlydue = $applied/$terms;
		$excess = $monthly-$monthlydue;
		
		if($_POST['age'] > 70)
		$loanable = $monthly * 18;
		else
		$loanable = $monthly * 24;
		
		if($applied > $loanable)
		$error[] = "Your maximum Loan Amount is ".$loanable;
		if($excess < 0)
		$error[] = "# of terms is not enough to pay your applied loan amount.";
		
		if(isset($error) and count($error)>0){
			return false;
		}else{
			$data = array("ClientID"=>$_POST['clientid'],
									"branchID"=>$this->ci->auth->branch_id(),
									"AmountApplied"=>$applied,
									"Term" => $terms,
									'LoanType'=>$_POST['typeloan'],
									'MonthlyInstallment'=>round($monthlydue,2),
									'dateApplied'=>$this->ci->auth->localtime(),
									'status'=>"processing",
									'LoanProcessor'=>$this->ci->auth->user_id());
			$loan = $this->ci->Loansmodel->addloan($data);
			if($loan != false)
			return $loan;
			else
			return false;
		}
		/*[loanapplied] => 12344
		[terms] => 1
		[interest] => 246.88
		[servicefee] => 400
		[rfpl] => 18.516
		[atm] => 15
		[notarial] => 100
		[totalcharges] => 780.396
		[net] => 11,563.604
		[typeloan] => 3
		[clientid] => 34*/
	}	
	
	function addloandetails(){
	
		$monthly = $_POST['pension']['pension'] - 100 ;
		$applied = $_POST['loanapplied'];
		$terms = $_POST['terms'];
		$method = $_POST['method'];
		
		$monthlydue = $applied/$terms;
		$excess = $monthly-$monthlydue;
		
		//Loan INFO
		$data = array("ClientID"=>$_POST['clientid'],
									"branchID"=>$this->ci->auth->branch_id(),
									"AmountApplied"=>$applied,
									"pensionID"=>$_POST['pensionaccount'],
									"Term" => $terms,
									"paymentmethod"=>$method,
									'LoanType'=>$_POST['loantype'],
									'MonthlyInstallment'=>round($monthlydue,2),
									'dateApplied'=>$this->ci->auth->localtime(),
									'status'=>"processing",
									'LoanProcessor'=>$this->ci->auth->user_id());
		$loanID = $this->ci->Loansmodel->addloan($data);
		
			
		//loanfees		
		//$this->loanfees($applied,$terms,$_POST['loantype'], $_POST['clientstatus'], $loanID);
		
		//CO-MAKER
		$data = array("clientID"=>$_POST['clientid'],
							"loanID"=>$loanID,
							"firstname"=>$_POST['comaker']['firstname'],
							"middlename"=>$_POST['comaker']['mname'],
							"lastname"=>$_POST['comaker']['lname'],
							"dob"=>$_POST['comaker']['dob'],
							"contact"=>$_POST['comaker']['contact'],
							"gender"=>$_POST['comaker']['gender'],
							"civilstatus"=>$_POST['comaker']['civilstatus'],
							"province"=>$_POST['comaker']['province'],
							"city"=>$_POST['comaker']['city'],
							"barangay"=>$_POST['comaker']['barangay'],
							"address"=>$_POST['comaker']['address'],
							"dateAdded"=>$this->ci->auth->localtime(),
							"addedBy"=>$this->ci->auth->user_id(),
							"active"=>1
							);
		$this->ci->Loansmodel->addtotable('comaker', $data);
		
		//REQUIREMENTS
		foreach($_POST['reqID'] as $req=>$val){
			if($val == 1)
			$submitted = $this->ci->auth->localtime();
			else
			$submitted = '';
			$data = array("reqID"=>$req,
								"loanID"=>$loanID,
									"submitted"=>$val,
									"dateSubmitted"=>$submitted,
									"submittedTo"=>$this->ci->auth->user_id(),
									"active"=>1);
			$this->ci->Loansmodel->addtotable('loanrequirements', $data);
		}
		
		if($loanID != NULL)
		return $loanID;
		else
		return false;
		
		
	}
	
	function comaker($loanid, $clientid, $comakerdata){
		
		$dataex = array("clientID"=>$clientid,
						"loanID"=>$loanid,
						"active"=>1);
						
		if($this->ci->Loansmodel->fieldIn('comaker', $dataex) == true){
			//update data
			$data = array(  "firstname"=>$comakerdata['firstname'],
						  "middlename"=>$comakerdata['mname'],
						  "lastname"=>$comakerdata['lname'],
						  "dob"=>$comakerdata['dob'],
						  "contact"=>$comakerdata['contact'],
						  "gender"=>$comakerdata['gender'],
						  "civilstatus"=>$comakerdata['civilstatus'],
						  "province"=>$comakerdata['province'],
						  "city"=>$comakerdata['city'],
						  "barangay"=>$comakerdata['barangay'],
						  "address"=>$comakerdata['address'],
						  "dateModified"=>$this->ci->auth->localtime(),
						  "modifiedBy"=>$this->ci->auth->user_id());
			$this->ci->Loansmodel->update_data('comaker', $dataex, $data);
		}else{
			//CO-MAKER
			$data = array("clientID"=>$clientid,
						  "loanID"=>$loanid,
						  "firstname"=>$comakerdata['firstname'],
						  "middlename"=>$comakerdata['mname'],
						  "lastname"=>$comakerdata['lname'],
						  "dob"=>$comakerdata['dob'],
						  "contact"=>$comakerdata['contact'],
						  "gender"=>$comakerdata['gender'],
						  "civilstatus"=>$comakerdata['civilstatus'],
						  "province"=>$comakerdata['province'],
						  "city"=>$comakerdata['city'],
						  "barangay"=>$comakerdata['barangay'],
						  "address"=>$comakerdata['address'],
						  "dateAdded"=>$this->ci->auth->localtime(),
						  "addedBy"=>$this->ci->auth->user_id(),
						  "active"=>1);
			$this->ci->Loansmodel->addtotable('comaker', $data);
			$this->ci->session->set_userdata('comaker', $this->ci->db->insert_id());
		}
		
		return true;
	}
	
	function newloan()
	{
		
		//add to db
		$br = $this->ci->auth->branch_id();
		$year = date('Y');
		$loantype = $_POST['loantype'];
		$count = 1;
		
		$data = array("ClientID" => $_POST['client'],
					"AmountApplied" => $_POST['loanapplied'],
					"branchID" => $br,
					"Term" => $_POST['terms'],
					"LoanType" => $_POST['loantype'],
					"MonthlyInstallment" => $_POST['monthly'],
					"dateApplied" => $this->ci->auth->localtime(),
					"addedBy" => $this->ci->auth->user_id(),
					"dateAdded" => $this->ci->auth->localtime(),
					"active" => 1);
		$loan = $this->ci->Loansmodel->addloan($data);
		if($loan != false){
			$d = array("PNno" => $br.$year.$loantype.$loan);
			$l = array('loanID' => $loan);
			$this->ci->Loansmodel->update_data("loanapplication", $l, $d);
			return true;
		}else return false;
	}
	
	function get_age($dob){
		$_age = floor( (strtotime(date('Y-m-d')) - strtotime($dob)) / 31556926);
		return $_age;
	}
	
	function loanfees($loan,$terms,$pid, $status, $loanID){
		
		$monthly = $loan/$terms;
		$fees = $this->ci->Loansmodel->getfees($pid); 
		$totalfees = 0;
		$data = array();
		
		if($fees->num_rows() > 0){
		
			foreach($fees->result() as $fee){
			
				if($status == "new"){
					if(strpos(strtolower($fee->feeName), "existing") == false){
						if($fee->comptype == 'fixed'){
						
							$data[] = array("feeID"=>$fee->feeID,
												"loanID"=>$loanID,
												"value"=>$fee->value,
												"dateAdded"=>$this->ci->auth->localtime(),
												"addedBy"=>$this->ci->auth->user_id(),
												"active"=>1);
												
						}elseif($fee->comptype == 'formula'){
						
							$formula = str_replace("loan",$loan,$fee->value);
							$formula = str_replace("terms", $terms, $formula);
							eval('$newformula='.$formula.';');
							$totalfees += $newformula;
							
							$data[] = array("feeID"=>$fee->feeID,
												"loanID"=>$loanID,
												"value"=>$newformula,
												"dateAdded"=>$this->ci->auth->localtime(),
												"addedBy"=>$this->ci->auth->user_id(),
												"active"=>1);
						}
					}
				}elseif($status == "existing"){
				
					if(strpos(strtolower($fee->feeName), "new") == false){
						if($fee->comptype == 'fixed'){
						
							$data[] = array("feeID"=>$fee->feeID,
												"loanID"=>$loanID,
												"value"=>$fee->value,
												"dateAdded"=>$this->ci->auth->localtime(),
												"addedBy"=>$this->ci->auth->user_id(),
												"active"=>1);
												
						}elseif($fee->comptype == 'formula'){
						
							$formula = str_replace("loan",$loan,$fee->value);
							$formula = str_replace("terms", $terms, $formula);
							eval('$newformula='.$formula.';');
							$totalfees += $newformula;
							
							$data[] = array("feeID"=>$fee->feeID,
												"loanID"=>$loanID,
												"value"=>$newformula,
												"dateAdded"=>$this->ci->auth->localtime(),
												"addedBy"=>$this->ci->auth->user_id(),
												"active"=>1);
						}
					}
				
				}
			
			}
			
			//insertbatch
			$this->ci->db->insert_batch('loanfees',$data);
		}
			
	}
	
	function ComputationOfLoan($loans){
		$loandata = $loans['loaninfo']->row();
		$client = $loans['clientinfo']->row();
		$fees = $loans['fees'];
		$loan = $loandata->approvedAmount;
		$terms = $loandata->Term;
		$pid = $loandata->LoanType;
		$loanid = $loandata->loanID;
		if($loandata->paymentmethod == 'M')
			$monthly = $loandata->MonthlyInstallment;
		else
			$monthly = '';
		//$fees = $this->ci->Loansmodel->getLoanFees($loanid); 
		$totalfees = 0;
			
		if($loan > 0){
			if($fees->num_rows() > 0){
				$this->ci->table->set_heading("Principal",'',array("align"=>"right", "data"=>number_format($loan,2)));
				$this->ci->table->add_row('Terms','', $terms.' months');
				$this->ci->table->add_row('Interest','', array("align"=>"right", "data"=>$loandata->interest." %"));
				$this->ci->table->add_row('<label>Fees</label>','','');
				foreach($fees->result() as $fee){				
					
						$this->ci->table->add_row('',$fee->feeName, array("align"=>"right", "data"=>number_format($fee->value,2)));
						$totalfees += $fee->value;
						
						$loans['fee'][$fee->feeName] = $fee->value;
				}
				
				$net = $loan-$totalfees;
				$loans['net'] = $net;
				$this->ci->table->add_row("<label>Total Fees</label>", "", array("align"=>"right","data"=>"<label>( ".number_format($totalfees,2)." )</label>"));
				$this->ci->table->add_row(array("style"=>"color:red", "data"=>"<label>NET PROCEEDS</label>"), "", array("align"=>"right","style"=>"color:red", "data"=>"<h4> ".number_format($net,2)." </h4>"));
				$this->ci->table->add_row("<label>Monthly Installment</label>",'',array("align"=>"right","data"=>$monthly));
				$loans['table'] = $this->ci->table->generate();
				
				return $loans;
			}else{
				$fff = $this->ci->Loansmodel->getfeeBYPID($pid, $loan, $terms);
				$pid =  element('pid', $fff);	
				$fees =  element('fees', $fff);	
				$totalfees =  element('totalfees', $fff);
				$net =  element('netproceeds', $fff);
							
				
							
				$this->ci->table->add_row('<label>Principal</label>','',array("align"=>"right","<label><h4>".number_format($loan,2)."</h4></label>"));	
				$this->ci->table->add_row('<label>Terms</label>','',array("align"=>"right","<label>".$terms." months</label>") );
				
				//FEES HERE
				
				
				
				if($fees != ''){
					$this->ci->table->add_row('<label>Fees</label>','','');
					foreach($fees as $fee){
						$this->ci->table->add_row('',$fee['feename'],array("data"=>number_format($fee['feevalue'],2), "align"=>"right"));
					}
				}
				
				//TOTAL FEES
				
				$this->ci->table->add_row('<label>'.$totalfees['name'].'</label>','','<label>( '.number_format($totalfees['value'],2).' )</label>');
				$this->ci->table->add_row(array("style"=>"color:red", "data"=>'<label><h4>'.$net['name'].'</h4></label>'),'',array("align"=>"right","style"=>"color:red", "data"=>'<h4>'.number_format($net['value'],2).'</h4>'));	
				$this->ci->table->add_row('<label>Monthly Installment</label>', '',array("align"=>"right","data"=>"<label>".$monthly."</label>"));
				$loans['net'] = $net['value'];
				$loans['table'] = $this->ci->table->generate();
				
				return $loans;
			}
		}
	}
	
	function loancomputation($loan,$terms,$pid, $loanid){
		$monthly = $loan/$terms;
		$fees = $this->ci->Loansmodel->getLoanFees($loanid); 
		$totalfees = 0;
			
		if($loan > 0){
			if($fees->num_rows() > 0){
				$this->ci->table->set_heading("Principal",'',array("align"=>"right", "data"=>number_format($loan,2)));
				$this->ci->table->add_row('Terms','', $terms.' months');
				$this->ci->table->add_row('<label>Fees</label>','','');
				foreach($fees->result() as $fee){				
					
						$this->ci->table->add_row('',$fee->feeName, array("align"=>"right", "data"=>number_format($fee->value,2)));
						$totalfees += $fee->value;
						
						$loans['fee'][$fee->feeName] = $fee->value;
				}
				
				$net = $loan-$totalfees;
				$loans['net'] = $net;
				$this->ci->table->add_row("<label>Total Fees</label>", "", array("align"=>"right","data"=>"<label>( ".number_format($totalfees,2)." )</label>"));
				$this->ci->table->add_row(array("style"=>"color:red", "data"=>"<label>NET PROCEEDS</label>"), "", array("align"=>"right","style"=>"color:red", "data"=>"<h4> ".number_format($net,2)." </h4>"));
				$this->ci->table->add_row("<label>Monthly Installment</label>",'',array("align"=>"right","data"=>number_format($monthly,2)));
				$loans['table'] = $this->ci->table->generate();
				
				return $loans;
			}else{
				$fff = $this->ci->Loansmodel->getfeeBYPID($pid, $loan, $terms);
				$pid =  element('pid', $fff);	
				$fees =  element('fees', $fff);	
				$totalfees =  element('totalfees', $fff);
				$net =  element('netproceeds', $fff);
							
				
							
				$this->ci->table->add_row('<label>Principal</label>','',array("align"=>"right","<label><h4>".number_format($loan,2)."</h4></label>"));	
				$this->ci->table->add_row('<label>Terms</label>','',array("align"=>"right","<label>".$terms." months</label>") );
				
				//FEES HERE
				
				
				
				if($fees != ''){
					$this->ci->table->add_row('<label>Fees</label>','','');
					foreach($fees as $fee){
						$this->ci->table->add_row('',$fee['feename'],array("data"=>number_format($fee['feevalue'],2), "align"=>"right"));
					}
				}
				
				//TOTAL FEES
				
				$this->ci->table->add_row('<label>'.$totalfees['name'].'</label>','','<label>( '.number_format($totalfees['value'],2).' )</label>');
				$this->ci->table->add_row(array("style"=>"color:red", "data"=>'<label><h4>'.$net['name'].'</h4></label>'),'',array("align"=>"right","style"=>"color:red", "data"=>'<h4>'.number_format($net['value'],2).'</h4>'));	
				$this->ci->table->add_row('<label>Monthly Installment</label>', '',array("align"=>"right","data"=>"<label>".number_format($monthly,2)."</label>"));
				$loans['net'] = $net['value'];
				$loans['table'] = $this->ci->table->generate();
				
				return $loans;
			}
		}
	}
	
	
	function loancomputation_OLD($loan,$terms,$pid, $loanid){
		$monthly = $loan/$terms;
		$fees = $this->ci->Loansmodel->getLoanFees($loanid); 
		$totalfees = 0;
			
		if($loan > 0){
			if($fees->num_rows() > 0){
				$this->ci->table->set_heading("Principal",'','');
				$this->ci->table->add_row('',$terms." &nbsp; x &nbsp; ".number_format($monthly,2), array("align"=>"right", "data"=>number_format($loan,2)));
				$this->ci->table->add_row('<label>Fees</label>','','');
				foreach($fees->result() as $fee){				
					
						$this->ci->table->add_row('',$fee->feeName, array("align"=>"right", "data"=>number_format($fee->value,2)));
						$totalfees += $fee->value;
						
						$loans['fee'][$fee->feeName] = $fee->value;
				}
				
				$net = $loan-$totalfees;
				$loans['net'] = $net;
				$this->ci->table->add_row("<label>Total Fees</label>", "", array("align"=>"right", "data"=>"<label>( ".number_format($totalfees,2)." )</label>"));
				$this->ci->table->add_row("<label>NET PROCEEDS</label>", "", array("align"=>"right", "data"=>"<label> ".number_format($net,2)." </label>"));
				$loans['table'] = $this->ci->table->generate();
				
				return $loans;
			}else{
				
			$status = 'new';
			$monthly = $loan/$terms;
			$fees = $this->ci->Loansmodel->getfees($pid); 
			
					if($fees->num_rows() > 0){
					
						$this->ci->table->set_heading("Principal",'','');
						$this->ci->table->add_row('',$terms." &nbsp; x &nbsp; ".number_format($monthly,2), array("align"=>"right", "data"=>number_format($loan,2)));
						$this->ci->table->add_row('<label>Fees</label>','','');
							
							foreach($fees->result() as $fee){
								//echo "<tr>";
								
								if($status == "new"){
									if(strpos(strtolower($fee->feeName), "existing") == false){
									//echo "<td></td>";
										if($fee->comptype == 'fixed'){
										
											$totalfees += $fee->value;
											//echo "<td>".$fee->feeName."</td><td> ".number_format($fee->value,2)."</td>";
											$this->ci->table->add_row('',$fee->feeName, array("align"=>"right", "data"=>number_format($fee->value,2)));
										}elseif($fee->comptype == 'formula'){
											
											$formula = str_replace("loan",$loan,$fee->value);
											$formula = str_replace("terms", $terms, $formula);
											eval('$newformula='.$formula.';');
											//echo $newformula;
											$totalfees += $newformula;
											
											//echo "<td>".$fee->feeName."</td><td> ".number_format($newformula,2)."</td>";
											$this->ci->table->add_row('',$fee->feeName, array("align"=>"right", "data"=>number_format($newformula,2)));
										}else {
											$this->ci->table->add_row('','', '');
										}
										
									}
								}elseif($status == "existing"){
									if(strpos(strtolower($fee->feeName), "new") == false){
									//echo "<td></td>";
										if($fee->comptype == 'fixed'){
										
											$totalfees += $fee->value;
											//echo "<td>".$fee->feeName."</td><td> ".number_format($fee->value,2)."</td>";
											$this->ci->table->add_row('',$fee->feeName, array("align"=>"right", "data"=>number_format($fee->value,2)));
											
										}elseif($fee->comptype == 'formula'){
											
											$formula = str_replace("loan",$loan,$fee->value);
											$formula = str_replace("terms", $terms, $formula);
											eval('$newformula='.$formula.';');
											//echo $newformula;
											$totalfees += $newformula;
											
											//echo "<td>".$fee->feeName."</td><td> ".number_format($newformula,2)."</td>";
											$this->ci->table->add_row('',$fee->feeName, array("align"=>"right", "data"=>number_format($newformula,2)));
											
										}else {
											$this->ci->table->add_row('','', '');
										}
										
									}	
								}
								
								//echo "</tr>";
							}
							$net = $loan-$totalfees;
							$loans['net'] = $net;
							$this->ci->table->add_row("<label>Total Fees</label>", "", array("align"=>"right", "data"=>"<label>( ".number_format($totalfees,2)." )</label>"));
							$this->ci->table->add_row("<label>NET PROCEEDS</label>", "", array("align"=>"right", "data"=>"<label> ".number_format($net,2)." </label>"));
							$loans['table'] = $this->ci->table->generate();
							
							return $loans;
						
					
				}
			}
		}
}
	function add_loanschedule($terms, $loan, $date, $loanid, $method){
		$monthly = round($loan/$terms,2);
		$data =  array();		
		$count =1; 
		$less = 0;
		$olb=$loan;
		$totalpaid = 0;
		$year = date("Y", strtotime($date));
		$m = date("m", strtotime($date));
		$d = date("d", strtotime($this->ci->auth->localdate()));
		$sdate = $year."-".$m."-".$d;
		switch($method){
			case 'M':
				while ($count <= $terms){	
					$totalpaid += $monthly;
					$olb = $loan-$totalpaid;
					$date =  $this->ci->db->query("SELECT DATE_ADD( '".$sdate."', INTERVAL ".$count." MONTH ) as NewDate");
					$date = $date->row();
					$date = date("Y-m-d", strtotime($date->NewDate));
					
					$data[] = array("loanID"=>$loanid,
										"order"=>$count,
										"AmountDue"=>$monthly,
										"LoanBalance"=>$olb,
										"DueDate"=>date("Y-m-d", strtotime($date)),
										"DateAdded"=>$this->ci->auth->localtime(),
										"AddedBy"=>$this->ci->auth->user_id(),
										"Active"=>1);			
					$count++;
				}
				
				$this->ci->db->insert_batch("loanschedule", $data);
				
			break;
			
			case 'L':
				//$date = $date."+".$terms." month";
				$date =  $this->ci->db->query("SELECT DATE_ADD( '".$sdate."', INTERVAL ".$terms." MONTH ) as NewDate");
				$date = $date->row();
					 $date = date("Y-m-d", strtotime($date->NewDate));
					$data = array("loanID"=>$loanid,
										"order"=>$count,
										"AmountDue"=>$loan,
										"LoanBalance"=>$olb,
										"DueDate"=>date("Y-m-d", strtotime($date)),
										"DateAdded"=>$this->ci->auth->localtime(),
										"AddedBy"=>$this->ci->auth->user_id(),
										"Active"=>1);
										
				$this->ci->db->insert("loanschedule", $data);
			break;
			
			case 'SM':
				
			break;
		}
		
		return true;
	}
	
	function regen_loanschedule($terms, $loan, $date, $loanid, $method, $relDate){
		
		$data = array("loanID"=>$loanid,
							"Active"=>1);
		$this->ci->db->where($data);
		$sched = $this->ci->db->get("loanschedule");
		$totalterm = $sched->num_rows();
		$year = date("Y", strtotime($date));
		$m = date("m", strtotime($date));
		$d = date("d", strtotime($relDate));
		$date = $year."-".$m."-".$d;
		$monthly = round($loan/$terms);
		$data =  array();		
		$count =1; 
		$less = 0;
		$olb=$loan;
		$totalpaid = 0;
		switch($method){
			case 'M':
				if($terms != $totalterm){
					$d = array("Active"=>0);
					$w = array("loanID"=>$loanid);
					
					$this->ci->Loansmodel->update_data("loanschedule", $w, $d);
				}
				
				while ($count <= $terms){
					if($count == $terms)
						$monthly = $olb;
					
					$totalpaid += $monthly;
					$olb = $loan-$totalpaid;
					
					$date = $date."+1 month";
					
					if($d > 28){
						//get month
						$day = date("d",strtotime($date));
						$month = date("m",strtotime($date));
						$year = date("Y", strtotime($date));
						if($day != $d and $month== '01'){
							$m = '02';
							$d = '28';
							$date = $year."-".$m."-".$d;
						}elseif($day != $d){
												
							$date = $date."+1 month";
						}
					}else{
						
						$date = $date."+1 month";
					}
					
					$where = array("loanID"=>$loanid,
										"order"=>$count,
										"Active"=>1);
										
					if($this->ci->Loansmodel->fieldIn('loanschedule',$where)==true){
						$data = array("AmountDue"=>$monthly,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($date)),
											"DateModified"=>$this->ci->auth->localtime(),
											"modifiedBy"=>$this->ci->auth->user_id());		
						
						$this->ci->Loansmodel->update_data("loanschedule", $where, $data);
					}else{
						$data = array("AmountDue"=>$monthly,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($date)),
											"DateAdded"=>$this->ci->auth->localtime(),
											"addedBy"=>$this->ci->auth->user_id(), 
											"loanID"=>$loanid,
											"order"=>$count,
											"Active"=>1);
						
						$this->ci->Loansmodel->addtotable('loanschedule', $data);
					}
					$count++;
				}				
				
				
			break;
			
			case 'L':
				$date = $date."+".$terms." month";
				$where = array("loanID"=>$loanid,
										"order"=>$count,
										"Active"=>1);
				if($this->ci->Loansmodel->fieldIn('loanschedule',$where)==true){
					$data = array("AmountDue"=>$loan,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($date)),
											"dateModified"=>$this->ci->auth->localtime(),
											"modifiedBy"=>$this->ci->auth->user_id(),
											"Active"=>1);				
					$this->ci->Loansmodel->update_data("loanschedule", $where, $data);
				}else{
					$data = array("AmountDue"=>$monthly,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($date)),
											"DateAdded"=>$this->ci->auth->localtime(),
											"addedBy"=>$this->ci->auth->user_id(), 
											"loanID"=>$loanid,
											"order"=>$count,
											"Active"=>1);
						
					$this->ci->Loansmodel->addtotable('loanschedule', $data);
				}
			break;
			
			case 'SM':
				$count = 1;
				echo "Day : ".date("d", strtotime($_POST['startpayment']));
				$day = date("d", strtotime($_POST['startpayment']));
				$term = $_POST['term']*2;
				$ld = date("Y-m-d", strtotime($_POST['startpayment']));
				$nd = $ld;
				$days = "11-26";
				$monthly = $monthly/2;
				list($d1, $d2) = explode("-", $days);
				
				while ($count <= $term ){
													
					if($count == $term)
						$monthly = $olb;
					
					$totalpaid += $monthly;
					$olb = $loan-$totalpaid;
					
					
					$where = array("loanID"=>$loanid,
										"order"=>$count,
										"Active"=>1);
										
					if($this->ci->Loansmodel->fieldIn('loanschedule',$where)==true){
						$data = array("AmountDue"=>$monthly,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($nd)),
											"DateModified"=>$this->ci->auth->localtime(),
											"modifiedBy"=>$this->ci->auth->user_id());		
						
						$this->ci->Loansmodel->update_data("loanschedule", $where, $data);
					}else{
						$data = array("AmountDue"=>$monthly,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($nd)),
											"DateAdded"=>$this->ci->auth->localtime(),
											"addedBy"=>$this->ci->auth->user_id(), 
											"loanID"=>$loanid,
											"order"=>$count,
											"Active"=>1);
						
						$this->ci->Loansmodel->addtotable('loanschedule', $data);
					}
					
					
					if($day == $d1){
						
						$day = $d2;
						$ld = date("Y-m", strtotime($nd));
						if(date("d", strtotime($ld."-".$day)) < $d1)
							$nd = date("Y-m-t", strtotime($nd));
						else $nd = date("Y-m-d",strtotime($ld."-".$day));			
						
					}else{
						$date =  $this->ci->db->query("SELECT DATE_ADD( '".$nd."', INTERVAL 1 MONTH ) as NewDate");
						$date = $date->row();
						$ld = date("Y-m", strtotime($date->NewDate));
						//echo $date->NewDate;
						$day = $d1;
						$nd = date("Y-m-d",strtotime($ld."-".$day));
					}	
					
					$count++;
				}
			break;
		}
		
		return true;
	}
	
		
	function update_loanschedule($terms, $loan, $date, $loanid, $method){
		
		$data = array("loanID"=>$loanid,
							"Active"=>1);
		$this->ci->db->where($data);
		$sched = $this->ci->db->get("loanschedule");
		$totalterm = $sched->num_rows();
		
		$monthly = round($loan/$terms);
		$data =  array();		
		$count =1; 
		$less = 0;
		$olb=$loan;
		$totalpaid = 0;
		$day = date("d", strtotime($date));
		$year = date("Y", strtotime($date));
		$m = date("m", strtotime($date));
		$d = date("d", strtotime($date));
		$sdate = $year."-".$m."-".$d;
		switch($method){
			case 'M':
				
				if($totalterm != $terms){
					$d = array("Active"=>0);
					$w = array("loanID"=>$loanid);
					
					$this->ci->Loansmodel->update_data("loanschedule", $w, $d);
				}
				
				while ($count <= $terms){
					if($count == $terms)
						$monthly = $olb;
					
					$totalpaid += $monthly;
					$olb = $loan-$totalpaid;
					$maxday = date('t',strtotime($date));	
								
					$date =  $this->ci->db->query("SELECT DATE_ADD( '".$sdate."', INTERVAL ".$count." MONTH ) as NewDate");
					$date = $date->row();
					$date = date("Y-m-d", strtotime($date->NewDate));
					
					$where = array("loanID"=>$loanid,
										"order"=>$count,
										"Active"=>1);
										
					if($this->ci->Loansmodel->fieldIn('loanschedule',$where)==true){
						$data = array("AmountDue"=>$monthly,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($date)),
											"DateModified"=>$this->ci->auth->localtime(),
											"modifiedBy"=>$this->ci->auth->user_id());		
						
						$this->ci->Loansmodel->update_data("loanschedule", $where, $data);
					}else{
						$data = array("AmountDue"=>$monthly,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($date)),
											"DateAdded"=>$this->ci->auth->localtime(),
											"addedBy"=>$this->ci->auth->user_id(), 
											"loanID"=>$loanid,
											"order"=>$count,
											"Active"=>1);
						
						$this->ci->Loansmodel->addtotable('loanschedule', $data);
					}
					$count++;
				}				
				
				
			break;
			
			case 'L':
			
				if($totalterm != 1){
					$d = array("Active"=>0);
					$w = array("loanID"=>$loanid);
					
					$this->ci->Loansmodel->update_data("loanschedule", $w, $d);
				}
				//$date = $date."+".$terms." month";
				$date =  $this->ci->db->query("SELECT DATE_ADD( '".$sdate."', INTERVAL ".$terms." MONTH ) as NewDate");
				$date = $date->row();
					 $date = date("Y-m-d", strtotime($date->NewDate));
				$where = array("loanID"=>$loanid,
										"order"=>$count,
										"Active"=>1);
				if($this->ci->Loansmodel->fieldIn('loanschedule',$where)==true){
					$data = array("AmountDue"=>$loan,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($date)),
											"dateModified"=>$this->ci->auth->localtime(),
											"modifiedBy"=>$this->ci->auth->user_id(),
											"Active"=>1);				
					$this->ci->Loansmodel->update_data("loanschedule", $where, $data);
				}else{
					$data = array("AmountDue"=>$loan,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($date)),
											"DateAdded"=>$this->ci->auth->localtime(),
											"addedBy"=>$this->ci->auth->user_id(), 
											"loanID"=>$loanid,
											"order"=>$count,
											"Active"=>1);
						
					$this->ci->Loansmodel->addtotable('loanschedule', $data);
				}
			break;
			
			case 'SM':
				
				$count = 1;
				echo "Day : ".date("d", strtotime($_POST['startpayment']));
				$day = date("d", strtotime($_POST['startpayment']));
				$term = $_POST['term']*2;
				$ld = date("Y-m-d", strtotime($_POST['startpayment']));
				$nd = $ld;
				$days = "11-26";
				$monthly = $monthly/2;
				list($d1, $d2) = explode("-", $days);
				
				while ($count <= $term ){
													
					if($count == $term)
						$monthly = $olb;
					
					$totalpaid += $monthly;
					$olb = $loan-$totalpaid;
					
					
					$where = array("loanID"=>$loanid,
										"order"=>$count,
										"Active"=>1);
										
					if($this->ci->Loansmodel->fieldIn('loanschedule',$where)==true){
						$data = array("AmountDue"=>$monthly,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($nd)),
											"DateModified"=>$this->ci->auth->localtime(),
											"modifiedBy"=>$this->ci->auth->user_id());		
						
						$this->ci->Loansmodel->update_data("loanschedule", $where, $data);
					}else{
						$data = array("AmountDue"=>$monthly,
											"LoanBalance"=>$olb,
											"DueDate"=>date("Y-m-d", strtotime($nd)),
											"DateAdded"=>$this->ci->auth->localtime(),
											"addedBy"=>$this->ci->auth->user_id(), 
											"loanID"=>$loanid,
											"order"=>$count,
											"Active"=>1);
						
						$this->ci->Loansmodel->addtotable('loanschedule', $data);
					}
					
					
					if($day == $d1){
						
						$day = $d2;
						$ld = date("Y-m", strtotime($nd));
						if(date("d", strtotime($ld."-".$day)) < $d1)
							$nd = date("Y-m-t", strtotime($nd));
						else $nd = date("Y-m-d",strtotime($ld."-".$day));			
						
					}else{
						$date =  $this->ci->db->query("SELECT DATE_ADD( '".$nd."', INTERVAL 1 MONTH ) as NewDate");
						$date = $date->row();
						$ld = date("Y-m", strtotime($date->NewDate));
						//echo $date->NewDate;
						$day = $d1;
						$nd = date("Y-m-d",strtotime($ld."-".$day));
					}	
					
					$count++;
				}
			break;
		}
		
		return true;
	}
	
	
	function loanschedule_old($terms, $loan, $date){
		$monthly = $loan/$terms;
		
		$this->ci->table->set_heading("Installments", "Due Date","Amount Due","Loan Balance");
		$count =1; 
		$less = 0;
		$olb=$loan;
		//$date = "2014-08-18";
		while ($count <= $terms){
			
			$olb -= $monthly;
			$date = $date."+1 month";
			$this->ci->table->add_row($count, date("d - M - Y", strtotime($date)),number_format($monthly,2),number_format($olb,2));			
			$count++;
		}
		return $this->ci->table->generate();
	}
	
	function loanschedule($loanid){
		$where = array("loanID"=>$loanid,
							"active"=>1);
		$schedule = $this->ci->Loansmodel->get_data_from("loanschedule", $where);
		
		if($schedule->num_rows() >0){
			$totalpaid= 0;
			$count = 1;
			$totalterms = $schedule->num_rows();
			foreach($schedule->result() as $sch){
				if($count == $totalterms){
					
				}
				$amount = round($sch->AmountDue);
				$this->ci->table->add_row(array("align"=>"center","width"=>'30%', "data"=>$sch->order), array("align"=>"left", "width"=>'20%',"data"=>date("F d, Y", strtotime($sch->DueDate))),array("align"=>"center", "data"=>number_format($sch->AmountDue,2)));		
				$totalpaid += $amount;
				
			}
			$this->ci->table->set_heading(array("align"=>"center", "data"=>"Installments"), array("align"=>"center", "data"=>"Due Date"),array("align"=>"center", "data"=>"Amount Due"));
			return $this->ci->table->generate();
		}
	}
	
	function approvedloansched($loanid, $loan, $pn){
		if($pn == '')
			$pn = "PN IS NULL";
		else $pn = "PN = '".$pn."'";
		$sql = "select * from loanschedule where loanID = '$loanid' and $pn order by loanschedule.order ASC";
		$sched =  $this->ci->db->query($sql);
		
		if($sched->num_rows() > 0){
			$loanbal = $loan;
			foreach($sched->result() as $sch){
				if($sch->LoanBalance == NULL)
					$loanbal -= $sch->Paid;
				else
					$loanbal = $sch->LoanBalance;
				//echo $loanbal;
				$paid = ($sch->Paid ? $sch->Paid : 0);
				$this->ci->table->add_row($sch->order, $sch->DueDate,number_format($sch->AmountDue,2), number_format($paid,2), $sch->DatePaid);
			}
			$this->ci->table->set_heading("#", "Due Date","Amount Due", "Amount Paid","Date Paid");
			return $this->ci->table->generate();
		}
	}
	
	function pensioninfo($pensionaccount){
		$pension = $this->ci->Loansmodel->get_pensioninfo($pensionaccount);
		if($pension->num_rows() > 0){
		 $disabled = 'disabled';
		 $readonly = "readonly";
			foreach($pension->result() as $p){
				$pensiontype = $p->PensionType;
				$pensionNum = $p->PensionNum;
				$monthly = $p->monthlyPension;
				$pstatus = $p->PensionStatus;
				if(isset($p->bankCode))
				$bank = $p->bankCode;
				else
				$bank = '';
				$bankacct = $p->Bankaccount;
				$bankBranch = $p->bankBranch;
				$date = $p->pensionDate;
				//$date = date("d", strtoupper($date));
			}
		}else{
			$disabled = '';
			 $readonly = "";
			 $pensiontype = '';
				$pensionNum = '';
				$monthly = 0;
				$pstatus = '';
				$bank = '';
				$bankacct = '';
				$bankBranch = '';
				$date = '';
		}
		$monthly = $monthly ? $monthly : 0;
		$this->ci->table->add_row("Pension Type: ".strtoupper($pensiontype),array("colspan"=>'2', "data"=>"<b>SSS/GSIS Number :</b> ". ($pensionNum?$pensionNum:"<i>none<i>") ));
		$this->ci->table->add_row("<b>Pension Status : </b>".strtoupper(($pstatus?$pstatus:'<i>none</i>')), "<b>Monthly Pension : </b> "."Php ".number_format($monthly,2), "<b>Date Received : </b>".$date);
		$this->ci->table->add_row( "<b>Bank : </b>".strtoupper(($bank?$bank:"<i>none</i>")), "<b>Branch : </b>".strtoupper($bankBranch), "<b>Account # : </b>".$bankacct);
		
		/*$div = '<div class="panel-body">';
			$div .= '<div class="row form-group">';
				$div .= '<div class="col-md-4"><label>Pension by: </label> &nbsp;';
					$div .= strtoupper($pensiontype);
				$div .='</div>';
				$div .='<div class="col-md-4"><label>Pension Type</label> &nbsp;';
					$div .= strtoupper($pstatus);
				$div .='</div>';
				$div .='<div class="col-md-4"><label>SSS/GSIS #: </label> &nbsp;';
					$div .= $pensionNum;
				$div .='</div>';		
					
			$div .='</div>';
			$div .= '<div class="row form-group">';
				$div .='<div class="col-md-4"><label>Monthly Pension</label> &nbsp;';		
					$div .= number_format($monthly);
				$div .='</div>';
				$div .= '<div class="col-md-4"><label>Pension Date Received </label> &nbsp;';
					$div .= $date;
				$div .='</div>';				
			$div .='</div>';
			$div .= '<div class="row form-group">';				
				$div .= '<div class="col-md-4"><label>Bank: </label></label> &nbsp;';
					$div .= strtoupper($bank);
				$div .='</div>';
				$div .= '<div class="col-md-4"><label>Branch: </label></label> &nbsp;';
					$div .= strtoupper($bankBranch);
				$div .='</div>';
				$div .= '<div class="col-md-4"><label>Account #: </label></label> &nbsp;';
					$div .=$bankacct;
				$div .='</div>';
			$div .='</div>';		
		$div .='</div>'; */		
			
		return $this->ci->table->generate();
	}
	
	
	function comakerinfo($loanid) {
		$comaker = $this->ci->Loansmodel->getComaker($loanid);	
		if($comaker != false){ 	
			$clientinfo = $comaker['clientinfo'];
			$spouseinfo = $comaker['spouseinfo'];
			
			$this->ci->table->set_heading(strtoupper("Comaker's Personal Information"));
			if($clientinfo->num_rows() > 0){	
						
				foreach($clientinfo->result() as $value){							
					$this->ci->table->add_row(array("data"=>"<label>Comaker's Name</label>", "width"=>"30%"), '<a href="'.base_url().'client/profile/'.$value->ClientID.'">'.$value->LastName.", ".$value->firstName." ".$value->MiddleName.'</a>');
					$this->ci->table->add_row(array("data"=>"<label>Date of Birth</label>"), date("F d, Y", strtotime($value->dateOfBirth)));
					$this->ci->table->add_row(array("data"=>"<label>Address</label>"), $value->address.", ".$value->barangay.", ".$value->cityname.", ".$value->provname);
					$this->ci->table->add_row(array("data"=>"<label>Contact</label>"), $value->contact);
					$this->ci->table->add_row(array("data"=>"<label>Civil Status</label>"), $value->civilStatus);
					$this->ci->table->add_row(array("data"=>"<label>Gender</label>"), $value->gender);
				}					
			}else{
				$this->ci->table->add_row("No Information found.");	
			}			
				
			$div = $this->ci->table->generate();	
			
			$this->ci->table->set_heading("Comaker's Spouse Information");
			if($spouseinfo->num_rows() > 0){
				
				
				
			}else{
				$this->ci->table->add_row(strtoupper("No Spouse Information"));	
			}
			$div .= $this->ci->table->generate();
			
			return $div;
		}else{
			echo "No comaker yet. Update loan Application";	
		}
	}
	
	
	
	function requirements($loanid=NULL, $pid) {
		$tmpl = array ('table_open'  => '<table class="table table-bordered" id="loanstatustable">');
		$this->ci->table->set_template($tmpl);
		if($loanid != NULL){
			$reqs = $this->ci->Loansmodel->getLoanreqs($loanid);
			$count =1;
			$complete = array();
		
			if($reqs->num_rows() > 0){			
				foreach($reqs->result() as $req){
					
					if($req->submitted == 1){
						$checkyes = "checked";
						$checkno = "";
						$complete[] = true;
						$submitted = date("Y-m-d", strtotime($req->dateSubmitted));
					}else{
						$complete[] = false;
						$checkyes = "";
						$checkno = "checked"; 
						$submitted = "-";
					}
						$this->ci->table->add_row($count,"<input type='hidden' name='reqID[".$req->reqID."]' value='0' ".$checkno."><input type='checkbox' name='reqID[".$req->reqID."]' value='1' ".$checkyes."><input type='hidden' name='reqname[".$req->reqID."]' value='".$req->requirement."' >" , array("data"=>$req->requirement,"width"=>"50%"), $submitted );				
						$count++;
				}
				
				
			}
			
		}else{
						
			$reqs = $this->ci->Loansmodel->getreqs($pid);
			$complete[] = false;
			$count = 1;
			if($reqs->num_rows() > 0){				
				foreach($reqs->result() as $req){
					$this->ci->table->add_row($count,"<input type='hidden' name='reqID[".$req->reqID."]' value='0'><input type='checkbox' name='reqID[".$req->reqID."]' value='1'><input type='hidden' name='reqname[".$req->reqID."]' value='".$req->requirement."' >" , array("data"=>$req->requirement,"width"=>"50%"), '');				
					$count++;
				}
				
			}
		}
		
		$this->ci->table->set_heading("#", "Submitted", "Requirements", "Date Submitted");
		$return['req'] =  $this->ci->table->generate();
		
		$return['complete'] = $complete;
		return $return;
	}
	
	function generatePN($loanid){
		$branch = $this->ci->auth->branch_id();
		$my = date("my");
		
		//generate 3rd value with 6 digit ID
		$data = array("branchID"=>$branch);
		$loan = $this->ci->Loansmodel->get_data_from("loancounter",$data);
		$l = $loan->row();
		$id = str_pad($l->loancount, 4, '0', STR_PAD_LEFT);
		return $branch."-".$my."-".$id;
	}

	function validate_amount($loan, $terms, $pensionID){
		$monthly = $loan/$terms;	
		//$pensionID = $_POST['pensionaccount'];
		$pension = $this->ci->product->getpensionamount($pensionID) - 100;
		return true;
			/*if($monthly > $pension){
				//$this->ci->form_validation->set_message("verifyamount","Monthly Pension is not enough to pay the amount applied");
				echo "Monthly Pension is not enough to pay the amount applied";
				return false;
			}else {
				return true;
			}*/
	}
	
	function approveLoan($loanid){
		$amount = $_POST['approvedamount'];
		$loans = $this->ci->Loansmodel->getLoanbyID($loanid);
		if($loans->num_rows() > 0){
			$loan = $loans->row();
			//echo "loanid exist. ";
			//VALIDATE AMOUNT APPROVED
			
			if($this->validate_amount($amount, $loan->Term, $loan->pensionID)== true){
				//echo "loan amount is valid. ";
				$pn = $this->generatePN($loanid);
				//echo $pn. "is the PN. ";
				
				//UPDATE Loanapplication table
				$table = "loanapplication";
				$id = array("loanID"=>$loanid);
				$data = array("status"=> 'approved',
									"approvedAmount"=>$amount,
									"PNno"=>$pn,
									"MaturityDate"=>date("Y-m-d", strtotime($loan->dateApplied."+".$loan->Term." month")),
									"dateApproved"=>$this->ci->auth->localtime(),
									"approvedBy"=>$this->ci->auth->user_id(),
									"active"=>1);
									
				if ($this->ci->Loansmodel->update_data($table, $id, $data) != false){
				
					$status = $this->clientstatusonLoan($loan->LoanType, $loan->ClientID);
					
					//ADD FEES
					//$this->loanfees($amount,$loan->Term,$loan->LoanType, $status, $loanid);
					
					//ADD SCHEDULE
					$this->add_loanschedule($loan->Term, $amount, $loan->dateApplied, $loanid);
				
					return true;
				}else{
					return false;
				}
				
			}else{
				echo "loan amount is invalid. ";
				return false;
			}
		}else return false;
		
		
		/*
		if($loan->AmountApplied != $amount){		
			if($this->validate_amount($amount, $loan->Term, $loan->pensionID)== true){
				$data = array("active"=>0);
				$where = array("loanID"=>$loanid);
				$this->ci->Loansmodel->update_data('loanfees', $where, $data);
				
				$status = $this->clientstatusonLoan($loan->LoanType, $loan->ClientID);
				$this->loanfees($amount,$loan->Term,$loan->LoanType, $status, $loanid);
				
				$pn = $this->generatePN($loanid);
			
				//UPDATE Loanapplication table
				$table = "loanapplication";
				$id = array("loanID"=>$loanid);
				$data = array("status"=> 'approved',
								"approvedAmount"=>$amount,
								"PNno"=>$pn,
								"dateApproved"=>$this->ci->auth->localtime(),
								"approvedBy"=>$this->ci->auth->user_id(),
								"active"=>1);
								
				if ($this->ci->Loansmodel->update_data($table, $id, $data) != false){				
					
					return true;
				}
					
			}else{
				return false;
			}
		}else{
		
			//GENERATE PN
			$pn = $this->generatePN($loanid);
			
			//UPDATE Loanapplication table
			$table = "loanapplication";
			$id = array("loanID"=>$loanid);
			$data = array("status"=> 'approved',
							"approvedAmount"=>$amount,
							"PNno"=>$pn,
							"dateApproved"=>$this->ci->auth->localtime(),
							"approvedBy"=>$this->ci->auth->user_id(),
							"active"=>1);
			if ($this->ci->Loansmodel->update_data($table, $id, $data) != false)
			return true;
		}
		*/
	}
	
	function updateLoanStatus($status,$loanid){
		$table = "loanapplication";
		$id = array("loanID"=>$loanid);
		if(strtolower($status) == 'approved'){
			$data = array("status"=> $status,
					"dateApproved"=>$this->ci->auth->localtime(),
					"approvedBy"=>$this->ci->auth->user_id(),
					"active"=>1);
		}else{
			$data = array("status"=> $status,
					"dateModified"=>$this->ci->auth->localtime(),
					"modifiedBy"=>$this->ci->auth->user_id()
					);	
		}
		
		$this->ci->Loansmodel->update_data($table, $id, $data);
	}
	
	function loanrelease(){
		$loanid = $_POST['loanid'];
		$amount = $_POST['amount'];
		$id = array("loanID"=>$loanid);
		$table = "loanapplication";
		$data = array("status"=> 'current',
							"AmountDisbursed"=>$amount,
							"DateDisbursed"=>$this->ci->auth->localtime(),
							"DisbursedBy"=>$this->ci->auth->user_id());
		$this->ci->Loansmodel->update_data($table, $id, $data);
		return true;
	}
	
	function clientstatusonLoan($loantype, $client){
		$data = array("LoanType"=>$loantype,
						"ClientID"=>$client);
		$data2 = array("LNTYPE"=>$loantype,
						"CNO"=>$client);
		$status = $this->ci->Loansmodel->get_data_from("loanapplication", $data);
		$oldstatus = $this->ci->Loansmodel->get_data_from("loanrecords", $data2);
		if($status->num_rows() > 1 or $oldstatus->num_rows > 0)
		return "existing";
		else
		return "new";	
	}
	
	function maxloanOnPension($clientid){
		$sql = "SELECT * FROM clientinfo "
					."JOIN pensioninfo ON pensioninfo.`clientID` = clientinfo.`ClientID`"
					."WHERE clientinfo.`ClientID` = '".$clientid."'";
		$res = $this->ci->db->query($sql);
		
		if($res->num_rows() >0){
			$cli = $res->row();
			//get age
			$age = $this->get_age($cli->dateOfBirth);
			if ($age <= 70){
				$terms = 24;				
			}else {
				$terms = 18;
			}
			
			$maxloan = ($cli->monthlyPension -100) * $terms;			
			
			return $maxloan;
		}else{
			return $maxloan=0;
		}
	}
	
	
	function updateSubmittedReqs($loanID){
		//$loanID = $_POST['loanid'];
		$updatedata[] = array();
		$adddata[] = array();
		if(isset($_POST['reqID'])){
		foreach($_POST['reqID'] as $req=>$val){
			$data = array("reqID"=>$req,
						"loanID"=>$loanID);
			$req_exist = $this->ci->Loansmodel->get_data_from("loanrequirements",$data);
			if($req_exist->num_rows() > 0){
				$reqvalue = $req_exist->row();
					//echo "Exist ".$req."=".$reqvalue->submitted;
				if($reqvalue->submitted != $val){
					//echo "<br/>Update to DB. value =  ".$val ;
					//echo "<br/>";
					if($val == 1)
					$submitted = $this->ci->auth->localtime();
					else
					$submitted = '';
					$data = array( "submitted"=>$val,
										"dateSubmitted"=>$submitted,
										"submittedTo"=>$this->ci->auth->user_id(),
										"dateModified"=>$this->ci->auth->localtime(),
										"modifiedBy"=>$this->ci->auth->user_id(),
										"active"=>1);
					$id = array("reqID"=>$req,
								"loanID"=>$loanID);
					$table = "loanrequirements";
					$this->ci->Loansmodel->update_data($table, $id, $data);
				}else{
					//echo "<br/>Dont update to DB. value =  ".$val ;
					//echo "<br/>";
				}
			}else{
				//add requirements
				//echo "add to db";
				if($val == 1)
				$submitted = $this->ci->auth->localtime();
				else
				$submitted = '';
				$data = array("reqID"=>$req,
									"loanID"=>$loanID,
										"submitted"=>$val,
										"dateSubmitted"=>$submitted,
										"submittedTo"=>$this->ci->auth->user_id(),
										"active"=>1);
				$this->ci->Loansmodel->addtotable('loanrequirements', $data);
				
			}
		}
		}
		//$this->ci->Loansmodel->addtotable('loanrequirements', $data);
	}
	
	function convert_number_to_words($number) {
    
		$hyphen      = '-';
		$conjunction = '  ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' &  ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'forty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);
		
		if (!is_numeric($number)) {
			return false;
		}
		
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . convert_number_to_words(abs($number));
		}
		
		$string = $fraction = null;
		
		if (strpos($number, '.') !== false) {
			$num = $number;
			list($number, $fraction) = explode('.', $number);
		}
		
		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= $this->convert_number_to_words($remainder);
				}
				break;
		}
		
		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			
			if(strlen($fraction) == 1)
				$fraction = $fraction.'0';
			$string .= $fraction."/100";
			//$words = array();
			//foreach (str_split((string) $fraction) as $number) {
				//$words[] = $dictionary[$number];
			//}
			//$string .= implode(' ', $words);
		}
		
		return $string;
	}
	
	function loancount($status){
		if($this->ci->auth->perms("CMC ALL Branches",$this->ci->auth->user_id(),3) == true)
			$data = array("loanapplication.status"=>$status,
							"loanapplication.active"=>1);
		else{
			$data = array("loanapplication.status"=>$status,
								"loanapplication.branchID"=> $this->ci->auth->branch_id(),
								"loanapplication.active"=>1);
		}		
		$loan = $this->ci->Loansmodel->get_data_from('loanapplication', $data);
		
		return $loan->num_rows();
	}
	
	function loanlist($status){
		$data = array("status"=>$status);
		$loan = $this->ci->Loansmodel->get_data_from('loanapplication', $data);
		
		 return $loan;
	}
	
	
	function assignpn(){
		$this->ci->form_validation->set_rules("bookpn", "PN", "required|xss_clean|callback_pnexist");
		if($this->ci->form_validation->run() == true){
			$data = array("PN"=>$_POST['bookpn'],
								"dateModified"=>$this->ci->auth->localtime(),
								"modifiedBy"=>$this->ci->auth->user_id());
			$id = array('loanID'=>$_POST['loanid']);
			$table = "loanapplication";
			$this->ci->Loansmodel->update_data($table, $id, $data);
			//$this->add_loanschedule($loan->Term, $amount, $loan->dateApplied, $_POST['bookpn']);			
			return true;
		}else{
			return false;
		}
	}
	
	function update_pension(){
		
		$this->ci->form_validation->set_rules("pensiontype","Pension By", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("pensionstatus","Pension Type", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("sss","SSS/GSIS Number", "required|xss_clean|trim|is_numeric");
		$this->ci->form_validation->set_rules("pension","Monthly Pension", "required|xss_clean|trim|is_numeric");
		$this->ci->form_validation->set_rules("sssdate","Date of Pension", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("bank","Bank", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("branch","Branch", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("accountnum","Bank Account", "required|xss_clean|trim|is_numeric");
		$this->ci->form_validation->set_rules("pensionid","Pension ID", "required|xss_clean|trim|is_numeric");
		
		if($this->ci->form_validation->run() == true){
			$data = array("PensionType"=>$_POST['pensiontype'],
								"PensionNum"=>$_POST['sss'],
								"monthlyPension"=>$_POST['pension'],
								"PensionStatus"=>$_POST['pensionstatus'],
								"pensionDate"=>$_POST['sssdate'],
								"BankID"=>$_POST['bank'],
								"BankAccount"=>$_POST['accountnum'],
								"bankBranch"=>$_POST['branch'],
								"atm_pb"=>$_POST['atm_pb'],
								"atmnum"=>$_POST['atmnum'],
								"dateModified"=>$this->ci->auth->localtime(),
								"modifiedBy"=>$this->ci->auth->user_id()
			);
			$id = array("PensionID"=>$_POST['pensionid']);
			$table = "pensioninfo";
			$this->ci->Loansmodel->update_data($table, $id, $data);
			return true;
		}else{
			return false;
		}
		
	}
	
	
} 
?>