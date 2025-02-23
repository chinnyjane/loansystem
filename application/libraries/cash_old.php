<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cash {
	function __construct(){
		$this->ci =& get_instance();		
	}
	
	function addBank(){
		$this->ci->input->post(NULL,true);
		$this->ci->form_validation->set_rules("bankcode","Bank Code", "required|is_unique[banks.bankCode]|xss_clean");
		$this->ci->form_validation->set_rules("bankName","Bank Name", "required|xss_clean");
		$this->ci->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
		if($this->ci->form_validation->run() != False){
			$data = array("bankCode"=>$_POST['bankcode'],
								"bankName"=>$_POST['bankName'],
								"dateAdded" => $this->ci->auth->localtime(),
								"addedBy"=>$this->ci->auth->user_id(),
								"active" => 1);
			if($this->ci->UserMgmt->insert_data_to('banks',$data) != false)
			return true;
			else
			return false;
		}
	}
	
	function updateBankStatus($stat){
		foreach($_POST['checked'] as $ch){
			$data[] = array("bankID"=>$ch,
								"active"=>$stat,
								"dateModified"=>$this->ci->auth->localtime(),
								"modifiedBy" => $this->ci->auth->user_id());
		}
		$table='banks';
		$this->ci->Cashmodel->updateBatch($table, $data,'bankID');
	}
	
	
	function addBanktoBranch(){		
		$this->ci->input->post(NULL,true);
		$this->ci->form_validation->set_rules("bankID","Bank", "required|xss_clean");
		$this->ci->form_validation->set_rules("bankAccount","Bank Account", "required|xss_clean|is_numeric|callback_accountExist");
		$this->ci->form_validation->set_rules("BeginningBal","Beginning balance", "required|xss_clean|is_numeric");
		$this->ci->form_validation->set_rules("BeginningDate","Beginning Date", "required|xss_clean|regex_match[/^(19|20)\d\d([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])$/]");
		$this->ci->form_validation->set_rules("bankAddress","Bank Address", "required|xss_clean");
		$this->ci->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
		if($this->ci->form_validation->run() != false){
			$data = array("bankID"=> $_POST['bankID'],
						"bankAccount" => $_POST['bankAccount'],
						"bankBranch" => $_POST['bankBranch'],
						"bankAddress" => $_POST['bankAddress'],
						"BeginDate" => $_POST['BeginningDate'],
						"BeginBalance" => $_POST['BeginningBal'],
						"branchID" => $_POST['branchid'],
						"dateAdded"=>$this->ci->auth->localtime(),
						"addedBy"=>$this->ci->auth->user_id(),
						"active"=>1);
			/*$data = array("bankID" => $_POST['bankID'],
							"branchID" => $this->ci->auth->branch_id(),
							"transID" => $transid,
							"bankAccount" => $_POST['bankAccount'],
							"BeginningBal" => $_POST['BeginningBal'],
							"EndingBal" => $_POST['BeginningBal'],
							"dateAdded" => $this->ci->auth->localtime(),
							"addedBy" => $this->ci->auth->user_id(),
							"active" => 1);*/
			if($this->ci->UserMgmt->insert_data_to('bankofbranch',$data) != false){			
				return true;
			}else
			return false;
		}		
	}
	
	function bankonBranch(){
		$data = array("bankID" => $_POST['bankID'],
								"active" => 1);
		if($this->Loansmodel->get_data_from('branchbanks', $data)->num_rows() > 0)
		return false;
		else return true;
	}
	
	function accountExist(){
		$data = array("bankAccount" => $_POST['bankAccount'],
								"active" => 1);
		if($this->ci->Loansmodel->get_data_from('bankofbranch', $data)->num_rows() > 0){
			$this->ci->form_validation->set_message('accountExist', "Account already exists.");
		return false;
		}else return true;
	}
	
	function getTotalBal($branchID){
		$bank = $this->ci->Cashmodel->getbanklist($branchID);
		if($bank->num_rows() > 0)
		{
			$total = 0;
			foreach($bank->result() as $b){
				$total+= $b->bankBalance;
			}
			return $total;
			
		}else{
			return 0;
		}
	}
	
	function getTransaction($date, $branch){
		if($date=='')
		$data = array("branchID"=>$branch);
		else
		$data = $date;
		$rec = $this->ci->Loansmodel->get_data_from('cmctransaction', $data);
		if($rec->num_rows() >0)
		{
			return $rec;
		}else{
			return false;
		}
	}
	
	function getTotalCollections($transID){
		
	}
	
	function getTotalBalance($transID){
	
	}
	
	function getTotalDisbursed($transID){
	
	}
	function bankColl($bankID,$transID){
		$data = array("transID"=>$transID,
					 "branchBankID" => $bankID);
		if($this->ci->Loansmodel->get_data_from('bankstransactions',$data)->num_rows() > 0)
		return 0;
		else return 0;
	}
	
	function checkOpenTrans($branch){
		$data = array("branchID"=>$branch,
					"status" => 'open',
					"dateClose" => '0000-00-00');
		if($this->ci->Loansmodel->get_data_from('cmctransaction',$data)->num_rows() > 0)
		return true;
		else return false;
	}
	function checkTransToday($branchid,$date){
		//$date = $this->ci->auth->localdate();
		$data = array("dateTransaction"=>$date,
						"branchID"=>$branchid);
		if($this->ci->Loansmodel->get_data_from('cmctransaction',$data)->num_rows() > 0)
		return true;
	}
	
	function checkOpenTransbefore($date,$branch){
		$data = array("branchID"=>$branch,
					"status" => 'open',
					"dateTransaction <" => $date,
					"isdeleted" => 0 );
		if($this->ci->Loansmodel->get_data_from('cmctransaction',$data)->num_rows() > 0)
		return true;
		else return false;
	}
	
	function updateCMCStatus(){
		if(isset($_POST['update'])){
			$sub = $_POST['update'];
		
			switch ($sub){
				case "LOCK CMC" :
						$transid=$_POST['transid'];
						echo "Lock";
						return $this->openlocktransaction($transid, 'lock');
						exit();
				break;
				case "OPEN CMC" :
						$transid=$_POST['transid'];
						return $this->openlocktransaction($transid, 'open');
						exit();
				break;
				case "Verify CMC" :
					$where2 = array("transID"=>$_POST['transid'],
									"verified" => null);
					$data2 = array("dateVerified"=>$this->ci->auth->localtime(),
								"verifiedBy"=>$this->ci->auth->user_id(),
								"verified"=>'2');
					$this->ci->Loansmodel->update_data('bankstransactions', $where2, $data2);
					$data = array("dateVerified"=>$this->ci->auth->localtime(),
						"verifiedBy"=>$this->ci->auth->user_id(),
						"status"=>'verified');
				break;
				case "Approve CMC" :
					$data = array("dateApproved"=>$this->ci->auth->localtime(),
						"approvedBy"=>$this->ci->auth->user_id(),
						"status"=>'approved');
				break;
				case "Recompute CMC";
					$transid=$_POST['transid'];
					return $this->recompute($transid);
					exit();
				break;
			}
			
			$where = array("transID"=>$_POST['transid']);
							
			if($this->ci->Loansmodel->update_data('cmctransaction', $where, $data) == true)
				return true;
			else 
				return false;
		}
	}
	
	//BATCH VERIFICATION == 2
	function verifyTrans(){	
		$where2 = array("transID"=>$_POST['transid'],
								"verified" => null);
		$data2 = array("dateVerified"=>$this->ci->auth->localtime(),
					"verifiedBy"=>$this->ci->auth->user_id(),
					"verified"=>'2');
		$this->ci->Loansmodel->update_data('bankstransactions', $where2, $data2);
	
		//verfiy whole transaction
		$where = array("transID"=>$_POST['transid']);
		$data = array("dateVerified"=>$this->ci->auth->localtime(),
					"verifiedBy"=>$this->ci->auth->user_id(),
					"status"=>'verified');
					
		if($this->ci->Loansmodel->update_data('cmctransaction', $where, $data) == true)
			return true;
		else 
			return false;
	}
	
	//INDIVIDUAL VERIFICATION ==1
	function verifyEachTrans($banktransid){
		$where = array("BanktransID"=>$banktransid);
		$data2 = array("dateVerified"=>$this->ci->auth->localtime(),
					"verifiedBy"=>$this->ci->auth->user_id(),
					"verified"=>'1');
		if($this->ci->Loansmodel->update_data('bankstransactions', $where, $data2) == true)
			return true;
		else 
			return false;
	}
	
	function getBalanceofBranch($branch){
		$data = array("branchID"=>$branch);
		
	}
	
	function checkBankBal($date,$branchBankID){
		//check on DB Bank trans		
		$banktr = $this->ci->Cashbalance->EndOfDateBalance($date,$branchBankID);
		if($banktr->num_rows >0){
			$beg = $banktr->row();
			$adj = $beg->TotalAdjadd + (-1 * $beg->TotalAdjless);
			$res['begin'] = $beg->BeginBal;
			$res['totalcol'] = $beg->TotalCol;
			$res['totaldis'] = $beg->TotalDis;
			$res['totaladj'] = $adj;
			$res['end'] = $beg->BeginBal + $beg->TotalCol - $beg->TotalDis + $adj;
			return $res;
		}else return false;
	}	
	
	function addCollection($transid){
		$this->ci->form_validation->set_rules("particular", "Collection Name", "required|xss_clean");
		$this->ci->form_validation->set_rules("amount", "Amount", "required|xss_clean|is_numeric");
		$this->ci->form_validation->set_rules("reference", "OR Number", "required|xss_clean|calback_orcheck");
		$this->ci->form_validation->set_rules("paymentType", "paymentType", "required|xss_clean");
		$this->ci->form_validation->set_rules("transtype", "Collection Type", "required|xss_clean");
		if($this->ci->form_validation->run() !=false){
			//echo 'ok';
			$bal = $this->checkBankBal($_POST['transdate'],$_POST['bankID']);
			$begin =  $bal['begin'];
			$in = $_POST['amount'];
			$col = $bal['totalcol'] + $in;
			$end = $bal['end'] + $in;
			//echo $begin;
			if($begin > 0){
			//echo 'ok2';
				$data = array("amount_in"=>$_POST['amount'],
							"transType"=>$_POST['transtype'],
							"paymentType"=>$_POST['paymentType'],
							"Particulars"=>$_POST['particular'],
							"referenceNo"=>$_POST['reference'],
							"PN"=>$_POST['PN'],
							"TransID"=>$transid,
							"branchBankID"=>$_POST['bankID'],
							"dateOfTransaction"=>$_POST['transdate'],
							"dateAdded"=>$this->ci->auth->localtime(),
							"addedBy"=>$this->ci->auth->user_id());
				$trans_id = $this->ci->UserMgmt->insert_data_to('bankstransactions',$data);
				if($trans_id != false){
					$where = array("transID"=>$transid,
								   "branchbankID"=>$_POST['bankID'],
								   "dateOfTransaction"=>$_POST['transdate']);
					$bankdetails = $this->ci->Loansmodel->get_data_from('banksummary',$where);
					if($bankdetails->num_rows() > 0)
					{
						//UPDATE BANK SUMMARY
						$data = array("EndingBal"=>$end,
									"beginningBal"=>$begin,
									"TotalCollections"=>$col,
									 "dateModified"=>$this->ci->auth->localtime(),
									 "modifiedBy"=>$this->ci->auth->user_id());
						$where = array("transID"=>$transid,
								   "branchbankID"=>$_POST['bankID'],
								   "dateOfTransaction"=>$_POST['transdate']);
						$this->ci->Loansmodel->update_data('banksummary', $where, $data);
					}else{
						//INSERT DATA to BANK SUMMARY TABLE
						$data = array("transID"=>$transid,
									"branchbankID"=>$_POST['bankID'],
									"beginningBal"=>$begin,
									"EndingBal"=>$end,
									"TotalCollections"=>$col,
									 "dateOfTransaction"=>$_POST['transdate'],
									 "dateAdded"=>$this->ci->auth->localtime(),
									 "addedBy"=>$this->ci->auth->user_id(),
									 "active"=>1);
						$banksum = $this->ci->UserMgmt->insert_data_to('banksummary',$data);
					}
				return true ;
				}else
				return false;
			}else return false;
		}else return false;
	}
	
	function UpdateCollection($transid){
		$this->ci->form_validation->set_rules("particular", "Collection Name", "required|xss_clean");
		$this->ci->form_validation->set_rules("amount", "Amount", "required|xss_clean|is_numeric");
		$this->ci->form_validation->set_rules("reference", "OR Number", "required|xss_clean");
		$this->ci->form_validation->set_rules("paymentType", "paymentType", "required|xss_clean");
		$this->ci->form_validation->set_rules("PN", "PN No", "required|xss_clean");
		$this->ci->form_validation->set_rules("bankID", "Bank", "required|xss_clean");
		$this->ci->form_validation->set_rules("transtype", "Collection Type", "required|xss_clean");
		$this->ci->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
		if($this->ci->form_validation->run() !=false){			
			$in = $_POST['amount'];	
				$where = array("BanktransID"=>$transid);
				$data = array("amount_in"=>$_POST['amount'],
							"transType"=>$_POST['transtype'],
							"paymentType"=>$_POST['paymentType'],
							"Particulars"=>$_POST['particular'],
							"referenceNo"=>$_POST['reference'],
							"PN"=>$_POST['PN'],
							"branchBankID"=>$_POST['bankID'],
							"dateModified"=>$this->ci->auth->localtime(),
							"modifiedBy"=>$this->ci->auth->user_id());
				$trans_id = $this->ci->Loansmodel->update_data('bankstransactions', $where, $data);
				if($trans_id != false){		
				return true ;
				}else
				return false;			
		}else return false;
	}
	
	function removeTrans($transid){
		$where = array("BanktransID"=>$transid);
		$data = array("isdeleted"=>'1');
		if($this->ci->Cashmodel->removeTrans($where, $data) == true)
		return true;
		else return false;
	}
	
	function updateDisbursement($transid){
		$this->ci->form_validation->set_rules("particular", "Payee", "required|xss_clean");
		$this->ci->form_validation->set_rules("explanation", "Explanation", "xss_clean");
		$this->ci->form_validation->set_rules("amount", "Amount", "required|xss_clean|trim|is_numeric");
		$this->ci->form_validation->set_rules("checkno", "Check No", "required|xss_clean|trim|is_numeric");
		$this->ci->form_validation->set_rules("reference", "CV Number", "required|xss_clean");
		$this->ci->form_validation->set_rules("PN", "PN No", "xss_clean");
		if($this->ci->form_validation->run() !=false){
		echo "444";
			$where = array("BanktransID"=>$transid);			
			$out = $_POST['amount'];
			$data = array("Amount_OUT"=>$out,
						"transType"=>$_POST['transtype'],
						"explanation"=>$_POST['explanation'],
						"Checkno"=>$_POST['checkno'],
						"referenceNo"=>$_POST['reference'],
						"PN"=>$_POST['PN'],
						"branchBankID"=>$_POST['bankID'],
						"Particulars"=>$_POST['particular'],
						"dateModified"=>$this->ci->auth->localtime(),
						"modifiedBy"=>$this->ci->auth->user_id());
				$trans_id = $this->ci->Loansmodel->update_data('bankstransactions', $where, $data);
				if($trans_id != false){					
				return true ;
				}else return false;
			}else return false;	
	}
	
	function addDisbursement($transid){
		$this->ci->form_validation->set_rules("particular", "Payee", "required|xss_clean");
		$this->ci->form_validation->set_rules("explanation", "Explanation", "required|xss_clean");
		$this->ci->form_validation->set_rules("amount", "Amount", "required|xss_clean|trim|is_numeric");
		$this->ci->form_validation->set_rules("checkno", "Check No", "required|xss_clean|trim|is_numeric");
		$this->ci->form_validation->set_rules("PN", "PN No", "xss_clean|trim");
		$this->ci->form_validation->set_rules("reference", "CV Number", "required|xss_clean|callback_cvcheck");
		if($this->ci->form_validation->run() !=false){
			$bal = $this->checkBankBal($_POST['transdate'],$_POST['bankID']);
			$begin =  $bal['begin'];
			$out = $_POST['amount'];
			$dis = $bal['totaldis'] + $out;
			$end = $bal['end'] - $out;
			if($begin > 0){
				$data = array("amount_out"=>$out,
								"transType"=>$_POST['transtype'],
								"explanation"=>$_POST['explanation'],
								"Checkno"=>$_POST['checkno'],
								"referenceNo"=>$_POST['reference'],
								"TransID"=>$transid,
								"PN"=>$_POST['PN'],
								"branchBankID"=>$_POST['bankID'],
								"Particulars"=>$_POST['particular'],
								"dateOfTransaction"=>$_POST['transdate'],
								"dateAdded"=>$this->ci->auth->localtime(),
								"addedBy"=>$this->ci->auth->user_id());
				$trans_id = $this->ci->UserMgmt->insert_data_to('bankstransactions',$data);
				if($trans_id != false){
					$where = array("transID"=>$transid,
								   "branchbankID"=>$_POST['bankID'],
								   "dateOfTransaction"=>$_POST['transdate']);
					$bankdetails = $this->ci->Loansmodel->get_data_from('banksummary',$where);
					if($bankdetails->num_rows() > 0)
					{
						//UPDATE BANK SUMMARY
						$data = array("EndingBal"=>$end,
									"beginningBal"=>$begin,
									"TotalDisbursement"=>$dis,
									 "dateModified"=>$this->ci->auth->localdate(),
									 "modifiedBy"=>$this->ci->auth->user_id());
						$where = array("transID"=>$transid,
								   "branchbankID"=>$_POST['bankID'],
								   "dateOfTransaction"=>$_POST['transdate']);
						$this->ci->Loansmodel->update_data('banksummary', $where, $data);
					}else{
						//INSERT DATA to BANK SUMMARY TABLE
						$data = array("transID"=>$transid,
									"branchbankID"=>$_POST['bankID'],
									"beginningBal"=>$begin,
									"EndingBal"=>$end,
									"TotalDisbursement"=>$dis,
									 "dateOfTransaction"=>$_POST['transdate'],
									 "dateAdded"=>$this->ci->auth->localdate(),
									 "addedBy"=>$this->ci->auth->user_id(),
									 "active"=>1);
						$banksum = $this->ci->UserMgmt->insert_data_to('banksummary',$data);
					}
				return true ;
				}else
				return false;
			}else return false;
		}else return false;
	}
	
	function updateAdjustment($transid){
		$this->ci->form_validation->set_rules("particular", "Particulars", "required|xss_clean");
		$this->ci->form_validation->set_rules("amount", "Amount", "required|xss_clean|is_numeric");
		$this->ci->form_validation->set_rules("reference", "JV Number", "required|xss_clean");
		$this->ci->form_validation->set_rules("transtype", "Adjustment Type", "required|xss_clean");
		$this->ci->form_validation->set_rules("addorless", "Add or Less", "required|xss_clean");
		$this->ci->form_validation->set_rules("bankID", "Bank", "required|xss_clean");
		$this->ci->form_validation->set_rules("explanation", "Bank", "xss_clean");
		if($this->ci->form_validation->run() !=false){
			$adj = $_POST['amount'];
			if($_POST['addorless'] == "add"){				
				$amount = "Amount_IN";
			}elseif($_POST['addorless'] == "less"){
				$amount = "Amount_OUT";
			}
			$where = array("BanktransID"=>$transid);	
			$data = array($amount=>$adj,
						"transType"=>$_POST['transtype'],
						"referenceNo"=>$_POST['reference'],
						"branchBankID"=>$_POST['bankID'],
						"Particulars"=>$_POST['particular'],					
						"explanation"=>$_POST['explanation'],					
						"dateModified"=>$this->ci->auth->localtime(),
						"modifiedBy"=>$this->ci->auth->user_id());
			$trans_id = $this->ci->Loansmodel->update_data('bankstransactions', $where, $data);
				if($trans_id != false){					
				return true ;
				}else
				return false;			
		}else return false;
	}
	
	function addAdjustment($transid){
		$this->ci->form_validation->set_rules("particular", "Particulars", "required|xss_clean");
		$this->ci->form_validation->set_rules("amount", "Amount", "required|xss_clean|is_numeric");
		$this->ci->form_validation->set_rules("reference", "JV Number", "required|xss_clean");
		if($this->ci->form_validation->run() !=false){
			$bal = $this->checkBankBal($_POST['transdate'],$_POST['bankID']);
			$begin =  $bal['begin'];
			$adj = $_POST['amount'];
			if($_POST['addorless'] == "add"){				
				$adjusted = $bal['totaladj'] + $adj;
				$end = $bal['end'] + $adj;
				$amount = "amount_in";
			}elseif($_POST['addorless'] == "less"){
				$adjusted = $bal['totaladj'] - $adj;
				$end = $bal['end'] - $adj;
				$amount = "amount_out";
			}
			//echo $begin;			
			if($begin > 0){
			
				$data = array($amount=>$adj,
								"transType"=>$_POST['transtype'],
								"referenceNo"=>$_POST['reference'],
								"TransID"=>$transid,
								"branchBankID"=>$_POST['bankID'],
								"Particulars"=>$_POST['particular'],
								"explanation"=>$_POST['explanation'],
								"dateOfTransaction"=>$_POST['transdate'],
								"dateAdded"=>$this->ci->auth->localtime(),
								"addedBy"=>$this->ci->auth->user_id());
				$trans_id = $this->ci->UserMgmt->insert_data_to('bankstransactions',$data);
				if($trans_id != false){
					$where = array("transID"=>$transid,
								   "branchbankID"=>$_POST['bankID'],
								   "dateOfTransaction"=>$_POST['transdate']);
					$bankdetails = $this->ci->Loansmodel->get_data_from('banksummary',$where);
					if($bankdetails->num_rows() > 0)
					{
						//UPDATE BANK SUMMARY
						$data = array("EndingBal"=>$end,
									"beginningBal"=>$begin,
									"TotalAdjustment"=>$adjusted,
									 "dateModified"=>$this->ci->auth->localdate(),
									 "modifiedBy"=>$this->ci->auth->user_id());
						$where = array("transID"=>$transid,
								   "branchbankID"=>$_POST['bankID'],
								   "dateOfTransaction"=>$_POST['transdate']);
						$this->ci->Loansmodel->update_data('banksummary', $where, $data);
					}else{
						//INSERT DATA to BANK SUMMARY TABLE
						$data = array("transID"=>$transid,
									"branchbankID"=>$_POST['bankID'],
									"beginningBal"=>$begin,
									"EndingBal"=>$end,
									"TotalAdjustment"=>$adjusted,
									 "dateOfTransaction"=>$_POST['transdate'],
									 "dateAdded"=>$this->ci->auth->localdate(),
									 "addedBy"=>$this->ci->auth->user_id(),
									 "active"=>1);
						$banksum = $this->ci->UserMgmt->insert_data_to('banksummary',$data);
					}
				return true ;
				}else
				return false;
			}else return false;
		}else return false;
	}
	
	function cmctransSummary($transid){
		$banktrans = $this->ci->Cashmodel->getCMCSum($transid);
		$total = array();
		if($banktrans->num_rows() > 0){
			$collection = 0;
			$disbursement = 0;
			$begintotal = 0;
			$endtotal = 0;
			foreach($banktrans->result() as $bank){
				if(!empty($bank->TotalCollections))
				$collection += $bank->TotalCollections;
				if(!empty($bank->TotalDisbursement))
				$disbursement += $bank->TotalDisbursement;
				if(!empty($bank->beginningBal))
				$begintotal += $bank->beginningBal;
				if(!empty($bank->EndingBal))
				$endtotal += $bank->EndingBal;
				
				$branch = $bank->branchID;
			}
			//echo "<pre>";
			//print_r($banktrans->result());
			//echo "</pre>";
			/*$banks = $this->ci->Cashmodel->getbanklist($branch);
			foreach($banks->result() as $ba){
				$end = $this->checkBankBal($ba->bankID);
				$begintotal += $ba->bankBalance;
				$endtotal += $end;
			}			*/
			$total['Collections'] = $collection;
			$total['Disbursements'] = $disbursement;
			$total['BeginBalance'] = $begintotal;		
			$total['EndBalance'] = $endtotal;		
		}else{
			$total['Collections'] = 0;
			$total['Disbursements'] = 0;
			$total['BeginBalance'] = 0;		
			$total['EndBalance'] = 0;	
		}		
		return $total;
	}
	
	function recompute($transid){
		//compute total collections, disbursement, adjustment
			$data = array("transID"=>$transid);
			$page['cmctrans'] = $this->ci->Loansmodel->get_data_from('cmctransaction', $data);
			if($page['cmctrans']->num_rows() > 0){
			//$page['banktrans'] = $this->Loansmodel->get_data_from('bankstransactions', $data);		
				
				foreach($page['cmctrans']->result() as $tr){
					$page['transdate'] = $tr->dateTransaction;
					$page['opendate'] = $tr->dateOpen;
					$page['cmcstatus'] = $tr->status;
					$page['branchid'] = $tr->branchID;
				}
				if($this->checkOpenTransbefore($page['transdate'],$page['branchid']) == false){
				//by bank
					$banks = $this->ci->Cashmodel->getbanklistonbranch($page['branchid'] );
					if($banks->num_rows() > 0 ){
						$beginbal = 0; //totalbegin
						$tc = 0; //totaltc
						$td = 0; //totaltd
						$te = 0; //totalend
						$ta = 0;
						foreach($banks->result() as $bal){
							$beg = $this->ci->Cashbalance->EndOfDateBalance($page['transdate'],$bal->branchBankID)->row();
							$adj = $beg->TotalAdjadd + (-1 * $beg->TotalAdjless);
							$end = $beg->BeginBal + $beg->TotalCol - $beg->TotalDis + $adj;
							//update banksummary
							$bankdata = array("transID"=>$transid,
											"branchbankID"=>$bal->branchBankID);
							if($this->ci->Loansmodel->get_data_from("banksummary", $bankdata)->num_rows() > 0){
								//update bank
								$updatebank = array("beginningBal"=>$beg->BeginBal,
													"EndingBal"=>$end,
													"TotalCollections"=>$beg->TotalCol,
													"TotalDisbursement"=>$beg->TotalDis,
													"TotalAdjustment"=>$adj,
													"dateOfTransaction"=>$page['transdate'],
													"dateModified"=>$this->ci->auth->localtime(),
													"modifiedBy"=>$this->ci->auth->user_id());
								$this->ci->Loansmodel->update_data('banksummary', $bankdata, $updatebank);
							}else{
								$updatebank = array("beginningBal"=>$beg->BeginBal,
													"EndingBal"=>$end,
													"TotalCollections"=>$beg->TotalCol,
													"TotalDisbursement"=>$beg->TotalDis,
													"TotalAdjustment"=>$adj,
													"dateAdded"=>$this->ci->auth->localtime(),
													"AddedBy"=>$this->ci->auth->user_id(),
													"dateOfTransaction"=>$page['transdate'],
													"transID"=>$transid,
													"branchbankID"=>$bal->branchBankID);
								$this->ci->UserMgmt->insert_data_to("banksummary", $updatebank);
								//echo "OK added";
							}
							$beginbal += $beg->BeginBal;
							$tc += $beg->TotalCol; 
							$td += $beg->TotalDis; //totaltd
							$ta += $adj;
							$te += $end; //totalend
						}
						//consolidate on branch
						$data = array("beginningBal"=>$beginbal,
											"EndingBal"=>$te,
											"TotalCollections"=>$tc,
											"TotalDisbursement"=>$td,
											"TotalAdjustment"=>$ta);
						$where = array("transID"=>$transid);
						if($this->ci->Loansmodel->update_data('cmctransaction', $where, $data) == true)
						return true;
						else return false;
					}
				}else{
					return false;
				}
			}
	}
	
	function openlocktransaction($transid, $openlock){
		if($openlock == "lock"){
			//check open
			if( $this->ci->auth->perms("debug", $this->ci->auth->user_id(), 3) == true) echo "try to lock.". $transid;
			//compute total collections, disbursement, adjustment
			$data = array("transID"=>$transid);
			$page['cmctrans'] = $this->ci->Loansmodel->get_data_from('cmctransaction', $data);
			if($page['cmctrans']->num_rows() > 0){
			//$page['banktrans'] = $this->Loansmodel->get_data_from('bankstransactions', $data);		
				
				foreach($page['cmctrans']->result() as $tr){
					$page['transdate'] = $tr->dateTransaction;
					$page['opendate'] = $tr->dateOpen;
					$page['cmcstatus'] = $tr->status;
					$page['branchid'] = $tr->branchID;
				}
				if($this->checkOpenTransbefore($page['transdate'],$page['branchid']) == false){
				//by bank
					$banks = $this->ci->Cashmodel->getbanklistonbranch($page['branchid'] );
					if($banks->num_rows() > 0 ){
						$beginbal = 0; //totalbegin
						$tc = 0; //totaltc
						$td = 0; //totaltd
						$te = 0; //totalend
						$ta = 0;
						foreach($banks->result() as $bal){
							$beg = $this->ci->Cashbalance->EndOfDateBalance($page['transdate'],$bal->branchBankID)->row();
							$adj = $beg->TotalAdjadd + (-1 * $beg->TotalAdjless);
							$end = $beg->BeginBal + $beg->TotalCol - $beg->TotalDis + $adj;
							//update banksummary
							$bankdata = array("transID"=>$transid,
											"branchbankID"=>$bal->branchBankID);
							if($this->ci->Loansmodel->get_data_from("banksummary", $bankdata)->num_rows() > 0){
								//update bank
								$updatebank = array("beginningBal"=>$beg->BeginBal,
													"EndingBal"=>$end,
													"TotalCollections"=>$beg->TotalCol,
													"TotalDisbursement"=>$beg->TotalDis,
													"TotalAdjustment"=>$adj,
													"dateOfTransaction"=>$page['transdate'],
													"dateModified"=>$this->ci->auth->localtime(),
													"modifiedBy"=>$this->ci->auth->user_id());
								$this->ci->Loansmodel->update_data('banksummary', $bankdata, $updatebank);
							}else{
								$updatebank = array("beginningBal"=>$beg->BeginBal,
													"EndingBal"=>$end,
													"TotalCollections"=>$beg->TotalCol,
													"TotalDisbursement"=>$beg->TotalDis,
													"TotalAdjustment"=>$adj,
													"dateAdded"=>$this->ci->auth->localtime(),
													"AddedBy"=>$this->ci->auth->user_id(),
													"dateOfTransaction"=>$page['transdate'],
													"transID"=>$transid,
													"branchbankID"=>$bal->branchBankID);
								$this->ci->UserMgmt->insert_data_to("banksummary", $updatebank);
								if( $this->ci->auth->perms("debug", $this->ci->auth->user_id(), 3) == true) echo "OK added". $transid;
							}
							$beginbal += $beg->BeginBal;
							$tc += $beg->TotalCol; 
							$td += $beg->TotalDis; //totaltd
							$ta += $adj;
							$te += $end; //totalend
						}
						//consolidate on branch
						$data = array("beginningBal"=>$beginbal,
											"EndingBal"=>$te,
											"TotalCollections"=>$tc,
											"TotalDisbursement"=>$td,
											"TotalAdjustment"=>$ta,
											"dateClose"=>$this->ci->auth->localtime(),
											"closedBy"=>$this->ci->auth->user_id(),
											"status"=>$openlock);					
					}else{
						if( $this->ci->auth->perms("debug", $this->ci->auth->user_id(), 3) == true)  echo "wala bank". $transid;
						return false;
					}
				}else{
					if( $this->ci->auth->perms("debug", $this->ci->auth->user_id(), 3) == true)  echo "may open". $transid;
					return false;
				}
			}else{
				if( $this->ci->auth->perms("debug", $this->ci->auth->user_id(), 3) == true)  echo "wla trans". $transid;
				return false;
			}
			
		}else{
			$data = array('status'=>$openlock,
					"dateOpen"=>$this->ci->auth->localtime(),
					"openedBy"=>$this->ci->auth->user_id());
		}		
		$where = array("transID"=>$transid);
		if($this->ci->Loansmodel->update_data('cmctransaction', $where, $data) == true)
		return true;
		else return false;
	}
	
	
	
	function createtrans(){
		$this->ci->form_validation->set_rules("date", "Date","regex_match[/^(19|20)\d\d([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])$/]|required|callback_checkTransExist|callback_checkDateAdvance");
		$this->ci->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
		if($this->ci->form_validation->run() != false){
			//if($this->checkTransExist($_POST['date'], $this->ci->auth->branch_id()) == false){
				$data = array("branchID"=>$this->ci->auth->branch_id(),
					"status" => 'open',
					"dateOpen" => $this->ci->auth->localtime(),
					"dateTransaction" => $_POST['date'],
					"openedBy" => $this->ci->auth->user_id(),
					"active"=>1);
				$trans_id = $this->ci->UserMgmt->insert_data_to("cmctransaction", $data);
				if($trans_id != false)
				return $trans_id ;
				else
				return false;
			//}else
			//return false;
		}
	}
	
	function startTransaction($branch,$date){		
		$data = array("branchID"=>$branch,
					"status" => 'open',					
					"dateOpen" => $this->ci->auth->localtime(),
					"dateTransaction" => $date,
					"openedBy" => $this->ci->auth->user_id(),
					"active"=>1);
		$trans_id = $this->ci->UserMgmt->insert_data_to('cmctransaction',$data);
		if($trans_id != false)
		return $trans_id ;
		else
		return false;
	}
	
	function transactions($date){
		$branch = $this->ci->UserMgmt->get_branches();
		if($branch->num_rows() > 0){
			foreach ($branch->result() as $br){
			//echo $br->id;
				if($this->checkTransToday($br->id,$date) != true){
				//echo $br->id;
					$this->startTransaction($br->id,$date);
				}
			}
		}
	}
	
	function updateBranchbank(){
		$this->ci->input->post(NULL,true);
		$this->ci->form_validation->set_rules("bankID","Bank", "required|xss_clean");
		$this->ci->form_validation->set_rules("bankAccount","Bank Account", "required|xss_clean|is_numeric|callback_accountExistexept");
		$this->ci->form_validation->set_rules("BeginningBal","Beginning balance", "required|xss_clean|is_numeric|callback_checktrans");
		$this->ci->form_validation->set_rules("BeginningDate","Beginning Date", "required|xss_clean|regex_match[/^(19|20)\d\d([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])$/]");
		$this->ci->form_validation->set_rules("bankAddress","Bank Address", "required|xss_clean");
		$this->ci->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>', '</div>');
		$where = array('branchBankID'=>$_POST['branchBankID']);
		if($this->ci->form_validation->run() != false){
			$data = array("bankID"=> $_POST['bankID'],
						"bankAccount" => $_POST['bankAccount'],
						"bankBranch" => $_POST['bankBranch'],
						"bankAddress" => $_POST['bankAddress'],
						"BeginDate" => $_POST['BeginningDate'],
						"BeginBalance" => $_POST['BeginningBal'],
						"dateModified"=>$this->ci->auth->localtime(),
						"modifiedBy"=>$this->ci->auth->user_id());		
						;
			if($this->ci->Loansmodel->update_data('bankofbranch', $where, $data) != false){			
				return true;
			}else
			return false;
		}		
	}	
	
	function accountExistexept(){
		$data = array("branchBankID <>"=>$_POST['branchBankID'],
						"bankAccount" => $_POST['bankAccount'],
						"bankID" => $_POST['bankID']);
		if($this->ci->Loansmodel->get_data_from('bankofbranch', $data)->num_rows() > 0){
			$this->ci->form_validation->set_message('accountExistexept', 'Account number already exists.');
			return false;
		}else{
			return true;
		}
	}
	
	function checktrans(){
		$data = array("branchBankID"=>$_POST['branchBankID']);
		if($this->ci->Loansmodel->get_data_from('bankstransactions', $data)->num_rows() > 0)
		{
			$this->ci->form_validation->set_message('checktrans', 'Sorry you cannot modify the bank details');
			return false;
		}else
			return true;
	}
	
	
	function getTransID($branch,$date){
		$data = array("branchID"=>$branch,
					"dateTransaction"=>$date);
		$res = $this->ci->Loansmodel->get_data_from("cmctransaction", $data);
		if($res->num_rows() >0)
		{
			$cmc = $res->row();
			$transid = $cmc->transID;
			return $transid;
		}else return false;
	}
	
	
	function addtransaction(){
		if($_POST){
		//gettransID
		$branch = $this->ci->auth->branch_id();
		$date = $this->ci->auth->localdate();
			$transid = $this->getTransID($branch,$date);
			if($transid == false)
				$transid = $this->startTransaction($branch,$date);
			//echo $transid;
			switch ($_POST['submit']) {
				case 'Add Collection':
					if($this->addCollection($transid) == true)
					$status = "New Collection was added";
					break;
				case 'Add Disbursement':
					if($this->addDisbursement($transid)==true)
					$status = "New Disbursement was added";
					break;
				case 'Add Adjustment':
					if($this->addAdjustment($transid)==true)
					$status = "New Adjustment was added";
					break;
				case "Add Bank" :
					$status = $this->addBanktoBranch($transid);
					break;
				case "LOCK CMC" :
					if($this->lock() == true)
					$status = "CMC was locked";
					break;
				case "OPEN CMC" :
					if($this->lock() == true)
					$status = "CMC was opened";
					break;
			}	
			
			if(isset($status)){
				if($status != false) return $status;
				else return false;
			}
		}
	}
	
	function cmcstatus($branch, $date){
		$data = array("branchID"=>$branch,
					"dateTransaction"=>$date);
		$res = $this->ci->Loansmodel->get_data_from("cmctransaction", $data);
		if($res->num_rows() > 0){
			$stat = $res->row();
			$stat = $stat->status;
			if($stat == 'open') return true;
			else return false;
		} else return true;
	}
	
	function getCollectionbyID($id){
		$data = array("BanktransID"=>$id,
					"isdeleted <>"=>1);
		$col = $this->ci->Loansmodel->get_data_from("bankstransactions", $data);
		return $col;
	}
	
	function getRecapbyID($id){
		$data = array("recapdepositID"=>$id,
					"isdeleted <>"=>1);
		$col = $this->ci->Loansmodel->get_data_from("recapofdeposits", $data);
		return $col;
	}
	
	function removeRecap($id){
		$data = array("isdeleted"=>1,
						"dateModified"=>$this->ci->auth->localtime(),
						"modifiedBy"=>$this->ci->auth->user_id());
		$where = array("recapdepositID"=>$id);
		if($this->ci->Loansmodel->update_data('recapofdeposits', $where, $data) == true)
		return true;
		else 
		return false;
	}
	
	function addDeposit($transid){
		$this->ci->form_validation->set_rules("bankID", "Bank", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("transtype", "Type of Deposit", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("amount", "Amount", "required|xss_clean|trim|is_numeric");
		$this->ci->form_validation->set_rules("timedep", "Time Deposited", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("transdate", "Date of Transaction", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("notes", "Explanation", "xss_clean|trim");
		if($this->ci->form_validation->run() != false){
			$data = array("transID"=>$transid,
						"bankBranchID"=>$_POST['bankID'],
						"typeofDeposit"=>$_POST['transtype'],
						"amount"=>$_POST['amount'],
						"timeofDeposit"=>date("H:i:s", strtotime($_POST['timedep'])),
						"notes"=>$_POST['notes'],
						"dateofTransaction"=>$_POST['transdate'],
						"dateAdded"=>$this->ci->auth->localtime(),
						"addedBy"=>$this->ci->auth->user_id(),
						"active"=>1
						);
			if($this->ci->UserMgmt->insert_data_to('recapofdeposits',$data) != false)
			return true;
			else
			return false;
      	}else{
			return false;
		}
	
	}
	
	function updateRecap($id){
		$this->ci->form_validation->set_rules("bankID", "Bank", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("transtype", "Type of Deposit", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("amount", "Amount", "required|xss_clean|trim|is_numeric");
		$this->ci->form_validation->set_rules("timedep", "Time Deposited", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("transdate", "Date of Transaction", "required|xss_clean|trim");
		$this->ci->form_validation->set_rules("notes", "Explanation", "xss_clean|trim");
		if($this->ci->form_validation->run() != false){
			$where = array("recapdepositID"=>$id);
			$data = array("bankBranchID"=>$_POST['bankID'],
						"typeofDeposit"=>$_POST['transtype'],
						"amount"=>$_POST['amount'],
						"timeofDeposit"=>date("H:i:s", strtotime($_POST['timedep'])),
						"notes"=>$_POST['notes'],
						"dateModified"=>$this->ci->auth->localtime(),
						"modifiedBy"=>$this->ci->auth->user_id());
			if($this->ci->Loansmodel->update_data('recapofdeposits', $where, $data) == true)
			return true;
			else 
			return false;
      	}else{
			return false;
		}
	}
	
	function getLocalIP(){
		exec("ipconfig /all", $output);
			foreach($output as $line){
				if (preg_match("/(.*)IPv4 Address(.*)/", $line)){
					$ip = $line;
					$ip = str_replace("IPv4 Address. . . . . . . . . . . :","",$ip);
					$ip = str_replace("(Preferred)","",$ip);
				}
			}
		return $ip;
	}

	function collectionByTransID($transid, $cmcstatus){
			$banktrans = $this->ci->Cashmodel->getCMCTransactions($transid,"collection");
			$this->ci->table->set_heading('#', 'Bank Code', 'OR #','PN','Name','Type','Cash', 'Check', 'Online','POS', 'Collections','Verified','Action');			
			if($banktrans->num_rows() >0){
				$count=1;
				$totalcoll = array();				
				$totalcash = 0;
				$totalcheck = 0;
				$totalonline = 0;
				$totalin = 0;
				$totalpos = 0;
				foreach($banktrans->result() as $bt){
					$in = ($bt->Amount_IN ? $bt->Amount_IN : 0);
					$cash=0;
					$check=0;
					$online =0;	
					$pos	=0;
						if($bt->paymentType== 'cash')
						$cash = $in;
						elseif($bt->paymentType== 'check')
						$check = $in;
						elseif($bt->paymentType== 'online')
						$online = $in;
						elseif($bt->paymentType== 'POS')
						$pos = $in;
						
						$cashin = ($cash ? number_format($cash,2) : "-");
						$checkin = ($check ? number_format($check,2) : "-");
						$onlinein = ($online ? number_format($online,2) : "-");
						$posin = ($pos ? number_format($pos,2) : "-");
						
						if($this->ci->auth->perms("Cash.Collections", $this->ci->auth->user_id(), 3) == true and $cmcstatus == 'open' and $bt->verified == null){
							$act = "<a href='".base_url()."cash/page/forms/modifycollections/".$transid."/".$bt->BanktransID."' title='Update' data-target='#'  data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span> </a> &nbsp;";
							if($this->ci->auth->perms("Cash.Collections", $this->ci->auth->user_id(), 4) == true)
								$act .= "<a href='".base_url()."cash/daily/remove/".$transid."/".$bt->BanktransID."' title='Remove' data-target='#' data-toggle='remove'><span class='glyphicon glyphicon-remove'></span></a>";							
						}else $act="<span class='glyphicon glyphicon-lock' title='Verified'></span>";
						
						if($bt->verified == null ){
							if($this->ci->auth->perms("Verify CMC", $this->ci->auth->user_id(), 3) == true)
								$ver = " <a href='".base_url()."cash/update/verifystatus/".$bt->BanktransID."' id='update".$bt->BanktransID."' title='Verify' name='trans".$bt->BanktransID."'  class='btn btn-xs btn-success' data-target='#' data-toggle='verify'><span class='glyphicon glyphicon-ok'></span> Verify Now</a>";
							else $ver = "<span class='glyphicon glyphicon-ban-circle'></span>";
						}else {
							
							if($bt->verified == '2')
							$ver ="<span class='glyphicon glyphicon-compressed' title='Verified by Batch'></span> ".date("m-d-Y h:i A", strtotime($bt->dateVerified));
							else
							$ver ="<span class='glyphicon glyphicon-check' title='Verified individually'></span> ".date("m-d-Y h:i A", strtotime($bt->dateVerified));
						}
						
						$ver = '<div id="trans'.$bt->BanktransID.'">'.$ver.'</div>';
						$act = '<div id="update'.$bt->BanktransID.'">'.$act.'</div>';
						
						$this->ci->table->add_row($count, $bt->bankCode, $bt->referenceNo, $bt->PN, $bt->Particulars,$bt->transType,$cashin,$checkin, $onlinein, $posin, number_format($in,2), $ver,$act);		
						
						//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
						$totalin += $in;
						$totalcash += $cash;
						$totalcheck += $check;
						$totalonline += $online;
						$totalpos += $pos;
						$count++;										
				}
				
						$totalcash = ($totalcash ? number_format($totalcash,2) : "-");
						$totalcheck = ($totalcheck ? number_format($totalcheck,2) : "-");
						$totalonline = ($totalonline ? number_format($totalonline,2) : "-");
						$totalpos = ($totalpos ? number_format($totalpos,2) : "-");
						
				$this->ci->table->add_row('', '<b>TOTAL</b>','-', '-','-','-', $totalcash,$totalcheck, $totalonline, $totalpos,  number_format($totalin,2),'', '' );	
				$div = '<div class="table-responsive">';
				$div .= $this->ci->table->generate();
				$div .= '</div>';
				return $div;
			}return 'No transactions yet.';
	}
	
	function disbursementByTransID($transid, $cmcstatus){
		$banktrans = $this->ci->Cashmodel->getCMCTransactions($transid,"disbursement");
		$this->ci->table->set_heading('#', 'Bank', 'CV #', 'Check#', 'PN#','Particulars','Explanation','RFPL','Expenses', 'Releases', 'FundTransfer', ' Total', "Verified",'Action');			
			if($banktrans->num_rows() >0){
				$count=1;
				$totalcoll = array();				
				$totalexp = 0;
				$totalrel = 0;
				$totalft = 0;
				$totalout = 0;
				$totalrfpl = 0;
				foreach($banktrans->result() as $bt){
					$out = ($bt->Amount_OUT) ? $bt->Amount_OUT : 0;
					$exp=0;
					$rel=0;
					$ft =0;
					$rfpl =0;
					
						if(strtolower($bt->transType)== 'expenses')
						$exp = $out;
						elseif(strtolower($bt->transType)== 'releases')
						$rel = $out;
						elseif(strtolower($bt->transType)== 'fund transfer')
						$ft = $out;
						elseif(strtolower($bt->transType)== 'rfpl')
						$rfpl = $out;
												
						if($this->ci->auth->perms("Cash.Disbursements", $this->ci->auth->user_id(), 3) == true and $cmcstatus == 'open' and $bt->verified == null){
							$act = "<a href='".base_url()."cash/page/forms/modifydisbursements/".$transid."/".$bt->BanktransID."' title='Update' data-target='#' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;";
							if($this->ci->auth->perms("Cash.Disbursements", $this->ci->auth->user_id(), 4) == true)
								$act .= "<a href='".base_url()."cash/daily/remove/".$transid."/".$bt->BanktransID." 'title='Remove' data-target='#' data-toggle='remove'><span class='glyphicon glyphicon-remove'></span></a>";				
						}else $act="<span class='glyphicon glyphicon-lock' title='Verified'></span>";
						
						if($bt->verified == null ){
							if($this->ci->auth->perms("Verify CMC", $this->ci->auth->user_id(), 3) == true)
								$ver = " <a href='".base_url()."cash/update/verifystatus/".$bt->BanktransID."' id='update".$bt->BanktransID."' title='Verify' name='trans".$bt->BanktransID."'  class='btn btn-xs btn-success' data-target='#' data-toggle='verify'><span class='glyphicon glyphicon-ok'></span> Verify Now</a>";
							else $ver = "<span class='glyphicon glyphicon-ban-circle'></span>";
						}else {							
							if($bt->verified == '2')
							$ver ="<span class='glyphicon glyphicon-compressed' title='Verified by Batch'></span> ".date("m-d-Y h:i A", strtotime($bt->dateVerified));
							else
							$ver ="<span class='glyphicon glyphicon-check' title='Verified individually'></span> ".date("m-d-Y h:i A", strtotime($bt->dateVerified));
						}
						
						$ver = '<div id="trans'.$bt->BanktransID.'">'.$ver.'</div>';
						$act = '<div id="update'.$bt->BanktransID.'">'.$act.'</div>';
						
						$expout = ($exp ? number_format($exp,2) : "-");
						$relout = ($rel ? number_format($rel,2) : "-");
						$ftout = ($ft ? number_format($ft,2) : "-");
						$rfplout = ($rfpl ? number_format($rfpl,2) : "-");
						
						$totalout += $out;
						$totalexp += $exp;
						$totalrel += $rel;
						$totalft += $ft;
						$totalrfpl += $rfpl;
						
					 $this->ci->table->add_row($count, $bt->bankCode, $bt->referenceNo,$bt->Checkno,$bt->PN, $bt->Particulars, $bt->explanation, $rfplout, $expout,$relout, $ftout,number_format($out,2), $ver, $act );				
					//$totalcoll[$bt->bankCode][$bt->paymentType][] = $bt->Amount_IN;
					
					$count++;
					
										
				}
					$totalexp = ($totalexp ? number_format($totalexp,2) : "-");
					$totalrel = ($totalrel ? number_format($totalrel,2) : "-");
					$totalft = ($totalft ? number_format($totalft,2) : "-");
					$totalrfpl = ($totalrfpl ? number_format($totalrfpl,2) : "-");
					
				$this->ci->table->add_row('', '','', '','','','<b>TOTAL</b>', '<b>'.$totalrfpl.'</b>','<b>'.$totalexp.'</b>','<b>'.$totalrel.'</b>', '<b>'.$totalft.'</b>', '<b>'.number_format($totalout,2).'</b>','','' );	
				$div ='<div class="table-responsive">';
				$div .= $this->ci->table->generate();
				$div .= '</div>';
				return $div;
			}else return 'No transactions yet.';
			
	}

	function adjustmentByTransID($transid, $cmcstatus){
		$banktrans = $this->ci->Cashmodel->getCMCTransactions($transid,"adjustment");
		$this->ci->table->set_heading('#', 'JV #', 'Bank Code', 'Particulars','Adjustment Type', 'Amount',' Total Adjustment', 'Verified','Action');			
			if($banktrans->num_rows() >0){
				$count=1;
				$totalcoll = array();				
				$totalexp = 0;
				$totalrel = 0;
				$totalft = 0;
				$totalout = 0;
				foreach($banktrans->result() as $bt){
					if($bt->Amount_IN > 0) $amount = $bt->Amount_IN;
					else if($bt->Amount_OUT > 0) $amount = -1 * $bt->Amount_OUT; 
					
					if($this->ci->auth->perms("Cash.Adjustments", $this->ci->auth->user_id(), 3) == true and $cmcstatus == 'open' and $bt->verified == null){
							$act = "<a href='".base_url()."cash/page/forms/modifyadjustment/".$transid."/".$bt->BanktransID."' title='Update' data-target='#' data-toggle='modal'><span class='glyphicon glyphicon-pencil'></span></a> &nbsp;";
							if($this->ci->auth->perms("Cash.Adjustments", $this->ci->auth->user_id(), 4) == true)
								$act .= "<a href='".base_url()."cash/daily/remove/".$transid."/".$bt->BanktransID."' title='Remove' data-target='#' data-toggle='remove'><span class='glyphicon glyphicon-remove'></span></a>";		
						}else $act="<span class='glyphicon glyphicon-lock' title='Verified'></span>";
						
						if($bt->verified == null ){
							if($this->ci->auth->perms("Verify CMC", $this->ci->auth->user_id(), 3) == true)
								$ver = " <a href='".base_url()."cash/update/verifystatus/".$bt->BanktransID."' id='update".$bt->BanktransID."' title='Verify' name='trans".$bt->BanktransID."'  class='btn btn-xs btn-success' data-target='#' data-toggle='verify'><span class='glyphicon glyphicon-ok'></span> Verify Now</a>";
							else $ver = "<span class='glyphicon glyphicon-ban-circle'></span>";
						}else {							
							if($bt->verified == '2')
							$ver ="<span class='glyphicon glyphicon-compressed' title='Verified by Batch'></span> ".date("m-d-Y h:i A", strtotime($bt->dateVerified));
							else
							$ver ="<span class='glyphicon glyphicon-check' title='Verified individually'></span> ".date("m-d-Y h:i A", strtotime($bt->dateVerified));
						}
						
						$ver = '<div id="trans'.$bt->BanktransID.'">'.$ver.'</div>';
						$act = '<div id="update'.$bt->BanktransID.'">'.$act.'</div>';
					
					
					
					$this->ci->table->add_row($count, $bt->referenceNo, $bt->bankCode, $bt->Particulars, $bt->transType, number_format($amount,2),  number_format($amount,2),$ver, $act);				
					$count++;
					}					
				$div = '<div class="table-responsive">';
				$div .= $this->ci->table->generate();
				$div .= '</div>';
				return $div;
			}else return 'No transactions yet.';
	}
	
	function banksByTransID($branchid, $transid, $cmcstatus,$transdate){
		$banks = $this->ci->Cashmodel->getbanklistonbranch($branchid);
		if($banks->num_rows() > 0 ){
			$this->ci->table->set_heading("#", "Bank", "Beginning Balance", "Total Collections", "Total Disbursement", "Total Adjustment","Total End Balance","Difference","Bal. on Bank","Action");
			$count = 1;
			$date = $transdate;
			$beginbal = 0; //totalbegin
			$tc = 0; //totaltc
			$td = 0; //totaltd
			$te = 0; //totalend
			$ta = 0;
			$ab=0;
			$di=0;
			
			foreach($banks->result() as $bal){
				$beg = $this->ci->Cashbalance->EndOfDateBalance($date,$bal->branchBankID)->row();
				$adj = $beg->TotalAdjadd + (-1 * $beg->TotalAdjless);
				$end = $beg->BeginBal + $beg->TotalCol - $beg->TotalDis + $adj;
				$actual = ($beg->actualbalance) ? $beg->actualbalance : 0;
				$dif = $end - $actual;
				if($this->ci->auth->role_id() == 11)
				{
					//echo "<pre>";
					//print_r($beg);
				//	echo "</pre>";
				}
				if($this->ci->auth->perms("Cash.Transactions", $this->ci->auth->user_id(), 3) == true and $cmcstatus == 'open'){
				$but = '<a href="'.base_url().'cash/page/forms/actualbalance/'.$transid.'/'.$bal->branchBankID.'" id="teh" data-target="#" data-toggle="modal">Update Balance</a>';
				}else $but = "n/a";
				
				$begbal = ($beg->BeginBal ? number_format($beg->BeginBal,2) : "-");
				$totalCol = ($beg->TotalCol ? number_format($beg->TotalCol ,2) : "-");
				$totalDis = ($beg->TotalDis ? number_format($beg->TotalDis ,2) : "-");
				$totalAdj = ($adj ? number_format($adj ,2) : "-");
				$totalEnd = ($end ? number_format($end ,2) : "-");
				$totalDif = ($dif ? number_format($dif,2) : "-");
				$totalAct = ($actual ? number_format($actual,2) : "-");
				
				$this->ci->table->add_row($count, '<a href="'.base_url().'cash/daily/transaction/'.$transid.'/'.$bal->branchBankID.'">'.$bal->bankCode.'</a>',$begbal, $totalCol, $totalDis, $totalAdj, $totalEnd, $totalDif, $totalAct, $but);
				
				$beginbal += $beg->BeginBal;
				$tc += $beg->TotalCol; 
				$td += $beg->TotalDis; //totaltd
				$ta += $adj;
				$te += $end; //totalend
				$ab += $actual ; //totalend
				$di += $dif ; //Dif
				$count++;
			}
			$this->ci->table->add_row("","TOTAL", number_format($beginbal,2), number_format($tc,2), number_format($td,2),  number_format($ta,2),number_format($te,2),number_format($di,2),number_format($ab,2));
			$div = '<div class="table-responsive">';
			$div .= $this->ci->table->generate();
			$div .= '</div>';
			return $div;
		 }else { return '<div class="alert alert-danger">'."No banks associated yet. ".'</div>'; }
	}
	
	function transactionButtons(){
		
	}
	
	function updateBranchBankStatus(){
		switch ($_POST['submit']) {
				case "Add Bank":
					if($this->addBanktoBranch() == true)
						$page['success'] ="New Branch was added";
					else
						$page['error'] = "Error encountered. Please check your entries.";
				break;
				case "Remove":
					if(count($_POST['checked']) > 0){
						foreach ($_POST['checked'] as $bankbr){
							$data[] = array("branchBankID"=> $bankbr,
														"isdeleted" => 1,
														"active" => 0,
														"deletedBy"=> $this->ci->auth->user_id(),
														"dateDeleted"=> $this->ci->auth->localtime());
						}
						if ($this->ci->db->update_batch('bankofbranch', $data, 'branchBankID') != true)
						$page['success'] = "Removed";
						else $page['error'] = "bank was not removed";
					}
				break;
		}
		return $page;
		
	}
	
	function lastTransaction(){
		
	}
}?>