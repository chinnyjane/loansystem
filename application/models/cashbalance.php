<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CashBalance extends CI_Model {

	function getTransactionType($trans) {
		$this->db->select("transCatID, transTypeID, transType");
		$this->db->from("transcategory");
		$this->db->join("transactiontype","transactiontype.transcategory = transcategory.transCatID");
		$this->db->where("transCatName", $trans);
		return $this->db->get();
	}
	
	//FINAL BALANCE
	function getLastBalance($branchBankID){
		$sql = "select (CASE 
					when (select EndingBal from banksummary where branchbankID = '".$branchBankID."' order by banksumID DESC limit 0,1) is NULL
				THEN (select BeginBalance from bankofbranch where branchBankID = '".$branchBankID."') END) as BeginBal
					from bankofbranch 
				where branchBankID = '".$branchBankID."' ";
		echo $sql;
	}
	
	function EndOfDateBalance($date,$branchBankID){
			$sql = "select 
				(CASE
					when (select actualBalance from banksummary where branchbankID = '".$branchBankID."' and dateOfTransaction = '".$date."' order by dateOfTransaction DESC limit 0,1) is NULL
				THEN 0
				ELSE (select actualBalance from banksummary where branchbankID = '".$branchBankID."' and dateOfTransaction = '".$date."' 
					order by dateOfTransaction DESC limit 0,1) END) as actualbalance,
				(CASE 
					when 
					(select EndingBal from banksummary where branchbankID = '".$branchBankID."' and dateOfTransaction < '".$date."' order by dateOfTransaction DESC limit 0,1) is NULL
				THEN (select BeginBalance from bankofbranch where branchBankID = '".$branchBankID."') 
				ELSE
					(select EndingBal from banksummary where branchbankID = '".$branchBankID."' and dateOfTransaction < '".$date."' order by dateOfTransaction DESC limit 0,1)
				END) as BeginBal,
				(case WHEN
					(select sum(Amount_IN) from bankstransactions 
						join transcategory on transCatname = 'collection'
						join transactiontype on transactiontype.transcategory = transcategory.transCatID and
						transactiontype.transTypeID = bankstransactions.transtype
						where bankstransactions.dateOfTransaction = '".$date."' and branchbankID = '".$branchBankID."'  and bankstransactions.isdeleted <> 1 ) is NULL 
				THEN 0 
				ELSE  (select sum(Amount_IN) from bankstransactions 
						join transcategory on transCatname = 'collection'
						join transactiontype on transactiontype.transcategory = transcategory.transCatID and
						transactiontype.transTypeID = bankstransactions.transtype
						where bankstransactions.dateOfTransaction = '".$date."' and branchbankID = '".$branchBankID."'  and bankstransactions.isdeleted <> 1 ) 
				END ) as TotalCol,
					(case when (select sum(Amount_OUT) from bankstransactions
						join transcategory on transCatname = 'disbursement'
						join transactiontype on transactiontype.transcategory = transcategory.transCatID and
						transactiontype.transTypeID = bankstransactions.transtype
						where bankstransactions.dateOfTransaction = '".$date."' and branchbankID = '".$branchBankID."'  and bankstransactions.isdeleted <> 1 ) is NULL 
						then 0 
						ELSE  (select sum(Amount_OUT) from bankstransactions 
						join transcategory on transCatname = 'disbursement'
						join transactiontype on transactiontype.transcategory = transcategory.transCatID and
						transactiontype.transTypeID = bankstransactions.transtype
						where bankstransactions.dateOfTransaction = '".$date."' and branchbankID = '".$branchBankID."'  and bankstransactions.isdeleted <> 1 ) end ) as TotalDis ,
					(case when (select sum(Amount_IN)  from bankstransactions 
								JOIN  `transcategory` ON  `transcategory`.`transCatname` =  'adjustment'
								JOIN  `transactiontype` ON  `transactiontype`.`transCategory` =  `transcategory`.`transCatID`				
								AND transactiontype.transTypeID = bankstransactions.transtype
								where bankstransactions.dateOfTransaction = '".$date."' and bankstransactions.branchbankID = '".$branchBankID."'  and bankstransactions.isdeleted <> 1 ) is NULL 
								then 0 
								ELSE ( select sum(Amount_IN)  from bankstransactions 
								join transcategory on transcategory.transCatname = 'adjustment'
								join  `transactiontype` ON  `transactiontype`.`transCategory` =  `transcategory`.`transCatID`				
								AND transactiontype.transTypeID = bankstransactions.transtype
								where bankstransactions.dateOfTransaction = '".$date."' and bankstransactions.branchbankID = '".$branchBankID."'  and bankstransactions.isdeleted <> 1 ) END) as TotalAdjadd,
					(case when (select  sum(Amount_OUT) from bankstransactions 
								JOIN  `transcategory` ON  `transcategory`.`transCatname` =  'adjustment'
								JOIN  `transactiontype` ON  `transactiontype`.`transCategory` =  `transcategory`.`transCatID`				
								AND transactiontype.transTypeID = bankstransactions.transtype
								where bankstransactions.dateOfTransaction = '".$date."' and bankstransactions.branchbankID = '".$branchBankID."'  and bankstransactions.isdeleted <> 1 ) is NULL 
								then 0 
								ELSE  (select sum(Amount_OUT) from bankstransactions 
								join transcategory on transcategory.transCatname = 'adjustment'
								join  `transactiontype` ON  `transactiontype`.`transCategory` =  `transcategory`.`transCatID`				
								AND transactiontype.transTypeID = bankstransactions.transtype
								where bankstransactions.dateOfTransaction = '".$date."' and bankstransactions.branchbankID = '".$branchBankID."'  and bankstransactions.isdeleted <> 1 ) END) as TotalAdjless
				from bankofbranch 
				where branchBankID = '".$branchBankID."' ";
			if($this->auth->role_id() == 11)
			{
			//echo $sql;
			}
			
		return $this->db->query($sql);
	}
	
	function EndOfDateSummary($branchID,$date){
		/*$data = array("branchID" =>  $branchID,
					"dateTransaction"=>$date);
		$cmc = $this->Loansmodel->get_data_from("cmctransaction", $data);
		if($cmc->num_rows() <= 0){*/
			$bank = $this->Cashmodel->getbanklistonbranch($branchID);
			if($bank->num_rows() > 0){
				$begin = 0;
				$tc =0;
				$td =0;
				$ta =0;
				foreach($bank->result() as $b){
					$sum = $this->EndOfDateBalance($date,$b->branchBankID)->row();
					$adj = $sum->TotalAdjadd + (-1 * $sum->TotalAdjless);
					$begin += $sum->BeginBal;
					$tc += $sum->TotalCol;
					$td += $sum->TotalDis;
					$ta += $adj;
				}
				$end = $begin + $tc - $td + $ta;
				$res = array("begin"=>$begin,
							"totalcol"=> $tc,
							"totaldis"=>$td,
							"totaladj"=>$ta,
							"end"=>$end);	
				return $res;
			}
		/*}else{
			foreach ($cmc->result() as $c){
				$res = array("begin"=>$c->BeginningBal,
							"totalcol"=> $c->TotalCollections,
							"totaldis"=>$c->TotalDisbursement,
							"totaladj"=>$c->TotalAdjustment,
							"end"=>$c->EndingBal);
			}
		}*/
		
	}
	
	function sumofallbanks($transid){
		$sql = "select sum(beginningBal) as beginningBal, sum(EndingBal) as EndingBal, sum(TotalCollections) as TotalCollections, sum(TotalDisbursement) as TotalDisbursement, sum(TotalAdjustment) as TotalAdjustment
				from banksummary where transid='$transid'";
		//echo $sql;
		return $this->db->query($sql);
	}
	
	
} ?>