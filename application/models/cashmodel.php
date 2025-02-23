<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cashmodel extends CI_Model {
	function updateBatch($table, $data,$id){
		$this->db->update_batch($table, $data,$id);
	}
	
	function getbanklist($transID){
		$this->db->select('*');
		$this->db->from('banksummary');
		$this->db->join('banks', 'banks.bankID = banksummary.branchbankID');
		$this->db->where("banksummary.transID", $transID);
		return $this->db->get();
	}
	//bank
	function getbankBal($bankID){
		$this->db->select('EndingBal');
		$this->db->where("bankID" , $bankID);
		$this->db->order_by("banksumID", "DESC");
		$this->db->limit(1);
		$this->db->from('banksummary');
		return $this->db->get();
	}
	
	function CMCbyBank($bank, $transID){
		$this->db->select('*');
		$this->db->from('bankstransactions');
		$where = array("bankstransactions.transID"=>$transID);
		$this->db->where($where);
		$this->db->where($bank);
		$this->db->join('transactiontype', 'transactiontype.transTypeID = bankstransactions.transtype','left' );
		$this->db->join('transcategory', 'transcategory.transCatID = transactiontype.transcategory','left' );
		$this->db->join('paymenttype', 'paymenttype.paymentTypeID = bankstransactions.paymentType','left');
		//$this->db->join('banks', 'banks.bankID = bankofbranch.bankID' );
		$this->db->order_by('transCatID', "ASC");
		return $this->db->get();
	}
	
	function CMCbyBank_old($bank, $transID){
		$this->db->select('*');
		$this->db->from('bankstransactions');
		$where = array("bankstransactions.transID"=>$transID,
							"bankstransactions.branchBankID"=>$bank,
							"bankstransactions.isdeleted<>"=>1);
		$this->db->where($where);
		$this->db->join('transactiontype', 'transactiontype.transTypeID = bankstransactions.transtype','left' );
		$this->db->join('transcategory', 'transcategory.transCatID = transactiontype.transcategory','left' );
		$this->db->join('paymenttype', 'paymenttype.paymentTypeID = bankstransactions.paymentType','left');
		//$this->db->join('banks', 'banks.bankID = bankofbranch.bankID' );
		$this->db->order_by('transCatID', "ASC");
		return $this->db->get();
	}
	
	function getCMCTransactions($transID,$transtype){
		$sql = "SELECT BanktransID, bankstransactions.PN, bankstransactions.referenceNo, transactiontype.transType as transtype, bankstransactions.verified, bankstransactions.dateVerified, 
				(CASE WHEN bankstransactions.paymentType IS NOT NULL
				THEN (select typeOfPayment from paymenttype where paymentTypeID = bankstransactions.paymentType)
				ELSE bankstransactions.paymenttype
				END) as paymentType, 
				banks.bankCode, bankofbranch.branchCode, bankstransactions.Particulars,  bankstransactions.explanation, bankstransactions.Checkno, transactiontype.transType, Amount_IN, Amount_OUT 
				FROM (
				`bankstransactions`
				)
				JOIN  `bankofbranch` ON  `bankofbranch`.`branchbankID` =  `bankstransactions`.`branchbankID` 
				JOIN  `banks` ON  `banks`.`bankID` =  `bankofbranch`.`bankID` 
				JOIN  `transcategory` ON  `transcategory`.`transCatname` =  '".$transtype."'
				JOIN  `transactiontype` ON  `transactiontype`.`transcategory` =  `transcategory`.`transCatID`				
				AND transactiontype.transTypeID = bankstransactions.transtype
				JOIN  `cmctransaction` ON  `cmctransaction`.`transID` =  `bankstransactions`.`transID` 
				WHERE  `bankstransactions`.`transID` =  '".$transID."' and bankstransactions.isdeleted <> 1 order by referenceNO ASC";
		/*$this->db->select('*');
		$this->db->from('bankstransactions');
		$this->db->join('bankofbranch', 'bankofbranch.branchbankID = bankstransactions.branchbankID');
		$this->db->join('banks', 'banks.bankID = bankofbranch.bankID');
		$this->db->join('transcategory',"transcategory.transCatname = $transtype");
		$this->db->join('transactiontype',"transactiontype.transcategory = transcategory.transCatID and transactiontype.transTypeID = bankstransactions.transtype");
		$this->db->join('cmctransaction', 'cmctransaction.transID = bankstransactions.transID');		
		//$this->db->order_by("bankstransactions.branchbankID", "ASC");
		$this->db->order_by("bankstransactions.BanktransID", "DESC");
		$this->db->where("bankstransactions.transID", $transID);*/
		//echo $sql;
		return $this->db->query($sql);
	}
	
	function getCMCTransactionsbyDate($date,$transtype){
			$sql = "SELECT cmctransaction.branchID,BanktransID, bankstransactions.PN, bankstransactions.referenceNo, transactiontype.transType as transtype, bankstransactions.verified, bankstransactions.dateVerified, 
				(CASE WHEN bankstransactions.paymentType IS NOT NULL
				THEN (select typeOfPayment from paymenttype where paymentTypeID = bankstransactions.paymentType)
				ELSE bankstransactions.paymenttype
				END) as paymentType, 
				banks.bankCode, bankofbranch.branchCode, bankstransactions.Particulars,  bankstransactions.explanation, bankstransactions.Checkno, transactiontype.transType, Amount_IN, Amount_OUT 
				FROM (
				`bankstransactions`
				)
				JOIN  `bankofbranch` ON  `bankofbranch`.`branchbankID` =  `bankstransactions`.`branchbankID` 
				JOIN  `banks` ON  `banks`.`bankID` =  `bankofbranch`.`bankID` 
				JOIN  `transcategory` ON  `transcategory`.`transCatname` =  '".$transtype."'
				JOIN  `transactiontype` ON  `transactiontype`.`transcategory` =  `transcategory`.`transCatID`				
				AND transactiontype.transTypeID = bankstransactions.transtype
				JOIN  `cmctransaction` ON  `cmctransaction`.`transID` =  `bankstransactions`.`transID` 
				WHERE `bankstransactions`.`dateOfTransaction` =  '".$date."' and bankstransactions.isdeleted <> 1 order by  transactiontype.transType ASC";
				/*
		$sql = "SELECT cmctransaction.branchID, BanktransID, bankstransactions.PN, bankstransactions.referenceNo, transactiontype.transType as transtype, bankstransactions.verified, bankstransactions.dateVerified, 
				(CASE WHEN bankstransactions.paymentType IS NOT NULL
				THEN (select typeOfPayment from paymenttype where paymentTypeID = bankstransactions.paymentType)
				ELSE bankstransactions.paymenttype
				END) as paymentType, 
				banks.bankCode, bankofbranch.branchCode, bankstransactions.Particulars,  bankstransactions.explanation, bankstransactions.Checkno, transactiontype.transType, Amount_IN , Amount_OUT
				FROM (
				`bankstransactions`
				)
				JOIN  `bankofbranch` ON  `bankofbranch`.`branchbankID` =  `bankstransactions`.`branchbankID` 
				JOIN  `banks` ON  `banks`.`bankID` =  `bankofbranch`.`bankID` 
				JOIN  `transcategory` ON  `transcategory`.`transCatname` =  '".$transtype."'
				JOIN  `transactiontype` ON  `transactiontype`.`transcategory` =  `transcategory`.`transCatID`				
				AND transactiontype.transTypeID = bankstransactions.transtype
				JOIN  `cmctransaction` ON  `cmctransaction`.`transID` =  `bankstransactions`.`transID` 
				WHERE  `bankstransactions`.`dateOfTransaction` =  '".$date."' and bankstransactions.isdeleted <> 1 group by transactiontype.transType, cmctransaction.branchID order by  transactiontype.transType ASC";*/
		
		return $this->db->query($sql);
	}
	
	function getCMCSum($transID){
		$this->db->select('*');
		$this->db->from('banksummary');
		$this->db->join('banks', 'banks.bankID = banksummary.branchbankID');
		//$this->db->join('cmctransaction', 'cmctransaction.transID = banksummary.transID');		
		//$this->db->order_by("bankstransactions.branchbankID", "ASC");
		$this->db->order_by("banksummary.transID", "DESC");
		$this->db->where("banksummary.transID", $transID);
		return $this->db->get();
	}
	//transaction
	function getBalanceofBranch($branch){
		$this->db->select('EndingBal');
		$this->db->from('cmctransaction');
		$this->db->where('branchID', $branch);
		$this->db->order_by("transID", "DESC");
		$this->db->limit(1);
		return $this->db->get();
	}
	
	function updatebanksummary(){
		$this->db->where();
	}
	
	function getTransaction($date, $branch, $limit, $from){
		if($date==''){
			$data = array("branchID"=>$branch,
								"cmctransaction.isdeleted <>" => 1);
			if($limit != '')
			$this->db->limit($limit, $from);
		}
		else
			$data = $date;
		
		$this->db->order_by("dateTransaction", "DESC");
		$this->db->where($data);
		$this->db->where("status <>","close");
		$res['rec'] = $this->db->get('cmctransaction');
		$res['total'] = $this->db->count_all_results();
		//$this->db->last_query();
		//$this->output->enable_profiler(TRUE);
		
		return $res;		
	}
	
	function CMCSummary(){
		$this->db->select('*');
		$this->db->where("branches.active", 1);
		$this->db->from('branches');
		//$this->db->join('bankofbranch', 'bankofbranch.branchid=branches.id');
		//$this->db->join('cmctransaction', 'cmctransaction.branchID=branches.id');
		$this->db->order_by('id', "ASC");
		return $this->db->get();
	}
	
	function consolidatedCMC($date){
		if($date != ''){
			$data = array("dateTransaction"=>$date);
			$this->db->where($data);			
		}
		
		$this->db->select('cmctransaction.*, branchname');
		
		$this->db->from('cmctransaction');
		$this->db->join('branches', 'branches.id=cmctransaction.branchID');
		$this->db->order_by('branchname', "ASC");
		return $this->db->get();
	}
	
	function CMCByDate($date, $brid){
		$this->db->select("banksummary.beginningBal as BeginBal, sum(banktrans.Amount_IN) as Collections, sum(banktrans.Amount_OUT) as Disbursement, banksummary.beginningBal + sum(banktrans.Amount_IN) - sum(banktrans.Amount_OUT) as EndBal");
		$this->db->from('cmctransaction');
		$this->db->join('banksummary','banksummary.transID = cmctransaction.transID');
		$this->db->join('bankstransactions as banktrans','banktrans.transID = cmctransaction.transID ');
		$this->db->where('dateTransaction', $date);
		$this->db->where('cmctransaction.branchID', $brid);
		return $this->db->get();
	}
	
	function totalcollect($date,$transID){
		$this->db->select_sum('Amount_in');
		$this->db->where("transID", $transid);
		return $this->db->get('bankstransactions');
	}
	
	function getbanklistonbranch($branch){
		$this->db->select('*');
		$this->db->from('bankofbranch');
		$this->db->join('banks', 'banks.bankID = bankofbranch.bankID', 'left');
		$this->db->where("branchID", $branch);
		//$this->db->where("bankofbranch.isdeleted !=", 1);
		$this->db->where("bankofbranch.active", 1);
		return $this->db->get();
	}
	
	function getbanktotal($branch){
		$this->db->select('SUM(BeginBalance) as TotalBal');
		$this->db->from('bankofbranch');
		$this->db->join('banks', 'banks.bankID = bankofbranch.bankID');
		$this->db->where("branchID", $branch);
		return $this->db->get();
	}
	
	function getbranchsummary($branch){
		$sql="select case( EndingBal is null
						ELSE (CASE 
								(WHEN select sum(BeginBalance) from bankofbranch where branchid='$branch'
								ELSE 0
								THEN select sum(BeginBalance) from bankofbranch where branchid='$branch') )
						THEN EndingBal
						END) as EndingBal, 
					case( TotalCollections is null
						ELSE (CASE 
								(WHEN select sum(BeginBalance) from bankofbranch where branchid='$branch'
								ELSE 0
								THEN select sum(BeginBalance) from bankofbranch where branchid='$branch') )
						THEN TotalCollections
						END) as TotalCollections
					from cmctransaction where branchid='$branch'
					";
		return $this->db->get($sql);
	}
	function getbankofbranch($branchbankID){
		$this->db->select('*');
		$this->db->from('bankofbranch');
		$this->db->join('banks', 'banks.bankID = bankofbranch.bankID');
		$this->db->where("branchBankID", $branchbankID);
		return $this->db->get();
	}
	
	function getbalbankofbranch($branchID, $bankID){
		$this->db->select('*');
		$this->db->from('bankofbranch');
		$this->db->where("branchID", $branchID);
		$this->db->where("bankID", $bankID);
		return $this->db->get();
	}
	
	function getTransof($branch,$transtype,$start, $limit){
		$sql = "SELECT branchname, Checkno, explanation, bankstransactions.PN, bankstransactions.referenceNo, 
				(CASE WHEN bankstransactions.paymentType <> 0
				THEN (select typeOfPayment from paymenttype where paymentTypeID = bankstransactions.paymentType)
				ELSE bankstransactions.paymenttype
				END) as paymentType, 
				banks.bankCode, bankofbranch.bankAccount, bankstransactions.Particulars,  bankstransactions.dateOfTransaction, transactiontype.transType, Amount_IN, Amount_OUT 
				FROM (
				`bankstransactions`
				)
				JOIN branches on branches.id = '".$branch."'
				JOIN  `bankofbranch` ON  `bankofbranch`.`branchbankID` =  `bankstransactions`.`branchbankID` 
					and `bankofbranch`.`branchID` = branches.id
				JOIN  `banks` ON  `banks`.`bankID` =  `bankofbranch`.`bankID` 
				JOIN  `transcategory` ON  `transcategory`.`transCatname` =  '".$transtype."'
				JOIN  `transactiontype` ON  `transactiontype`.`transcategory` =  `transcategory`.`transCatID`				
				AND transactiontype.transTypeID = bankstransactions.transtype
				JOIN  `cmctransaction` ON  `cmctransaction`.`transID` =  `bankstransactions`.`transID` 
				order by BanktransID DESC";
				//echo $start."<br/>";
		if (!is_null($start ) )
		$sql = $sql." limit $start, $limit";
		//echo $sql;
		return $this->db->query($sql);		
	}
	function truncate_table($table){
		$this->db->truncate($table); 
	}
	
	function removeTrans($where, $data){
		$this->db->where($where);
		if($this->db->update('bankstransactions', $data))
		return true;
		else return false;
	}
	
	
	function getrecap($transid){
		$sql = "SELECT recapofdeposits.*, banks.bankCode, bankofbranch.branchCode, transactiontype.transType as type FROM recapofdeposits 
					JOIN bankofbranch ON branchBankID = recapofdeposits.bankBranchID 
					JOIN banks ON banks.bankID = bankofbranch.bankID 
					JOIN transactiontype ON  transactiontype.transTypeID =  recapofdeposits.typeofDeposit
					WHERE transID = '$transid' and recapofdeposits.isdeleted <> '1'";
		//echo $sql;
		return $this->db->query($sql);
	}
	
	function getbranchBank($branch, $transid){
		$sql = "SELECT banksummary.*, banks.bankCode FROM banksummary "
				."JOIN bankofbranch ON bankofbranch.branchBankID = banksummary.branchbankID "
				."JOIN banks ON banks.bankID = bankofbranch.bankID "
				."WHERE banksummary.branchbankID = '$branch' AND banksummary.transID = '$transid'";
		//echo $sql;
		return $this->db->query($sql);
	}
	
	function getBankbyID($bank){
		$this->db->select('banks.*');
		$this->db->join("banks","banks.bankID=bankofbranch.bankID");
		$this->db->where("branchBankID", $bank);
		$this->db->from("bankofbranch");
		return $this->db->get();
	}
	
	function getTransType($id){
		$this->db->select("*");
		$this->db->from("transactiontype");
		$this->db->where('transTypeID', $id);
		return $this->db->get();
	}
	
	function lastcmctransaction(){
		$this->db->select('*');
		$this->db->from('lasttransactions');
		return $this->db->get();
	}
	
	function lastverfiedCMC(){
		$this->db->select('*');
		$this->db->from('lastverifiedcmc');
		return $this->db->get();
	}
	
	function branchsummarybymonth($date){
		$this->db->SELECT('branchID, branchname, SUM(TotalCollections), SUM(TotalDisbursement), SUM(TotalAdjustment)');
		$this->db->FROM('cmctransaction');
		$this->db->join('branches','branches.id = cmctransaction.branchID');
		$this->db->WHERE('cmctransaction.active','1');
		$this->db->where('cmctransaction.STATUS <> ','open');
		$this->db->like('dateTransaction',$date, 'after');
		$this->db->group_by('branchID');
		return $this->db->get();
	}
	
	function currentstatus(){		
		$this->db->SELECT('MAX( dateTransaction ) , branchID, branchname, EndingBal');
		$this->db->FROM('cmctransaction');
		$this->db->join('branches','branches.id = cmctransaction.branchID');
		$this->db->WHERE('cmctransaction.active','1');
		$this->db->where('cmctransaction.STATUS <> ','open');
		$this->db->group_by('branchID');
		$this->db->order_by('branchname','ASC');
		return $this->db->get();
	}
	
	//by year group by month summary
	function ycfccashsummary($year, $branch){
		$this->db->SELECT("DATE_FORMAT(dateTransaction,'%Y-%m') as month , SUM(TotalCollections) as col, SUM(TotalDisbursement) as dis, SUM(TotalAdjustment) as adj", FALSE);
		$this->db->FROM('cmctransaction');
		$this->db->join('branches','branches.id = cmctransaction.branchID');
		$this->db->WHERE('cmctransaction.active','1');
		if($branch != 'all')
		$this->db->WHERE('cmctransaction.branchID',$branch);
		$this->db->where('cmctransaction.STATUS <> ','open');
		$this->db->like('dateTransaction',$year, 'after');
		$this->db->group_by('month(dateTransaction)');
		return $this->db->get();
	}
	
	function getBankTransactions($bankofBranchID, $dateStart = NULL, $dateEnd = NULL){
		$sql = "SELECT 
				particulars, PN, Amount_IN, Amount_OUT, referenceNo, transactiontype.transType as trans, transcategory.`transCatName` as type, bankCode, dateOfTransaction,Checkno, bankstransactions.dateAdded as dateAdd, bankstransactions.`dateModified` as dateModified
				FROM
				  bankstransactions 				  
				  JOIN transactiontype ON transactiontype.`transTypeID` = bankstransactions.`transtype`
				  JOIN transcategory ON transcategory.`transCatID` = transactiontype.`transCategory`
				  JOIN bankofbranch ON bankofbranch.`branchBankID` = bankstransactions.`branchBankID`
				  JOIN banks ON banks.`bankID` = bankofbranch.`bankID`  
				WHERE bankstransactions.branchBankID = '$bankofBranchID' 
				  AND bankstransactions.isdeleted <> 1 ";
		if($dateStart != NULL ){
			$sql .= "AND (dateOfTransaction $dateStart AND $dateEnd)";
		}
		$sql .= " ORDER BY dateOfTransaction ASC";
		
		return $this->db->query($sql);
	}
}?>