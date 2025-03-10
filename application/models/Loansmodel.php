<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loansmodel extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		
    }
	
	
	public function add_products($pcode,$pname,$pdesc){
		$pars = array("loancode"=>$pcode);	
		
		
		if($this->fieldIn("loantypes",$pars) ==true)
			return 'existing loan product';
		else{
			//hash code, date and user
			$hash = md5($pcode.substr($this->auth->localtime(), 0,10).$this->auth->user_id());
			$pars = array("loancode"=>$pcode,
								"loanname"=>$pname,
								"loandescription"=>$pdesc,
								"dateAdded"=>$this->auth->localtime(),
								"addedBy" => $this->auth->user_id(),
								"active" => 1,
								"inlineHash" => $hash);
			if ($this->db->insert('loantypes', $pars))
			return "New Loan Product was created";
			else
			return "Please try again.";
		}
		
	}
	
	function getproduct($data){		
		$this->db->select('*');
		$this->db->where($data);
		$this->db->join('product', 'product.productID = loantypes.productID', 'left');
		$g = $this->db->get("loantypes");
		return $g;	
	}
	
	function getproductid($loantype, $loancode, $loanstatus, $method,$comp){
				
		$data = array("loantypes.productID"=>$loantype,
						"LoanCode"=>$loancode,
						"LoanSubCode"=>$loanstatus,
						"PaymentTerm"=>$method,
						"computation"=>$comp,
						"loantypes.active"=>1);
		$this->db->join("product", "product.productID = loantypes.productID","left");
		$this->db->where($data);
		$pro = $this->db->get('loantypes');
		//$pro = $this->get_data_from('loantypes',$data);
		
		if($pro->num_rows() >0){
			$pr = $pro->row();			
			return $pr;
		}else
			return false;
	}
	
	function name_exist($fname, $lname){
		$data = "CONCAT(firstName, ' ', LastName) = '".$fname." ".$lname."'";
		$this->db->select('*');
		$this->db->from('clientinfo');
		$this->db->where($data, NULL, false);
		//$c = $this->get_data_from('clientinfo', $data);
		$c = $this->db->get();
		if($c->num_rows() > 0)
			return true;
		else
			return false;
	}
	
	function get_pensioninfo($pensionid){
		$this->db->select('*');
		$this->db->from('pensioninfo');
		$this->db->join('banks','banks.bankID = pensioninfo.BankID','left');
		$this->db->where('PensionID', $pensionid);
		return $this->db->get();
	}
	
	function get_pensionofclient($client, $cno, $branchID){
		$sql ="select * from pensioninfo left  join banks on banks.bankID = pensioninfo.BankID where clientID = '$client'";
		return $this->db->query($sql);
		/*$this->db->select('*');
		$this->db->from('pensioninfo');
		//$this->db->join('banks','banks.bankID = pensioninfo.BankID', 'left');
		$this->db->where('clientID', $client);
		$this->db->or_where('CNO', $cno);
		return $this->db->get();*/
	}
	
	function fieldIn($table,$pars){		
		$this->db->select('*');
		$this->db->where($pars);
		$g = $this->db->get($table);
		if($g->num_rows() > 0)
		return true;
		else return false;
	}
	
	function update_products($post,$pid){
		$this->db->trans_start();
		$this->db->where("loantypeID",$pid);
		$this->db->update("loantypes", $post);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)	return false;
		else return true;		
	}
	
	function get_products($status){
		//$sql = "select * from loantypes where active='1'";		
		$this->db->where($status);
		$this->db->select('loantypes.*');
		$this->db->select('product.productName');
		$this->db->order_by('productName', 'DESC');
		$this->db->order_by('LoanCode', 'ASC');
		$this->db->order_by('LoanSubCode', 'ASC');
		$this->db->join('product', 'product.productID = loantypes.productID', 'left');
		return $this->db->get('loantypes');
		
	}

	function get_productcodes(){
		$sql = 'SELECT * FROM loantypes 
					JOIN product ON product.productID = loantypes.productID
					WHERE loantypes.active = 1
					GROUP BY CONCAT(productCode,"-",LoanCode)';
		return $this->db->query($sql);
	}
	
	function getproductsbyID($pid){
		$pars = array("loanTypeID"=>$pid);
		$this->db->select('loantypes.*', 'loantypes.active as loanActive' );
		$this->db->where($pars);
		$this->db->join('product', 'product.productID = loantypes.productID', 'left');
		$g = $this->db->get("loantypes");
		return $g;
	}
	
	function add_loanfee($post){
		//hash feename, loantype, date, userid
		$hash = md5($post['feename'].$post['loantype'].substr($this->auth->localtime(), 0,10).$this->auth->user_id());
		$pars = array("feeName"=>$post['feename'],
							"comptype"=>$post['computation'],
							"value"=>$post['value'],
							"loantype"=>$post['loantype'],
							"dateAdded"=>$this->auth->localtime(),
							"addedBy" => $this->auth->user_id(),
							"active" => 1,
							"inlineHash" => $hash
							);
		if ($this->db->insert('loanfees', $pars))
			return "New Loan Fee was created";
		else
			return "Please try again.";
	}	
	
	function get_loanfees(){
		$sql = "select fee.*, productCode as loancode from loanfees as fee 
					LEFT JOIN loantypes ON fee.loantype = loantypes.loantypeID
					LEFT JOIN product on product.productID = loantypes.productID";
		return $this->db->query($sql);
	}
	
	function updateproduct_status($post, $pid)
	{		
		$this->db->trans_start();
		$this->db->where("loantypeID",$pid);
		$this->db->update("loantypes", $post);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)	return false;
		else return true;
	}
	
	
	function get_cities(){
		$sql = "select cities.*, provinces.name as prov from cities 
					LEFT JOIN provinces on provinces.id=cities.province_id order by cities.name ASC";
		return $this->db->query($sql);
	}
	
	function get_cities_by_prov($pid){
		$sql = "select * from cities where province_id = '".$pid."' order by name ASC";
		return $this->db->query($sql);
	}
	
	function get_province(){
		$this->db->select('*');
		$this->db->from('provinces');
		return $this->db->get();		
	}
	
	function add_client($data){
		$this->db->insert('clientinfo', $data);
		return $this->db->insert_id();
	}
	
	function update_inlinehash($table, $id, $hash)
	{
		$this->db->where($id);
		$this->db->update($table, $hash);
	}
	
	function update_data($table, $id, $data)
	{
		$this->db->where($id);
		if	($this->db->update($table, $data))
		return true;
	}
	
	function addtotable($table, $data){
		$this->db->trans_start();
		$this->db->insert($table,$data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)	return false;
		else return true;
	}
	
	function get_clientbyid($id){
		$this->db->select('*');
		$this->db->where("clientID", $id);
		return $this->db->get("clientinfo");
	}
	
	function get_data_from($table, $where){		
		$this->db->select('*');
		if($where)
		$this->db->where($where);
		return $this->db->get($table);
	}
	
	function addloan($data){
		$this->db->insert('loanapplication', $data);
		if($this->db->insert_id())
		return $this->db->insert_id();
		else return false;
	}
	
	function forapproval(){
		$this->db->select('loan.PNno, loan.AmountApplied, loan.Term, loan.dateApplied, client.firstname, client.LastName, loantypes.LoanName');
		$this->db->join('loantypes', 'loanTypeID = loan.loantype');
		$this->db->join('clientinfo as client', '');
	}
	
	/*function getLoans($data){
		$this->db->select("*");
		$this->db->join("loantypes", "loanTypeID = loanrecords.LNTYPE");
		$this->db->from("loanrecords");
		$this->db->order_by("DOPEN","DESC");
		$this->db->where($data);
		return $this->db->get();
	}*/
	
	function getLoansNew($client){
		if($this->auth->perms("CMC ALL Branches",$this->auth->user_id(),3) == false)
			$this->db->where("branchID", $this->auth->branch_id());
		$this->db->select('*');
		$this->db->from('loanapplication');
		$this->db->join('loantypes', 'loanTypeID = loanapplication.LoanType', 'left');
		$this->db->join('product', 'product.productID = loantypes.productID', 'left');
		$this->db->where("ClientID", $client);
		
		$this->db->where("loanapplication.active", 1);
		$this->db->order_by("MaturityDate", "DESC");		
		return $this->db->get();
	}
	
	function getLoansFromBranch($client){
		$sql = "SELECT count(*) as total, `branchname` FROM `loanapplication` LEFT JOIN `loantypes` ON `loanTypeID` = `loanapplication`.`LoanType` LEFT JOIN `product` ON `product`.`productID` = `loantypes`.`productID` JOIN `branches` ON `branches`.`id`=`loanapplication`.`branchID` WHERE `ClientID` = '".$client."' AND `loanapplication`.`active` = 1 AND (lower(loanapplication.status) = 'granted' OR lower(loanapplication.status) = 'current' OR lower(loanapplication.status) = 'past due') and MaturityDate > CURDATE() and branchID <> '".$this->auth->branch_id()."' GROUP BY `branchID`";
				
		return $this->db->query($sql);
	}
	
	function getActiveLoans($client){
		$sql = "SELECT 
		  loantypes.*, product.*,loanapplication.* 
		FROM
		  loanapplication 
		  LEFT JOIN loantypes 
			ON loanTypeID = loanapplication.LoanType 
		  LEFT JOIN product 
			ON product.productID = loantypes.productID 
		WHERE ClientID = '".$client."' 
		  AND loanapplication.active = 1 
		  AND (LOWER(loanapplication.status) = 'granted' 
		  OR LOWER(loanapplication.status) = 'current' )
		ORDER BY MaturityDate DESC " ;
		return $this->db->query($sql);
		
	}
	
	function getLoansByStatus($where){
		$this->db->select('loantypes.*,pensioninfo.pensionType, product.*,branchname,loanapplication.*, clientinfo.firstName as cfname, clientinfo.LastName as clname, user.firstname as ufname, user.lastname as ulname');
		$this->db->join('loantypes', 'loanTypeID = loanapplication.LoanType','left');
		$this->db->join('product', 'product.productID=loantypes.productID','left');
		$this->db->join('pensioninfo', 'product.productCode="PL" and pensioninfo.pensionID = loanapplication.pensionID','left');
		$this->db->join('branches', 'branches.id=loanapplication.branchID','left');
		$this->db->join('user', 'user.id = loanapplication.LoanProcessor','left');
		$this->db->join('clientinfo', 'clientinfo.ClientID = loanapplication.ClientID','left');
		$this->db->where($where);
		//if($this->auth->perms("CMC ALL Branches",$this->auth->user_id(),2) == false)
		//$this->db->where("loanapplication.branchID", $this->auth->branch_id());
		$this->db->order_by("loanapplication.dateModified", "ASC");
		$this->db->order_by("dateApproved", "DESC");
		$this->db->from('loanapplication');
		return $this->db->get();
	}
	
	function LoansGranted($where){
		
		$sql = "SELECT 
			 loanapplication.PN ,
			CONCAT(
				clientinfo.LastName,
				', ',
				clientinfo.firstName
			  ) AS name ,
			  product.productCode,
			 referenceNo,
			approvedAmount,
			(SELECT loanfees.value
			FROM (loanfees)
			LEFT JOIN productfees ON productfees.feeID = loanfees.feeID
			LEFT JOIN loan_charges ON loan_charges.id = productfees.charge_type_ID
			WHERE loanfees.loanID =  loanapplication.loanID
			AND loanfees.active <> '0' 
			AND feeName = 'UID') AS UID,
			 (SELECT SUM(loanfees.value)
			FROM (loanfees)
			LEFT JOIN productfees ON productfees.feeID = loanfees.feeID
			LEFT JOIN loan_charges ON loan_charges.id = productfees.charge_type_ID
			WHERE loanfees.loanID =  loanapplication.loanID
			AND loanfees.active <> '0' 
			AND feeName <> 'UID') AS charges,
			netproceeds,
			Term, extension,
			paymentmethod,
			DateDisbursed,
			MaturityDate
			FROM
			  loanapplication 
			  LEFT JOIN loantypes 
				ON loantypes.loanTypeID = loanapplication.LoanType 
			  LEFT JOIN product 
				ON product.productID = loantypes.productID 
			  LEFT JOIN branches 
				ON branches.id = loanapplication.branchID 
			  LEFT JOIN clientinfo 
				ON clientinfo.ClientID = loanapplication.ClientID 
			  LEFT JOIN bankstransactions 
				ON bankstransactions.PN = loanapplication.PN 
			  LEFT JOIN bankofbranch 
				ON bankofbranch.branchBankID = bankstransactions.branchBankID 
				AND bankofbranch.branchID = loanapplication.branchID
			  LEFT JOIN banks 
				ON banks.bankID = bankofbranch.bankID 
			  
			WHERE ".$where.
			" order by loanapplication.DateDisbursed ASC ";
		
		return $this->db->query($sql);
	}
	
	function getLoanbyID($id, $select){
		//$this->db->select('loantypes.*, product.*,loanapplication.*, branches.branchname, branches.address as branchaddress, cities.name as city');
		$this->db->select($select);
		$this->db->join('loantypes', 'loantypes.loanTypeID = loanapplication.LoanType','left');	
		$this->db->join('product', 'product.productID = loantypes.productID');	
		$this->db->join('branches', 'branches.id = loanapplication.branchID','left');	
		$this->db->join('cities', 'cities.id = branches.city','left');	
		$this->db->where("loanID", $id);
		$this->db->order_by("dateApplied");
		$this->db->from('loanapplication');
		return $loaninfo = $this->db->get();
	}
	
	function getLoanDetails($loanid){
		$this->db->select('loantypes.*, pensioninfo.pensionType,product.*,loanapplication.*, branches.branchname, branches.address as branchaddress, cities.name as city');
		$this->db->join('loantypes', 'loantypes.loanTypeID = loanapplication.LoanType','left');	
		$this->db->join('product', 'product.productID = loantypes.productID');	
		$this->db->join('branches', 'branches.id = loanapplication.branchID','left');	
		$this->db->join('cities', 'cities.id = branches.city','left');	
		$this->db->join('pensioninfo', 'product.productCode="PL" and pensioninfo.pensionID = loanapplication.pensionID','left');
		$this->db->where("loanID", $loanid);
		$this->db->order_by("dateApplied");
		$this->db->from('loanapplication');
		$loaninfo = $this->db->get();
		
		//$this->output->enable_profiler(TRUE);
		if($loaninfo){
		if($loaninfo->num_rows() > 0 ){
			$loandetails= $loaninfo->row();			
			$loans['loaninfo'] = $loaninfo;
			$loans['clientinfo'] = $this->Clientmgmt->getclientinfoByID($loandetails->ClientID);
			$loans['spouseinfo'] = $this->Clientmgmt->getspouse($loandetails->ClientID);
			$loans['dependents'] = $this->Clientmgmt->getdependents($loandetails->ClientID);
			$loans['creditor'] = $this->Clientmgmt->getcreditor($loandetails->ClientID);
			$loans['employment'] = $this->Clientmgmt->getEmployer($loandetails->ClientID);
			$loans['incomeexpense'] = $this->Clientmgmt->getIncomeExpense($loandetails->ClientID);	
			$loans['comaker'] = $this->getComaker($loanid);			
			$loans['fees'] = $this->getLoanFees($loanid); 
			
			
			$loantype = $loandetails->productCode;
			$productID = $loandetails->productID;
			$PN = $loandetails->PN;
			$colID = $loandetails->pensionID;
			if(strpos($loantype, "PL") !== false){
				$loans['collaterals']= $this->get_pensioninfo($colID);
			}else{
				$loans['collaterals']= $this->getCollateralByLoan($colID);
			}
			//if($PN == '')
				$loans['schedule'] = $this->getsched($loanid); 
			//else
				//$loans['schedule'] = $this->schedule($loanid, $PN);
			$loans['ci'] = $this->getCILoan($loanid, $productID);
			$loans['req'] = $this->loansetup->requirements($loanid, $loantype); 
			
			return $loans;
		}else{
			return false;	
		}
		}
		
	}
	
	function schedule($loanid, $pn){
		if($pn == '')
			$pn = "PN IS NULL";
		else $pn = "PN = '".$pn."'";
		$sql = "select * from loanschedule where loanID = '$loanid' and $pn order by loanschedule.order ASC";
		$sched =  $this->db->query($sql);
		return $sched;
	}
	
	function getsched($loanid){
		
		$sql = "select * from loanschedule where loanID = '$loanid' and Active = '1' order by loanschedule.order ASC";
		$sched =  $this->db->query($sql);
		return $sched;
	}
	
	function getCILoan($loanid, $loantype){
		$this->db->select('ci_details.*, ci_and_appraisal.ci_name, ci_and_appraisal.datatype, ci_and_appraisal.ci_id');
		$data = array("loanid"=>$loanid,
							"ci_details.active"=>1);
		$this->db->where($data);
		$this->db->join("ci_and_appraisal", "ci_and_appraisal.ci_id=ci_details.ci_id","left");
		$ci = $this->db->get("ci_details");
		
		if($ci->num_rows() > 0){
			return $ci;
		}else{
			$data = array("productid"=>$loantype,
							"active"=>1);
			$this->db->select('ci_name, datatype, ci_id');			
			$this->db->where($data);
			return $this->db->get("ci_and_appraisal");
		}
	}
	
	function getComaker($loanid){
		
		$this->db->where("loanID", $loanid);
		$this->db->where("active <>", '0');
		$comaker = $this->db->get('co_maker');
		
		if($comaker->num_rows() > 0){
			//echo "mey";
			/*$comakerinfo = $comaker->row();
			$loans['clientinfo'] = $this->Clientmgmt->getclientinfoByID($comakerinfo->clientID);
			$loans['spouseinfo'] = $this->Clientmgmt->getspouse($comakerinfo->clientID);
			$loans['dependents'] = $this->Clientmgmt->getdependents($comakerinfo->clientID);
			$loans['creditor'] = $this->Clientmgmt->getcreditor($comakerinfo->clientID);
			$loans['employment'] = $this->Clientmgmt->getEmployer($comakerinfo->clientID);
			$loans['incomeexpense'] = $this->Clientmgmt->getIncomeExpense($comakerinfo->clientID);			
			*/
			return $comaker;
		}else
			//echo "waley";
			return false;		
	}
	
	function getfees($pid){
		$this->db->select('*');
		$this->db->from('productfees');
		$this->db->join('fees', 'fees.id = productfees.fee_account_id', 'left');
		$this->db->where('productfees.productID', $pid);
		$this->db->where('productfees.active', 1);
		//$this->db->group_by("terms");
		$get =  $this->db->get();
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return $get;
	}
	
	
	function getreqs($pid){
		$this->db->select('*');
		$this->db->from('productrequirements');
		$this->db->join("product", "product.productID = productrequirements.productID");
		$this->db->where('product.productID', $pid);
		$this->db->where('productrequirements.active', 1);
		return $this->db->get();
	}
	 
	function getLoanreqs($loanID){
		$this->db->select("loanID, loanrequirements.reqID, requirement, LoanReqID, submitted, dateSubmitted, submittedTo");
		$this->db->from("loanrequirements");
		$this->db->join("productrequirements", "productrequirements.reqID = loanrequirements.reqID", "left");
		$this->db->where("loanrequirements.loanID", $loanID);		
		return $this->db->get();
	}
	
	function getLoanFees($loanid){
		$this->db->select("loanfeeID, feeName, loanfees.feeID, loanfees.value, loan_charges.id, charge_type_ID, productfees.feeID as fID, charge_type, charge_name, display, comptype, upfront");
		$this->db->FROM("loanfees");
		$this->db->join("productfees","productfees.feeID = loanfees.feeID", "left");
		$this->db->join("loan_charges","loan_charges.id = productfees.charge_type_ID", "left");
		$this->db->where("loanfees.loanID",$loanid);
		$this->db->where("loanfees.active <>","0");
		//$this->db->or_where("loanfees.active",NULL);
		return $this->db->get();
		/*$this->db->select("coa_name, coa_code, loanfeeID, fees.fee_name as fname, fees.gl_account as gl,feeName, loanfees.feeID, fee_account_id, loanfees.value, loan_charges.id, charge_type_ID, productfees.feeID as fID, charge_type, charge_name, display, productfees.comptype, productfees.upfront");
		
		//comptype
		//$this->db->select('*');
		$this->db->FROM("loanfees");
		$this->db->join("productfees","productfees.feeID = loanfees.feeID", "left");
		$this->db->join("loan_charges","loan_charges.id = productfees.charge_type_ID", "left");
		$this->db->join("fees","fees.id = productfees.fee_account_id", "left");
		$this->db->join("gl_coa","gl_coa.coa_parent = fees.gl_account", "left");
		$this->db->where("loanfees.loanID",$loanid);
		$this->db->where("loanfees.active <>","0");
		//$this->db->or_where("loanfees.active",NULL);*/
		/*$sql = "SELECT coa_name, coa_code, loanfeeID, fees.fee_name as fname, gl_coa.coa_id as gl,feeName, loanfees.feeID, fee_account_id, loanfees.value, loan_charges.id, charge_type_ID, productfees.feeID as fID, charge_type, charge_name, display, productfees.comptype, productfees.upfront FROM loanfees
				JOIN productfees ON productfees.`feeID` = loanfees.`feeID`
				JOIN fees ON fees.id = productfees.`fee_account_id`
				JOIN gl_coa ON gl_coa.`coa_parent` = fees.`gl_account`
				JOIN gl_coa_branches ON gl_coa_branches.`coa_id` = gl_coa.`coa_id`
				JOIN loanapplication ON loanapplication.`branchID` = gl_coa_branches.`branch_id`
				AND loanapplication.`loanID` = loanfees.`loanID`
				join loan_charges on loan_charges.id = productfees.charge_type_ID
				WHERE loanfees.`loanID` = '".$loanid."' and gl_coa_branches.active='1'
				 GROUP BY gl_coa.coa_parent";*/		
	}
	
	function getFeesCoa($loanID){
		$sql="SELECT 
				  coa_name,
				  coa_code,
				  loanfeeID,
				  fees.fee_name AS fname,
				  gl_coa.coa_id AS gl,
				  feeName,
				  loanfees.feeID,
				  fee_account_id,
				  sum(loanfees.value) as value,
				  loan_charges.id,
				  charge_type_ID,
				  productfees.feeID AS fID,
				  charge_type,
				  charge_name,
				  display,
				  productfees.comptype,
				  productfees.upfront
				FROM
				  loanfees 
				  JOIN loanapplication 
					ON loanapplication.`loanID` = loanfees.`loanID` 
				  JOIN gl_coa_branches 
					ON gl_coa_branches.`branch_id` = loanapplication.branchID 
				  JOIN gl_coa 
					ON gl_coa.`coa_id` = gl_coa_branches.`coa_id` 
				  JOIN fees 
					ON fees.gl_account = gl_coa.`coa_parent` 
				  JOIN productfees 
					ON productfees.`fee_account_id` = fees.id 
					AND productfees.`feeID` = loanfees.`feeID` 
				  JOIN loan_charges 
					ON loan_charges.id = productfees.charge_type_ID 
				WHERE loanfees.`loanID` = '".$loanID."' 
				  AND gl_coa_branches.`active` = '1' 
				GROUP BY gl_coa.coa_parent ";
		return $this->db->query($sql);
	}
	
	
		
	function SumOfFees($loanid){
		$this->db->select("sum(loanfees.value) as TotalFees");
		$this->db->FROM("loanfees");
		$this->db->join("productfees","productfees.feeID = loanfees.feeID");
		$this->db->where("loanfees.loanID",$loanid);
		$this->db->where("loanfees.active <>","0");
		//$this->db->or_where("loanfees.active",NULL);
		return $this->db->get();
	}
	
	function cvexist($pn){
		$this->db->select("*");
		$this->db->from("bankstransactions");
		$this->db->join("transactiontype", "transactiontype.transTypeID = bankstransactions.transtype");
		$this->db->where('transactiontype.transType', "Releases");
		$this->db->where('PN', $pn);
		$this->db->where('isdeleted <>', '1');
		
		return $this->db->get();
	}
	
	function getTransByPN($pn, $branchID){
		$this->db->select('*');
		$this->db->from('bankstransactions');		
		$this->db->join("bankofbranch", "bankofbranch.branchBankID = bankstransactions.branchBankID");
		$this->db->join("banks", "banks.bankID = bankofbranch.bankID");
		$this->db->where("bankstransactions.PN", $pn);
		$this->db->where("bankofbranch.branchID", $branchID);
		$this->db->where("bankstransactions.isdeleted", 0);
		return $this->db->get();		
	}
	
	function dcrr($date){
		//$date = $this->auth->localdate();
		$day = date("j", strtotime($date));
		$dayword = date("N",strtotime($date));
		
		$end_date = date("Y-m-d", strtotime($date."+5 day"));
		//$end_date = $date;
		//$dateRange = "DDUE BETWEEN '$date' AND '$end_date'";
		$dateRange = "DueDate < '$end_date'";
		
		$this->db->SELECT( 'loanapplication.ClientID as CNO,  loanapplication.loanID, loanapplication.PN, clientinfo.LastName AS lname, clientinfo.firstName AS fname, SUM(AmountDue) as due, pensioninfo.monthlyPension AS mo_pension, pensioninfo.pensionDate AS pdate, Bankaccount, bankCode, bankBranch, pensioninfo.PensionID as PensionID');
		$this->db->from('loanschedule');
		$this->db->JOIN('loanapplication','loanapplication.PN = loanschedule.PN');
		$this->db->JOIN('pensioninfo','pensioninfo.pensionID = loanapplication.pensionID');
		$this->db->JOIN('banks','banks.bankID = pensioninfo.BankID','left');
		$this->db->JOIN('clientinfo','clientinfo.ClientID = loanapplication.ClientID');
		$this->db->where($dateRange, NULL, FALSE);
		$this->db->where('LoanType', '3');
		$this->db->where('Paid','0');
		$this->db->where('status <>', 'closed');
		
		if($dayword == 1){
			$day2 = date("j", strtotime($date."-1 days"));
			$date = 'pensionDate BETWEEN '.$day2.' AND '.$day;
			$this->db->or_where($date,NULL, FALSE);
		}else{
			$this->db->or_where('pensionDate',$day);
		}
		//$this->db->like('Due');
		$time = "TIMESTAMPDIFF(DAY,DueDate,'$date') < '365'";
		$this->db->where($time, NULL,false);		
		$this->db->group_by('pensioninfo.PensionID');
		$this->db->order_by('lname', 'ASC');		
		//$this->output->enable_profiler(TRUE);
		return $this->db->get();
		//echo $this->db->last_query();
	}
	
	function dcrr_new($from = NULL, $to=NULL, $branch){
		if($from ==NULL or $to == NULL)
		{
			$sql ="SELECT 
				 CAST(PensionDate AS UNSIGNED) as PensionDate,
				  pensioninfo.PensionID,
				  `clientinfo`.ClientID,
				  `clientinfo`.`LastName` AS `lname`,
				  `clientinfo`.`firstName` AS `fname`,   
				  `bankCode`,
				  `bankBranch`, 
				  `Bankaccount`,
				  atm_pb,
				   atmnum,
				   `pensioninfo`.`monthlyPension` AS `mo_pension`
				FROM
				  pensioninfo 
				  JOIN `loanapplication` 
					ON `loanapplication`.pensionID = pensioninfo.PensionID 
				  JOIN loantypes 
				  ON loantypes.loanTypeID = loanapplication.LoanType 
				 JOIN product 
				  ON product.productID = loantypes.productID 
				  JOIN `banks` 
					ON `banks`.`bankID` = `pensioninfo`.`BankID` 
				  JOIN `clientinfo` 
					ON `clientinfo`.`ClientID` = `loanapplication`.`ClientID` 
					WHERE clientinfo.`branchID` = '".$branch."'
					AND pensioninfo.`active`='1'   					
				   GROUP BY pensioninfo.PensionID
				   ORDER BY pensionDate ASC, `clientinfo`.`LastName` ASC ";
		}else{
			
			$sql ="SELECT 
				  CAST(PensionDate AS UNSIGNED) as PensionDate,
				  pensioninfo.PensionID,
				  `clientinfo`.ClientID,				  
				  `clientinfo`.`LastName` AS `lname`,
				  `clientinfo`.`firstName` AS `fname`,   
				  `bankCode`,
				  `bankBranch`, 
				  `Bankaccount`,
				  atm_pb,
				   atmnum,
				   `pensioninfo`.`monthlyPension` AS `mo_pension`
				FROM
				  pensioninfo 
				  JOIN `loanapplication` 
					ON `loanapplication`.pensionID = pensioninfo.PensionID 
				  JOIN loantypes 
				  ON loantypes.loanTypeID = loanapplication.LoanType 
				 JOIN product 
				  ON product.productID = loantypes.productID 
				  JOIN `banks` 
					ON `banks`.`bankID` = `pensioninfo`.`BankID` 
				  JOIN `clientinfo` 
					ON `clientinfo`.`ClientID` = `loanapplication`.`ClientID` 
					WHERE clientinfo.`branchID` = '".$branch."'
					AND pensioninfo.`active`='1'   
					AND (CAST(PensionDate AS UNSIGNED) >= '".$from."' AND CAST(PensionDate AS UNSIGNED) <= '".$to."')
				   GROUP BY pensioninfo.PensionID
				   ORDER BY pensionDate ASC, `clientinfo`.`LastName` ASC  ";
		}
		
		$r = $this->db->query($sql);
		return $r;
		
	}
	
	function dcrr_old($date){
		$day = date("j", strtotime($date));
		$dayword = date("N",strtotime($date));
		
		$end_date = date("Y-m-d", strtotime($date."+1 month -1 day"));
		//$dateRange = "DDUE BETWEEN '$date' AND '$end_date'";
		$dateRange = "DDUE < '$end_date'";
			
		$this->db->SELECT( 'CNO,  DueDate, PensionID, clientinfo.LastName AS lname, clientinfo.firstName AS fname, SUM(INSTAMT) as due, pensioninfo.monthlyPension AS mo_pension, pensioninfo.pensionDate AS pdate');
		$this->db->from('pensioninfo');
		$this->db->JOIN('loanrecords','loanrecords.pl_acct_no = pensioninfo.Bankaccount');
		$this->db->JOIN('dblninst','dblninst.PN = loanrecords.PN');
		$this->db->JOIN('clientinfo','clientinfo.ClientID = loanrecords.CNO');
		$this->db->where($dateRange, NULL, FALSE); 
		$stat= "(ISTAT = '0' )";
		$this->db->where($stat, NULL, FALSE);
		//$this->db->or_where('ISTAT', 'P');
		$this->db->where('LNTYPE', '3');
		if($dayword == 1){
			$day2 = date("j", strtotime($date."-1 days"));
			$date = "pensionDate BETWEEN '$day2' AND '$day'";
			$this->db->where($date,NULL, FALSE);
		}else{
			$this->db->where('pensionDate',$day);
		}
		$this->db->where('CURBAL <>', '0');
		$this->db->group_by('PensionID');
		$this->db->order_by('lname', 'ASC');
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return $this->db->get();
	}
	
	function clientdue_old($clientid, $date){
		$this->db->select(' PN, DDUE, INSTAMT');
		$this->db->from('dblninst');
		$this->db->join('loanapplication','loanapplication.PNno = dblninst.PN');
		$this->db->where('ClientID',$clientid);
		$this->db->where('DDUE <',$date);
		$this->db->where('ISTAT','0');
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return $this->db->get();
	}
	
	function clientdue($clientid, $date){
		//$date = '2014-09-08';
		//$end_date = date("Y-m-d", strtotime($date."+1 month -1 day"));
		$date = date("Y-m-d", strtotime($date."+5 day"));
		$this->db->select("loanscheduleID AS schedID ,loanapplication.loanID, loanapplication.PN, order, AmountDue as INSTAMT, DueDate as DDUE");
		$this->db->from('loanschedule');
		$this->db->join('loanapplication', 'loanapplication.loanID = loanschedule.loanID');
		$this->db->JOIN('pensioninfo','pensioninfo.pensionID = loanapplication.pensionID');
		$this->db->JOIN('loantypes','loantypes.loanTypeID = loanapplication.LoanType');
		$this->db->JOIN('product','product.productID = loantypes.productID');
		$this->db->where('pensioninfo.pensionID',$clientid);
		$this->db->where('DueDate <=',$date);
		$this->db->where('Paid','0');		
		return $this->db->get();
	}
	
	function clientpensiondue($pensionid, $date){
		//$end_date = $this->auth->localdate();
		//$end_date = date("Y-m-d", strtotime($date."+5 day"));
	
		$this->db->select("loanschedule.loanSchedID as schedID, loanapplication.loanID as loanID, loanschedule.PN, order, AmountDue as INSTAMT, LoanBalance, DueDate as DDUE");
		$this->db->from('loanschedule');
		$this->db->join('loanapplication', 'loanapplication.PN = loanschedule.PN');
		$this->db->where('pensionID',$pensionid);
		$this->db->where('DueDate <',$date);
		$this->db->where('Paid','0');
		$this->db->where('status <>','closed');
		$time = "TIMESTAMPDIFF(DAY,DueDate,'$date') < '365'";
		$this->db->where($time, NULL,false);		
		$this->db->order_by('DueDate', 'ASC');
		
		return $this->db->get();
	}
	
	function clientpensiondue_old($pensionid, $date){
		$this->db->select(' dblninst.PN as PN, DDUE, INSTAMT, loanapplication.loanID');
		$this->db->from('dblninst');
		$this->db->join('loanapplication','loanapplication.PN = dblninst.PN');
		$this->db->join('pensioninfo', 'pensioninfo.PensionID = loanapplication.pensionID and pensioninfo.clientID = loanapplication.ClientID');
		$this->db->where('pensioninfo.PensionID',$pensionid);
		$this->db->where('DDUE <',$date);
		$this->db->where('ISTAT','0');
		//$this->db->where('CURBAL >', '0');
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		return $this->db->get();
	}
	
	function pensionloan($pensionid){
		$sql = "SELECT * FROM loanapplication
		left join pensioninfo on pensioninfo.ClientID = loanapplication.ClientID
		WHERE loanapplication.pensionID = '$pensionid'  ";
		return $this->db->query($sql);
	}
	
	function addtransdetails($transid, $acctcode, $desc, $dr, $cr){
		$data = array("BankTransID"=>$transid,
							"acct_code"=>$acctcode,
							"description"=>$desc,
							"DR"=>$dr,
							"CR"=>$cr,
							"dateAdded"=>$this->auth->localtime(),
							"addedBy"=>$this->auth->user_id(),
							"active"=>'1');
		$table = "transaction_details";
		$this->addtotable($table, $data);
	}
	
	
	function addCollection(){
	
		$this->db->trans_begin();
		
		$date = $this->auth->localdate();
		$time = $this->auth->localtime();
		$user = $this->auth->user_id();
		$branch = $this->auth->branch_id();
		$clientID = $_POST['clientID'];
		$particulars = $_POST['particular'];
		$beginbal = $_POST['beginbal'];
		$amount_in = $_POST['amount'];
		$amountleft = $_POST['amountleft'];
		$amountdue = $_POST['amountdue']; // array of amount due, params [PN][loansched][amountdue]
		$bankbal = $beginbal - $amount_in;
				
       	//compute total due and total excess
		$transtype = $_POST['transtype'];
		$paymentType = $_POST['paymentType'];
		$bankID = $_POST['bankID'];
		$pensionID = $_POST['pensionid'];
		$referenceNo = $_POST['reference'];
		
		$PN = '';
		if(isset($_POST['amountdue'])){
				$totaldue = 0;				
					foreach($_POST['amountdue'] as $loanID=>$sched){
						$PN .= $loanID."; ";
					}
		}
		
		//get CMCtransID
			$trans = $this->cash->getTransID($branch,$date);
			if($trans == false)
				$trans = $this->cash->startTransaction($branch,$date);
				
				
		//add transaction on bank
		$transid = $this->addbanktransaction($trans, $branch, $clientID, $PN, $bankID, $transtype, $paymentType, $amount_in, $amount_out='', $date, $particulars, $referenceNo, $checkno='', $time, $user);
		
		
		//-----add transaction_details------
		
			//DR
			
			$this->addtransdetails($transid, $acctcode='', $desc='Cash in Bank', $amount_in, $cr='');
			
			//CR
			
				if ( isset ( $_POST['amountdue'] ) ) 
				{
				
					$totaldue = 0;
					$PN = '';
					$pay = $amount_in;
					$cr = 0;
					$crdata= array();
					$totalpay = 0;
						foreach($_POST['amountdue'] as $loanID=>$sched){
						
							$pntotal = 0;
							
								foreach($sched as $sid=>$adue){
								
									//echo "<br/>Sched ID: ".$sid."=".$adue;
									
									//for loanschedule update
									if($pay > $adue){
										$payment = $adue;
										$pay -= $payment;
									}else{
										$payment = $pay;
										$pay -= $payment;
									}
									
									$schedule = array("Paid"=>$payment,
													"DatePaid"=>$this->auth->localtime(),
													"TransactionID"=>$transid);
									$where = array("loanSchedID"=>$sid);
														
									//Update loanschedule table here
									$table = 'loanschedule';
									$this->update_data($table, $where, $schedule);
									
									$totaldue +=$adue;
									$pntotal += $payment;
									//$totapay += $payment;
								}	
							$cr += $pntotal;
							//$this->addtransdetails($transid, $acctcode='', $desc='Loans Receivable', $dr='', $pntotal);
							
							
							$crdata[] = array("BankTransID"=>$transid,
								"acct_code"=>'',
								"description"=>'Loans Receivable PN: '.$loanID,
								"DR"=>'',
								"CR"=>$pntotal,
								"dateAdded"=>$this->auth->localtime(),
								"addedBy"=>$this->auth->user_id(),
								"active"=>'1');								
							
						}
					
					if($pay > 0){
						$crdata[] = array("BankTransID"=>$transid,
								"acct_code"=>'',
								"description"=>'Excess Payable',
								"DR"=>'',
								"CR"=>$pay,
								"dateAdded"=>$this->auth->localtime(),
								"addedBy"=>$this->auth->user_id(),
								"active"=>'1');
					}
					
					//if($cr == $amount_in){
					
						//insert batch
						$this->db->insert_batch('transaction_details', $crdata); 
						
					//}
				}
		
		//-----ENDS HERE add transaction_details------
		
		
		
		//add plcollection table
		$colid = $this->plcollection($pensionID, $beginbal, $amount_in, $bankbal, $bankID,$clientID, $pay, $transid);
		
			
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}		
		
	}
	
	function add_dep($data){
	
		$this->db->insert_batch('dependents', $data); 
		return true;
	}
	
	function plcollection($pensionid, $beginbal, $withdraw, $bankbal, $bank, $clientID, $pay, $transid){
	
		$collectiondata = array("pensionID"=>$pensionid,
								"beginbal"=>$beginbal,
								"amountwithdrawn"=>$withdraw,
								"amountLeft"=>$bankbal,
								"toBank"=>$bank,
								"transactionID"=>$transid,
								"addedBY"=>$this->auth->user_id(),
								"dateAdded"=>$this->auth->localtime(),
								"active"=>'1');
			
			$this->db->trans_start();
			//save to collection table
			$colid = $this->UserMgmt->insert_data_to("plcollection", $collectiondata);
			
			//add excess table
			if($pay > 0)
			$this->addexcess($colid, $clientID, $pay);
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			return false;
			else
			return true;
			
	}
	
	
	function addexcess($colid, $clientID, $excess){
		$data = array("collectionid"=>$colid,
							"clientid"=>$clientID,
							"excessamount"=>$excess,
							"dateAdded"=>$this->auth->localtime(),
							"addedBy"=>$this->auth->user_id(),
							"active"=>'1');
							
		$this->db->trans_start();
		$exid = $this->UserMgmt->insert_data_to("plexcess", $data);
		$this->db->trans_complete();
			
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
			
	}
	
	
	function addbanktransaction($trans, $branch, $clientID, $PN, $bankID, $transtype, $paymentType, $amount_in, $amount_out, $date, $particulars, $referenceNo, $checkno, $time, $user){		
		
		//TransID, branchID, clientID, PN, branchBankID, transtype, paymentType, Amount_IN, Amount_OUT, dateOfTransaction, Particulars, referenceNo, Checkno, dateAdded, addedBy
		
		$data = array("TransID"=>$trans,
						"branchID"=>$branch,
						"clientID"=>$clientID,
						"PN"=>$PN,
						"branchBankID"=>$bankID,
						"transtype"=>$transtype,
						"paymentType"=>$paymentType,
						"Amount_IN"=>$amount_in,
						"Amount_OUT"=>$amount_out,
						"dateOfTransaction"=>$date,
						"Particulars"=>$particulars,
						"referenceNo"=>$referenceNo,
						"Checkno"=>$checkno,
						"dateAdded"=>$time,
						"addedBy"=>$user,
						"active"=>'1');	
						
		$this->db->trans_start();
		$trans_id = $this->UserMgmt->insert_data_to('bankstransactions',$data);
		$this->db->trans_complete();
			
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return $trans_id;
		
		
	}
	
	function getCollections($pensionid){
		$sql = "SELECT * FROM plcollection"
					." JOIN bankstransactions ON  bankstransactions.BanktransID = plcollection.transactionID"
					." LEFT JOIN plexcess ON plexcess.collectionid = plcollection.collectionID"
					." WHERE pensionID = '$pensionid'";
		return $this->db->query($sql);
	}
	
	function addnewloan($loandata, $fee){
		
		$this->db->trans_begin();	
		
		//add to loanapplication
		$this->db->insert('loanapplication', $loandata);
		$loanid = $this->db->insert_id();
		
		//add to loan fees
		if(isset($fee)){
				
				foreach($fee as $feeID=>$value){
					$fees[] = array("feeID"=>$feeID,
										"loanID"=>$loanid,
										"value"=>floatval(str_replace(",","",$value)),
										"dateAdded"=>$this->auth->localtime(),
										"addedBy"=>$this->auth->user_id(),
										"active"=>1);
				}
			$this->db->insert_batch('loanfees', $fees); 
		}
		
		//add to loanschedule
		$term = ($loandata['extension'] ? $loandata['extension'] : $loandata['Term']);
		$date = ($loandata['dateStartPayment'] ? date("Y-m-d", strtotime($loandata['dateStartPayment']."-1 month")) : $loandata['dateApplied'] );
		$this->add_loanschedule($term, $loandata['principalAmount'], $date, $loanid, $loandata['paymentmethod']);
			
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			$array = array("loanid"=>$loanid,
					"pid"=>$loandata['LoanType']);
			return $array;	
		}	
		
	}
	
	function add_loanschedule($terms, $loan, $date, $loanid, $method){
		//echo "add Schedule";
		$monthly = round($loan/$terms,2);
		$data =  array();		
		$count =1; 
		$less = 0;
		$olb=$loan;
		$totalpaid = 0;
		$year = date("Y", strtotime($date));
		$m = date("m", strtotime($date));
		$d = date("d", strtotime($this->auth->localdate()));
		$sdate = $year."-".$m."-".$d;
		switch($method){
			case 'M':
			//echo "M";
			$data =array();
				while ($count <= $terms){	
					$totalpaid += $monthly;
					$olb = $loan-$totalpaid;
					$date =  $this->db->query("SELECT DATE_ADD( '".$sdate."', INTERVAL ".$count." MONTH ) as NewDate");
					$date = $date->row();
					$date = date("Y-m-d", strtotime($date->NewDate));
					//echo date("Y-m-d", strtotime($date));
					
					$data[] = array("loanID"=>$loanid,
									"order"=>$count,
									"AmountDue"=>$monthly,
									"LoanBalance"=>$olb,
									"Paid"=>0,
									"DueDate"=>date("Y-m-d", strtotime($date)),
									"DateAdded"=>$this->auth->localtime(),
									"addedBy"=>$this->auth->user_id(),
									"active"=>1);
					$count++;
				}
				
				$this->db->insert_batch("loanschedule", $data);				
				if($this->db->affected_rows() > 0)
				{					
					return true;
				}else return false;
					
			break;
			
			case 'L':
				//$date = $date."+".$terms." month";
				$date =  $this->db->query("SELECT DATE_ADD( '".$sdate."', INTERVAL ".$terms." MONTH ) as NewDate");
				$date = $date->row();
				$data =array();
					 $date = date("Y-m-d", strtotime($date->NewDate));
					 //echo date("Y-m-d", strtotime($date));
					
				$data = array("loanID"=>$loanid,
								"order"=>$count,
								"AmountDue"=>$loan,
								"LoanBalance"=>$olb,
								"Paid"=>0,
								"DueDate"=>date("Y-m-d", strtotime($date)),
								"DateAdded"=>$this->auth->localtime(),
								"addedBy"=>$this->auth->user_id(),
								"active"=>1);										
				if($this->db->insert("loanschedule", $data))
					return true;
				else return false;
			break;
			
			case 'SM':
				
			break;
		}
		
		return true;
	}
	
	//insert loan details
	function addloandetails(){
		
		$loantype = $_POST['loancode'];
		$method = $_POST['method'];
		$loanstatus = $_POST['loanstatus'];
		$loan = $_POST['loanapplied'];
		$terms = $_POST['terms'];
		$status = $loanstatus;
		$monthly = $loan/$terms;
					
		$fff = $this->get_loan_fees($loantype, $loanstatus, $method, $loan, $terms);
		
		$pid =  element('pid', $fff);	
		$pro = array('pid'=>$pid,
					'loantype'=>$loantype);
		$this->session->set_userdata($pro);
		
		$fees =  element('fees', $fff);	
		$totalfees =  element('totalfees', $fff);
		$net =  element('netproceeds', $fff);
		$monthlydue = $loan/$terms;
		
		$data = array("ClientID"=>$this->session->userdata('applicant_id'),
						"branchID"=>$this->auth->branch_id(),
						"AmountApplied"=>$_POST['loanapplied'],
						"Term" =>$_POST['terms'],
						'LoanType'=>$pid,
						'paymentmethod'=>$method,
						'MonthlyInstallment'=>round($monthlydue,2),
						'dateAdded'=>$this->auth->localtime(),
						'addedBy'=>$this->auth->user_id(),
						'dateApplied'=>$this->auth->localtime(),
						'status'=>"processing",
						'LoanProcessor'=>$this->auth->user_id(),
						'active'=>1);		
		
		$this->db->trans_start();	
		
		$this->db->insert('loanapplication', $data);
		$loanid = $this->db->insert_id();
		
		if($fees != ''){
			$f = array();
			foreach($fees as $fee){
				$f[] = array("feeID"=>$fee['feeID'],
								"loanID"=>$loanid,
								"value"=>$fee['feevalue'],
								"dateAdded"=>$this->auth->localtime(),
								"addedBy"=>$this->auth->user_id(),
								"active"=>1);				
			}
			$this->db->insert_batch('loanfees',$f);
		}
		
			
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			
			return false;
		}else{
			$array = array("loanid"=>$loanid);
			$this->session->set_userdata($array);
			return true;	
		}
	}
	
	//insert loan details
	function updateloandetails($loanid){
		
		$loantype = $_POST['loancode'];
		$method = $_POST['method'];
		$loanstatus = $_POST['loanstatus'];
		$loan = $_POST['loanapplied'];
		$terms = $_POST['terms'];
		$status = $loanstatus;
		$monthly = $loan/$terms;
					
		$fff = $this->get_loan_fees($loantype, $loanstatus, $method, $loan, $terms);
		
		$pid =  element('pid', $fff);	
		$pro = array('pid'=>$pid,
					'loantype'=>$loantype);
		$this->session->set_userdata($pro);
		
		$fees =  element('fees', $fff);	
		$totalfees =  element('totalfees', $fff);
		$net =  element('netproceeds', $fff);
		$monthlydue = $loan/$terms;
		
		$data = array("AmountApplied"=>$_POST['loanapplied'],
						"Term" =>$_POST['terms'],
						'LoanType'=>$pid,
						'paymentmethod'=>$method,
						'MonthlyInstallment'=>round($monthlydue,2),
						'dateModified'=>$this->auth->localtime(),
						'modifiedBy'=>$this->auth->user_id(),
						'status'=>"processing");		
		
		$this->db->trans_start();	
		
		$id = array("loanID"=>$loanid);
		$this->update_data('loanapplication', $id, $data);
				
		if($fees != ''){
			$f = array();
			foreach($fees as $fee){
				$f = array("value"=>$fee['feevalue'],
							"dateModified"=>$this->auth->localtime(),
							"modifiedBy"=>$this->auth->user_id());
				$where = array("feeID"=>$fee['feeID'],
								"loanID"=>$loanid,
								"active"=>1);	
								
				if($this->fieldIn("loanfees",$where) ==true){	
					$this->update_data('loanfees', $where, $f);
				}else{
					$f = array("feeID"=>$fee['feeID'],
								"loanID"=>$loanid,
								"value"=>$fee['feevalue'],
								"dateAdded"=>$this->auth->localtime(),
								"addedBy"=>$this->auth->user_id(),
								"active"=>1);
					$this->addtotable("loanfees", $f);
				}
						
			}
			
		}
		
			
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}else{			
			return true;	
		}
	}
	
	function getInterestrates($pid, $term){
		$where = array("productID"=>$pid,  "term"=>$term, "active"=>1);
		$this->db->select('interest');
		$this->db->where($where);
		$this->db->limit(1);
		$get= $this->db->get('interestrates');
		
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		
		return $get;
	}
	
	function loanfees($pid,$loan, $terms, $extendedTerm ){
		$fees = $this->getfees($pid); 
		//echo $pid;
		$totalfees = 0;
		$addfees = 0;
		$deductfees = 0;
		$net = 0;
		$feedetail = array();
		$feedetail['pid'] = $pid;
		
		if($fees->num_rows() > 0){
				
				foreach ($fees->result() as $fee){
					$comp =$fee->comptype;
					$up = $fee->upfront;
					//$feedetail['fee']
					$int = '';
					switch ($comp){
						case 'fixed':
							//echo "fixed";
							$totalfees += $fee->value;
							$feedetail['fees'][] = array(
											"feeID"=>$fee->feeID,
											"comp"=>$comp,
											"charge_type_ID"=>$fee->charge_type_ID,
											"fee_account_id"=>$fee->fee_account_id,
											"feename"=>$fee->feeName,
											"feevalue"=>$fee->value);
							
							if($up == 'add')
							{
								$addfees += $fee->value;
							}else{
								$deductfees += $fee->value;
							}
							
						break;
						
						case 'formula':
							$formula = $fee->value;
							$interest = $this->getInterestrates($pid, $terms);				
														
							if($interest->num_rows() > 0){
								$ir = $interest->row()->interest;
								$interestrates = $ir*.01;
								eval('$newformula='.$formula.';');								
								//$newformula = $interestrates;
							}else{
								$interestrates = 0;	
								eval('$newformula='.$formula.';');
								$ir = $newformula/$loan * 100;
								$interestrates = 0;	
								
							}
							
							$feedetail['interest'] = $ir;
							if($up == 'add')
							{
								$addfees += $newformula;
							}else{
								$deductfees += $newformula;
							}
							$totalfees += $newformula;
							$feedetail['fees'][] = array(
										"feeID"=>$fee->feeID,
										"comp"=>$comp,
										"charge_type_ID"=>$fee->charge_type_ID,
										"fee_account_id"=>$fee->fee_account_id,
										"feename"=>$fee->feeName,
										"upfront"=>$fee->upfront,
										"feevalue"=>$newformula);
						break;
						
					}
					
				}
				
			}
			
			$principal = $loan+$addfees;
			$net = $principal - $totalfees;
			
			$feedetail['totalfees'] = array(
								"name"=>"Total Fees",
								"value"=>$totalfees);
			
			
			$feedetail['netproceeds'] = array(
								"name"=>"Net Proceeds",
								"value"=>$net);
			
			$feedetail['principal'] = array(
								"name"=>"Principal",
								"value"=>$principal);
			
		
		return $feedetail;
	}
	
	//get loan fees
	function get_loan_fees($pid,  $loan, $terms,$extendedTerm){

		//$pid = $this->getproductid($loantype, $loanstatus, $method,$computation);
		
		$fees = $this->getfees($pid); 
		//echo $pid;
		$totalfees = 0;
		$addfees = 0;
		$deductfees = 0;
		$net = 0;
		$feedetail = array();
		$feedetail['pid'] = $pid;
		
		
			if($fees->num_rows() > 0){
				
				foreach ($fees->result() as $fee){
					$comp =$fee->comptype;
					$up = $fee->upfront;
					$int = '';
					switch ($comp){
						case 'fixed':
							//echo "fixed";
							$totalfees += $fee->value;
							$feedetail['fees'][] = array(
											"feeID"=>$fee->feeID,
											"comp"=>$comp,
											"feename"=>$fee->feeName,
											"feevalue"=>$fee->value);
							
							if($up == 'add')
							{
								$addfees += $fee->value;
							}else{
								$deductfees += $fee->value;
							}
							
						break;
						
						case 'formula':
							$formula = $fee->value;
							
							$interest = $this->getInterestrates($pid, $terms);				
															
								if($interest->num_rows() > 0){
									$ir = $interest->row()->interest;
									$interestrates = $ir*.01;
									eval('$newformula='.$formula.';');								
									//$newformula = $interestrates;
								}else{
									eval('$newformula='.$formula.';');
									$ir = $newformula/$loan * 100;
									$interestrates = 0;	
									
								}
								
								$feedetail['interest'] = $ir;
							
							
							if($up == 'add')
							{
								$addfees += $newformula;
							}else{
								$deductfees += $newformula;
							}
							
							$totalfees += $newformula;
							$feedetail['fees'][] = array(
										"feeID"=>$fee->feeID,
										"comp"=>$comp,
										"feename"=>$fee->feeName,
										"feevalue"=>$newformula);
						break;
						
					}
					
				}
				
			}
			
			$principal = $loan+$addfees;
			$net = $principal - $totalfees;
			
			$feedetail['totalfees'] = array(
								"name"=>"Total Fees",
								"value"=>$totalfees);
			
			
			$feedetail['netproceeds'] = array(
								"name"=>"Net Proceeds",
								"value"=>$net);
			
			$feedetail['principal'] = array(
								"name"=>"Principal",
								"value"=>$principal);
			
		
		return $feedetail;
	}
	
	function getProductTerms($data){
		$this->db->select('loantypes.minTerm, loantypes.maxTerm');
		$this->db->where($data);
		$this->db->join("product", "product.productID = loantypes.productID");
		$pro = $this->db->get('loantypes');
		if($pro->num_rows() > 0){
			$prod =$pro->row();
			$re['min'] = $prod->minTerm;
			$re['max'] = $prod->maxTerm;
			return $re;
		}else
			return false;
	}
	
	function getfeeBYPID($pid,$loan,$terms){
		
		$fees = $this->getfees($pid); 
		$totalfees = 0;
		$feedetail = array();
		$feedetail['pid'] = $pid;
		if($fees->num_rows() > 0){
			
			foreach ($fees->result() as $fee){
				$comp =$fee->comptype;
				
				switch ($comp){
					case 'fixed':
						$totalfees += $fee->value;
						$feedetail['fees'][] = array(
										"feeID"=>$fee->feeID,
										"feename"=>$fee->feeName,
										"feevalue"=>$fee->value);
					break;
					
					case 'formula':
						$formula = $fee->value;
						//echo $formula;
						eval('$newformula='.$formula.';');
						//echo $newformula;
						$totalfees += $newformula;
						$feedetail['fees'][] = array(
										"feeID"=>$fee->feeID,
										"feename"=>$fee->feeName,
										"feevalue"=>$newformula);
					break;
					
				}
				
			}
			
		}
		
		$net = $loan-$totalfees;
		
		$feedetail['totalfees'] = array(
							"name"=>"Total Fees",
							"value"=>$totalfees);
		
		$feedetail['netproceeds'] = array(
							"name"=>"Net Proceeds",
							"value"=>$net);
		
		return $feedetail;
	
	}
	
	function checkcollateralOnLoan($loanid){
		$this->db->select('pensionID');
		$this->db->from('loanapplication');
		$this->db->where('loanID',$loanid);	
		
		$rec = $this->db->get();
		$rec = $rec->row();
		
		if($rec->pensionID == '')
			return false;
		else
			return $rec->pensionID ;
		
	}
	
	function addcollateralDetails($loanID, $clientID, $pid, $loantype){
				
		//check if collateral already exists
		$this->db->select('pensionID');
		$this->db->where('loanID', $loanID);
		$this->db->where('active', 1);
		$loan = $this->db->get('loanapplication');
		//echo $loanID;
		
		if($loan->num_rows() > 0){
			$collaID = $loan->row()->pensionID;	
			
			//==========if not exist ADD to DB===========
			if($collaID == NULL){
				$this->db->trans_start();
				if($loantype=='3'){
					//echo $loantype;
					$col = element('pension', $_POST);
					$colID = $this->loansetup->addpension($clientID, $col);
					if( $colID != false){					
						$loanupdate = array("pensionID"=>$colID);
						$where = array("loanID"=>$loanID);
						$this->update_data('loanapplication', $where, $loanupdate);						
					}
				}else{
					$col1 = array("loantypeID"=>$pid,
									"clientID"=>$clientID,
									"dateAdded"=>$this->auth->localtime(),
									"addedBY"=>$this->auth->user_id(),
									"active"=>1);
					$this->db->insert('collaterals', $col1);
					$colID=	$this->db->insert_id();
								
					//echo $colID;
					foreach($_POST['col']  as $procolID=>$value){
						$data = array("collateralID"=>$colID,
									"procolID"=>$procolID,
									"value"=>$value,
									"dateAdded"=>$this->auth->localtime(),
									"addedBy"=>$this->auth->user_id(),
									"active"=>1);
						$this->addtotable("collaterals_details", $data);
					}
					
					$loanupdate = array("pensionID"=>$colID);
					$where = array("loanID"=>$loanID);
					$this->update_data('loanapplication', $where, $loanupdate);
				}
				$this->db->trans_complete();
				
				if($this->db->trans_status() == FALSE)
					return false;
				else
					return true;
			}else{
				//========UPDATE DB=============
				echo "update";
			}
		//============EnD ==============
		}
				
		
	}
	
	function addexistcoltoloan($colID, $loanID){
		$loanupdate = array("pensionID"=>$colID);
		$where = array("loanID"=>$loanID);
		$this->update_data('loanapplication', $where, $loanupdate);
	}
	
	function addcollaterals($loantype,$loanid,$clientID, $pid){
		$col = element($loantype, $_POST);
		
		if($loantype == 'PL'){
			$colID = $this->loansetup->addpension($clientID, $col);
			if( $colID != false){					
				$loanupdate = array("pensionID"=>$colID);
				$where = array("loanID"=>$loanid);
				$this->update_data('loanapplication', $where, $loanupdate);
				return true;
			}else
				return false;
		}else{
			$colla = array();
			$collateralID = $this->checkcollateralOnLoan($loanid);
			if( $collateralID == false){
				$this->db->trans_start();
				
					$col1 = array("loantypeID"=>$pid,
									"clientID"=>$clientID,
									"collateral"=>$loantype.'-'.$clientID,
									"dateAdded"=>$this->auth->localtime(),
									"addedBY"=>$this->auth->user_id(),
									"active"=>1);
					$this->db->insert('collaterals', $col1);
					$colID=	$this->db->insert_id();
					
					$loanupdate = array("pensionID"=>$colID);
					$where = array("loanID"=>$loanid);
					$this->update_data('loanapplication', $where, $loanupdate);
					
					foreach ($col as $name=>$val){
						$colla[] = array(
									'collateralID'=>$colID,
									'name'=>$name,
									'value'=>$val,
									'dateAdded'=>$this->auth->localtime(),
									'addedBy'=>$this->auth->user_id(),
									'active'=>1);
					}
					$this->db->insert_batch('collaterals_details',$colla);
				
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE)
				{
					return false;
				}else{
					return true;	
				}
			}else{
				//update herel=
				if($loantype == 'PL'){
					
					$this->loansetup->update_pension();
					
				}else{
						/*foreach ($col as $name=>$val){
							$colla[] = array(
										'collateralID'=>$collateralID,
										'name'=>$name,
										'value'=>$val,
										'dateModified'=>$this->auth->localtime(),
										'modifiedBy'=>$this->auth->user_id());
							
						}
						$this->db->insert_batch('collaterals_details',$colla, 'collateralID');*/
				}
				return true;
			}
		}
	}
	
	function addIncome($data){
		$this->db->insert_batch('income_expense',$data);
	}
	
	function getCollateralByLoan($id){
		$sql = "SELECT 
					  * 
					FROM
					  collaterals 
					  LEFT JOIN collaterals_details 
						ON collaterals_details.collateralID = collaterals.collateralID 
					  LEFT JOIN productcollateral 
						ON productcollateral.procolID = collaterals_details.procolID 
					  LEFT JOIN product 
						ON product.productID = productcollateral.productID 
					WHERE collaterals.collateralID = $id";
		//echo $sql;
		/*$sql = "SELECT * 
				FROM (collaterals)
				JOIN collaterals_details ON collaterals_details.collateralID=collaterals.collateralID
				 left JOIN productcollateral ON productcollateral.procolID=collaterals_details.procolID
				 left join loantypes on loantypes.loanTypeID = productcollateral.productID				 
				WHERE collaterals.collateralID =  '$id'";		*/
		$res = $this->db->query($sql);
		return $res;
	}
	
	function getCollateralByID($id, $pid){
		$sql = "SELECT 
				  * 
				FROM
				  productcollateral
				  LEFT JOIN product 
					ON product.productID = productcollateral.productID 				
				  LEFT JOIN collaterals_details 
					ON collaterals_details.procolID = productcollateral.procolID
				  LEFT JOIN collaterals 
					ON collaterals.collateralID = collaterals_details.collateralID   
				WHERE  productcollateral.productID = '$pid' and collaterals.collateralID = '$id'";
		//echo $sql;
		/*$sql = "SELECT * 
				FROM (collaterals)
				JOIN collaterals_details ON collaterals_details.collateralID=collaterals.collateralID
				 left JOIN productcollateral ON productcollateral.procolID=collaterals_details.procolID
				 left join loantypes on loantypes.loanTypeID = productcollateral.productID				 
				WHERE collaterals.collateralID =  '$id'";		*/
		$res = $this->db->query($sql);
		return $res;
	}
	
	function getcidetails($pid){
		$this->db->select('*');
		$this->db->from('ci_and_appraisal');
		$this->db->where('productid', $pid);	
		$this->db->where('active', 1);
		return $this->db->get();
	}
	
	
	function addcireport($data){
		$this->db->insert('ci_details', $data);
		return true;
	}
	function updatecireport($data, $where){
		$this->db->where($where);
		$this->db->update("ci_details",$data);		
		return true;
	}
	
	
	function getOutBalance($client){
		$sql = "SELECT loanschedule.loanscheduleID as schedID, loanschedule.loanID, loanschedule.PN, productCode, DateApplied, MIN(DueDate) AS FromDate, maturityDate, COUNT(*) AS months, AmountDue, "
				."SUM(loanschedule.AmountDue) AS OutstandingBal FROM clientinfo
				JOIN loanapplication ON loanapplication.ClientID = clientinfo.ClientID
				JOIN loantypes ON loantypes.loanTypeID = loanapplication.LoanType
				JOIN product ON product.productID = loantypes.productID
				JOIN loanschedule ON loanschedule.PN = loanapplication.PN
				WHERE clientinfo.ClientID = '".$client."' AND loanschedule.Paid = '0' AND loanapplication.status <> 'closed'
				GROUP BY PN";	
		//echo $sql;
		$res =$this->db->query($sql);
		return $res;
	}
	
	
	function getBalancePerDate($client, $date){
		$sql = "SELECT loanscheduleID, loanapplication.PN, productCode, loanapplication.loanID, max(DueDate) as DueDate, sum(AmountDue) as AmountDue, Paid,  DATEDIFF(NOW(),DueDate) AS aging  FROM clientinfo
				JOIN loanapplication ON loanapplication.ClientID = clientinfo.ClientID
				JOIN loantypes ON loantypes.loanTypeID = loanapplication.LoanType
				JOIN product ON product.productID = loantypes.productID
				JOIN loanschedule ON loanschedule.loanID = loanapplication.loanID
				WHERE clientinfo.ClientID = '".$client."' AND DueDate <= '".$date."' + INTERVAL 5 DAY AND Paid = '0' AND loanapplication.status <> 'closed' group by loanapplication.PN";
		$res =$this->db->query($sql);
		return $res;
	}
	
	function addEmployer($emp, $client){		
	
		$data = array(
					"clientID"=>$client,
					"employer"=>$emp['employer'],
					"address"=>$emp['address'],
					"natureOfBusiness"=>$emp['nature'],
					"contact"=>$emp['contact'],
					"position"=>$emp['position'],
					"lengthOfService"=>$emp['length'],
					"status"=>$emp['status'],
					"monthlySalary"=>$emp['salary'],
					"addedBy"=>$this->auth->user_id(),
					"dateAdded"=>$this->auth->localtime(),
					"active"=>1);
					
		$pars = array("clientID"=>$client,
					"active"=>1);
					
		if($this->fieldIn("employmentinfo",$pars) ==true){			
			 $this->update_data('employmentinfo', $pars, $data);			
			return true;
		}else{	
						
			if($this->db->insert('employmentinfo', $data))
			{				
				return true;
			}else				
				return false;
		}
	}
	
	function getExistingProcessByClientID($clientID){
		
		$sql = "select * from loanapplication where clientID = '$clientID' and active='1' and (status = 'processing' or status = 'CI' or status ='approval')";
		$table='loanapplication';
		
		/*$this->db->where("clientID",$clientID);
		$this->db->where("active", 1);
		$this->db->where("status", "processing");
		$this->db->or_where("status", "CI");
		$this->db->or_where("status", "approval");
		*/
		$loan = $this->db->query($sql);
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		if($loan->num_rows() > 0)
		return true;
		else
		return false;
	}	
	
	function getcollaterals($pcode,$pid,$pensionid){
		if(strpos($pcode,"PL") !== false){
			if($pensionid == '')
				return $content = $this->load->view('loans/forms/plform', true);
			else
				return $this->loansetup->pensioninfo($pensionid);
		}else{
			if(!empty($pensionid)){
				//echo $pensionid;
				$col = $this->getCollateralByLoan($pensionid);		
				
				if($col->num_rows() > 0){
					foreach($col->result()
					as $c){
						$this->table->add_row(array('width'=>'30%', 'data'=>'<label>'.strtoupper($c->collateralname).'</label> : '),$c->value);									
					}	
					return $this->table->generate();
				}else{
					//echo "yeah2x";
					return $this->form->collateralForm($pid, $loanid=NULL, $pcode);
				}
			}else{
				
				return $this->form->collateralForm($pid, $loanid=NULL, $pcode);
			}
		}
	}
	
	function updateloanfees($post){
		
		//UPDATE Loan Application Data
		$loandata = array("principalAmount"=>$post['principal'],
								"netproceeds"=>$post['netproceeds'],
								"status"=>$post['loanstatus'],
								"dateModified"=>$this->auth->localtime(),
								"modifiedBy"=>$this->auth->user_id());
		$where = array("loanID"=>$post['loanid']);		
		$this->update_data("loanapplication", $where, $loandata);
		
		//UPDATE Loan Fees
		foreach($post['feeid'] as $fid => $fees){
			$feedata = array("value"=>floatval(str_replace(",","",$fees)),
								"dateModified"=>$this->auth->localtime(),
								"modifiedBy"=>$this->auth->user_id());
			$where = array("loanfeeID"=>$fid);
			$this->update_data("loanfees", $where, $feedata);
		}
		
		return true;
	}
		
	function Clientledger($clientID){
		$sql = "SELECT 
		  loanschedule.loanscheduleID AS schedID,
		  loanschedule.loanID,
		  loanschedule.PN,
		  productCode,
		  DateApplied,
		  maturityDate,
		  AmountDue,
		  Paid  
		FROM
		  clientinfo 
		  JOIN loanapplication 
			ON loanapplication.ClientID = clientinfo.ClientID 
		  JOIN loantypes 
			ON loantypes.loanTypeID = loanapplication.LoanType 
		  JOIN product 
			ON product.productID = loantypes.productID 
		  JOIN loanschedule 
			ON loanschedule.PN = loanapplication.PN 
		WHERE clientinfo.ClientID = '$clientID'   
		  AND loanapplication.status <> 'closed' 
		ORDER BY PN ASC";
		return $this->db->query($sql);
		
	}
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>