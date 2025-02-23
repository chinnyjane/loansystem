<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product {

	function __construct(){
		$this->ci =& get_instance();		
	}
	
	function add_product(){
		$this->ci->form_validation->set_rules('pcode', "Product Code", "required");
		$this->ci->form_validation->set_rules('pname', "Product name", "required");
		$this->ci->form_validation->set_rules('pdesc', "Product Description", "required");
		$this->ci->form_validation->set_rules('psubcode', "Sub Code", "required");
		$this->ci->form_validation->set_rules('paymentterm', "Payment Method", "required|callback_product_exist");
		$this->ci->form_validation->set_rules('minAmount', "Minimum amount", "required|numeric|xss_clean");
		$this->ci->form_validation->set_rules('maxAmount', "Maximum Amount", "required|numeric|xss_clean");
		$this->ci->form_validation->set_rules('minTerm', "Minimum Term", "required|numeric|xss_clean");
		$this->ci->form_validation->set_rules('maxTerm', "Maximum Term", "required|numeric|xss_clean");
		//$this->ci->form_validation->set_rules('penalty', "Penalty", "required|numeric|xss_clean");
		if($this->ci->form_validation->run() == true)
		{
			$hash = md5($_POST['pcode'].substr($this->ci->auth->localtime(), 0,10).$this->ci->auth->user_id());
			$data = array("productID"=>$_POST['pcode'],
								"LoanName"=>$_POST['pname'],
								"LoanCode"=>$_POST['pname'],
								"LoanSubCode"=>$_POST['psubcode'],
								"PaymentTerm"=>$_POST['paymentterm'],
								"LoanDescription"=>$_POST['pdesc'],
								"computation"=>$_POST['computation'],
								"minAmount"=>$_POST['minAmount'],
								"maxAmount"=>$_POST['maxAmount'],
								"minTerm"=>$_POST['minTerm'],
								"maxTerm"=>$_POST['maxTerm'],
								"active"=>1,
								"addedBy"=>$this->ci->auth->user_id(),								
								"inlineHash" => $hash,
								"dateAdded"=>$this->ci->auth->localtime());
			$table = 'loantypes';
			$this->ci->Loansmodel->addtotable($table, $data);
			
			return true;
		}else{
			return false;
		}
	
	}
	
	function update_product($pid){
		$this->ci->form_validation->set_rules('pcode', "Product Code", "required");
		$this->ci->form_validation->set_rules('productID', "Product name", "required");
		$this->ci->form_validation->set_rules('pdesc', "Product Description", "required");
		$this->ci->form_validation->set_rules('psubcode', "Sub Code", "required");
		$this->ci->form_validation->set_rules('paymentterm', "Payment Method", "required");
		$this->ci->form_validation->set_rules('minAmount', "Minimum amount", "required|numeric|xss_clean");
		$this->ci->form_validation->set_rules('maxAmount', "Maximum Amount", "required|numeric|xss_clean");
		$this->ci->form_validation->set_rules('minTerm', "Minimum Term", "required|numeric|xss_clean");
		$this->ci->form_validation->set_rules('maxTerm', "Maximum Term", "required|numeric|xss_clean");
		//$this->ci->form_validation->set_rules('penalty', "Penalty", "required|numeric|xss_clean");
		if($this->ci->form_validation->run() == true)
		{
			$hash = md5($_POST['pcode'].substr($this->ci->auth->localtime(), 0,10).$this->ci->auth->user_id());
			$data = array("LoanCode"=>$_POST['pcode'],
								"productID"=>$_POST['productID'],
								"LoanSubCode"=>$_POST['psubcode'],
								"PaymentTerm"=>$_POST['paymentterm'],
								"LoanDescription"=>$_POST['pdesc'],
								"minAmount"=>$_POST['minAmount'],
								"maxAmount"=>$_POST['maxAmount'],
								"minTerm"=>$_POST['minTerm'],
								"maxTerm"=>$_POST['maxTerm'],
								"computation"=>$_POST['computation'],
								"active"=>$_POST['active'],
								"inlineHash"=>$hash,
								"ModifiedBy"=>$this->ci->auth->user_id(),
								"dateModified"=>$this->ci->auth->localtime());
			$where = array('loanTypeID'=>$pid);
			$table = 'loantypes';
			if($this->ci->Loansmodel->update_data($table, $where, $data) == true)
			return true;
		}else{
			return validation_errors();
		}
	
	}	
	
	function removefees($pid){
			//remove fees
			if(isset($_POST['remove'])){
				foreach ($_POST['remove'] as $rem){
					$data = array("active"=>0,
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id());
					$where = array("feeID" => $rem);
					$this->ci->Loansmodel->update_data("productfees", $where, $data);
				}
				return true;
			}
			
	}
	
	function removeci($pid){
			//remove fees
			if(isset($_POST['ciremove'])){
				foreach ($_POST['ciremove'] as $ci){
					$data = array("active"=>0,
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id());
					$where = array("ci_id" => $ci);
					$this->ci->Loansmodel->update_data("ci_and_appraisal", $where, $data);
				}
				return true;
			}
			
	}
	
	function ciupdates($pid){
		$this->removeci($pid);
		if(isset($_POST['ciname'])){			
			$ciname = $_POST['ciname'];
			$citype = $_POST['citype'];
			$cidata = array();
			foreach($ciname as $key=>$value){
				//check duplicate
				$data = array("ci_name"=>$value,
							"productid"=>$pid,
							"active"=>1);
				
				if($this->ci->Loansmodel->fieldIn("ci_and_appraisal",$data) == true){
					return false;
				}else{
					$cidata[] = array("productid"=>$pid,
									"ci_name"=>$value,
									"datatype"=>$citype[$key],
									"dateAdded"=>$this->ci->auth->localtime(),
									"addedBy"=>$this->ci->auth->user_id(),
									"active"=>1);				
				}
			}			
			$this->ci->db->insert_batch('ci_and_appraisal', $cidata);
		}
		
		if(isset($_POST['ci'])){
			foreach($_POST['ci'] as $cid=>$n){
				$data = array("ci_name"=>$n['name'],
									"datatype"=>$n['type'],
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id());	
				$where = array("ci_id"=>$cid);
				$this->ci->Loansmodel->update_data("ci_and_appraisal", $where, $data);
			}
		}
	}
	
	function addfees($pid){	
			
			//add fees
			
			if(isset($_POST['feename'])){									
				if($this->feeexist() != false){
					if(count($_POST['feename']) > 0){
						$fname = $_POST['feename'];
						$ftype = $_POST['feetype'];
						$fvalue = $_POST['feevalue'];

						foreach($fname as $key=>$value){
							$data = array("productID"=>$pid,
													"feeName"=>$value,
													"comptype"=>$ftype[$key],
													"value"=>$fvalue[$key],
													"dateAdded"=>$this->ci->auth->localtime(),
													"addedBy"=>$this->ci->auth->user_id(),
													"active"=>1);							
							if(!empty($value))
							$this->ci->db->insert('productfees', $data); 
						}						
					}
					return true;
				}else return false;
			}	
			
			
			
	}
	function feeexist(){
	
		if(count($_POST['feename']) > 0){
			$fname = $_POST['feename'];
			$ftype = $_POST['feetype'];
			$fvalue = $_POST['feevalue'];

			foreach($fname as $key=>$value){
				$data = array("productID"=>$_POST['pid'],
									"feeName"=>$value,									
									"active"=>1);
				$table = "productfees";
				$fee = $this->ci->Loansmodel->get_data_from($table, $data);
				if($fee->num_rows() > 0)
				{
					$error[] = "Fee Name ".$value." already exists.";
				}
				
			}			 
			 
		}
		
		if(isset($error))
		{
			$this->ci->form_validation->set_message("feeexist", $error);
			return false;					
		}else return true;
		
	}
	
	function updatefees($pid){
		
		
			//update fees
			if(isset($_POST['fee'])){
				foreach ($_POST['fee'] as $feeID=>$f){
					$where = array("feeID"=>$feeID);
					$data = array("comptype"=>$f['type'],
									"fee_account_id"=>$f['fee_account_id'],
									"charge_type_ID"=>$f['charge'],
									"value"=>$f['value'],
									"display"=>$f['display'],
									"upfront"=>$f['upfront'],
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id());
					$this->ci->Loansmodel->update_data("productfees", $where, $data);
				}	
			return true;				
			}
		
	}
	
	function requirements($pid)
	{
			
			//add requirements			
			if(isset($_POST['requirement'])){
				if(count($_POST['requirement']) > 0){
					foreach($_POST['requirement'] as $req){
						$data = array("requirement"=>$req,
												"productID"=>$pid,
												"dateAdded"=>$this->ci->auth->localtime(),
												"addedBy"=>$this->ci->auth->user_id(),
												"active"=>1);
						if(!empty($req))
						$this->ci->db->insert('productrequirements', $data); 
					}
					
				}
			} 
			
			//update requirements
			if(isset($_POST['req'])){
				foreach($_POST['req'] as $reqID=>$req){
					$data = array("requirement"=>$req,
										"dateModified"=>$this->ci->auth->localtime(),
											"modifiedBy"=>$this->ci->auth->user_id());
					$where = array("reqID"=>$reqID);
					$this->ci->Loansmodel->update_data("productrequirements", $where, $data);
				}
			}
			//remove requirements
			if(isset($_POST['reqremove'])){
				foreach ($_POST['reqremove'] as $reqID){
					$data = array("active"=>0,
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id());
					$where = array("reqID" => $reqID);
					$this->ci->Loansmodel->update_data("productrequirements", $where, $data);
				}
			}
			
			return true;
	}
	
	function verifyamount(){
		if($_POST){
			$loan = $_POST['loanapplied'];
			$terms = $_POST['terms'];
			$monthly = $loan/$terms;		
			$pension = $_POST['pension']['pension'];
			
			if($monthly > $pension){
				$this->form_validation->set_message("Amount applied is not valid","verifyamount");
				return false;
			}else {
			  return true;
			 }
		}
	 }
	
	
	function validationpension(){
		
	}
	
	
	function validateform(){	
		//LOAN INFO
		$this->ci->form_validation->set_rules("loanapplied", "Amount applied", "required|trim|numeric|callback_verifyamount");
		$this->ci->form_validation->set_rules("terms", "Terms", "required|trim|numeric");
		
		//PENSION INFO
		$pension = $this->ci->Loansmodel-> get_pensioninfo($_POST['clientid']);
		if($pension->num_rows() <= 0){
			if(isset($_POST['pension'])){				
					$this->ci->form_validation->set_rules("pension[pensiontype]", "Pension by", "required");
					$this->ci->form_validation->set_rules("pension[pensionstatus]", "Type of Pension", "required");
					$this->ci->form_validation->set_rules("pension[sss]", "SSS/GSIS Number", "is_numeric|min_length[10]|required|is_unique[pensioninfo.PensionNum]");
					$this->ci->form_validation->set_rules("pension[pension]", "Monthly Pension", "callback_money_multi");
					$this->ci->form_validation->set_rules("pension[branch]", "Bank branch", "required");
					$this->ci->form_validation->set_rules("pension[accountnum]", "Bank account number", "required");				
			}	
		}
		
		//CO-MAKER
		if($_POST['civilstatus'] != 'married'){
			$this->ci->form_validation->set_rules('comaker[firstname]', "Comaker's Firstname", "required");
			$this->ci->form_validation->set_rules('comaker[mname]', "Comaker's Middlename", "required");
			$this->ci->form_validation->set_rules('comaker[lname]', "Comaker's Last Name", "required");
			$this->ci->form_validation->set_rules('comaker[dob]', "Comaker's Birthday", "required");
			$this->ci->form_validation->set_rules('comaker[contact]', "Comaker's Contact", "required");
			$this->ci->form_validation->set_rules('comaker[civilstatus]', "Comaker's Civil Status", "required");
			$this->ci->form_validation->set_rules('comaker[gender]', "Comaker's gender", "required");
			$this->ci->form_validation->set_rules('comaker[province]', "Comaker's Province", "required");
			$this->ci->form_validation->set_rules('comaker[city]', "Comaker's City", "required");
			$this->ci->form_validation->set_rules('comaker[barangay]', "Comaker's Barangay", "required");
			$this->ci->form_validation->set_rules('comaker[address]', "Comaker's address", "required");		
		}
		
		if($this->ci->form_validation->run() == false)
			return false;
		else{
			return true;
		}
		
	}
	
	function getpensionamount($pensionID){
		$data = array("PensionID"=>$pensionID);
		$pen = $this->ci->Loansmodel->get_data_from("pensioninfo", $data);
		
		if($pen->num_rows() > 0){
			$pension = $pen->row();
			$amount = $pension->monthlyPension;
		}else{
			$amount = 0;
		}
		
		return $amount;
	}
	
	
	function CollateralsDetails($pid){
		
		//add collateral name 
		if(isset($_POST['coldetail'])){
			$colname = $_POST['coldetail'];
			$coltype = $_POST['coltype'];
			
			if(isset($_POST['colID']))
				$colID = $_POST['colID'];
				
			if(isset($_POST['colremove']))
				$remID = $_POST['colremove'];
			
			foreach($colname as $key=>$value){
				
				if(isset($colID[$key])){
					if(isset($remID[$key]))
					{
						$data = array("active"=>0);
						$id = array("procolID"=>$remID[$key]);
					}else{
						if(isset($_POST['pri'])){	
							if($_POST['pri'] == $colID[$key])
								$primary = 1;
							else
								$primary = 0;
						}else 
						$primary = 0;
							
							$data = array(
								"primary"=> $primary,
								"productID"=>$pid,
								"collateralname"=>$value,
								"datatype"=>$coltype[$key],
								"dateModified"=>$this->ci->auth->localtime(),
								"modifiedBy"=>$this->ci->auth->user_id());
						
						$id = array("procolID"=>$colID[$key]);
					}
										
					$this->ci->Loansmodel->update_data("productcollateral", $id, $data);
				}else{				
					$data = array("productID"=>$pid,
								"collateralname"=>$value,
								"active"=>1);
					
					if($this->ci->Loansmodel->fieldIn("productcollateral", $data) == true){
						//update
					}else{
						$data = array("productID"=>$pid,
								"collateralname"=>$value,
								"datatype"=>$coltype[$key],
								"dateAdded"=>$this->ci->auth->localtime(),
								"addedBy"=>$this->ci->auth->user_id(),
								"active"=>1);
						if(!empty($value))
						$this->ci->Loansmodel->addtotable("productcollateral", $data);
					}
				}
			}
		}
	}
	
	function updateinterest($pid){	
		
		
		if(isset($_POST['interestID'])){
			$interestid = $_POST['interestID'];
			$term = $_POST['term'];
			$interest = $_POST['interest'];
			
			foreach($interestid as $k=>$id){				
				$data = array("productID"=>$pid,
									'term'=>$term[$id],
									"interest"=>$interest[$id],
									"interestID <>"=>$id	);
				
				if($this->ci->Products->checkInterestExist($data) == false)
				{
					if(isset($_POST['intremove'][$id]))
						$active = 0;
					else $active = 1;
					//echo 'not exist'.$id.$term[$id];
					$data2 = array("productID"=>$pid,
									'term'=>$term[$id],
									"interest"=>$interest[$id],
									"dateModified"=>$this->ci->auth->localtime(),
									"modifiedBy"=>$this->ci->auth->user_id(),
									"active"=>$active);
					$where = array("interestID"=>$id);
					
					$this->ci->Products->updateInterest($data2,$where);
				
				}
				
				
			}
		}
		
		if(isset($_POST['newterm'])){
			$term = $_POST['newterm'];
			$interest = $_POST['newinterest'];
			
			foreach($term as $key=>$tm){
				$int = $interest[$key];
				
				if(!empty($int) and !empty($tm)){
					//echo $int."-".$tm;
					$data = array("productID"=>$pid,
										'term'=>$tm,
										"interest"=>$int,
										"dateAdded"=>$this->ci->auth->localtime(),
										"addedBy"=>$this->ci->auth->user_id(),
										"active"=>1);
					if($this->ci->Products->checkInterestExist($data) == false)
					{
						$this->ci->Products->addInterestByPID( $data);
					}
				}
			}
		}
		
	}
}

?>