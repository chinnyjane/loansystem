<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loans extends CI_Model {

	function PLSchedule($pensionID){
		$d = date("Y-m", strtotime($this->auth->localdate()));
		$sql = "SELECT loanschedule.DueDate, loanschedule.AmountDue, loanapplication.PN, loanschedule.Paid FROM pensioninfo
			JOIN loanapplication ON loanapplication.pensionID = pensioninfo.PensionID
			JOIN loanschedule ON loanschedule.loanID = loanapplication.loanID
			join loantypes on loantypes.loanTypeID = loanapplication.LoanType
			join product on product.productID = loantypes.productID
			WHERE pensioninfo.PensionID = '$pensionID' and productCode = 'PL'  and loanapplication.active='1'
			AND (loanschedule.DueDate > CURDATE() and loanschedule.DueDate NOT LIKE '".$d."%' )and loanapplication.status <> 'canceled' and loanapplication.status <> 'closed'
			ORDER BY DueDate ASC";
			 if($this->auth->perms('debug',$this->auth->user_id(),3) == true) { 
				//echo $sql;
			 }
		$pl = $this->db->query($sql);
		return $pl;
	}
	
	function PLBalance($pensionID){
		$d = date("Y-m", strtotime($this->auth->localdate()));
		$sql = "SELECT sum( loanschedule.AmountDue) as totalPL, sum(loanschedule.Paid) as totalPaid, max( loanschedule.DueDate) as lastDate FROM pensioninfo
			JOIN loanapplication ON loanapplication.pensionID = pensioninfo.PensionID
			JOIN loanschedule ON loanschedule.loanID = loanapplication.loanID
			join loantypes on loantypes.loanTypeID = loanapplication.LoanType
			join product on product.productID = loantypes.productID
			WHERE pensioninfo.PensionID = '$pensionID' and loanschedule.active = '1' and productCode = 'PL'  and loanapplication.active='1'
			AND (loanschedule.DueDate > CURDATE() and loanschedule.DueDate NOT LIKE '".$d."%' )and (loanapplication.status <> 'canceled' and loanapplication.status <> 'closed' and loanapplication.status <> 'declined')
			ORDER BY DueDate ASC";
		//echo $sql;
		$pl = $this->db->query($sql);
		return $pl;
	}
	
	function CountTerms($pensionID){
		$d = date("Y-m", strtotime($this->auth->localdate()));
		$sql = "SELECT loanschedule.DueDate, loanschedule.AmountDue, loanapplication.PN, loanschedule.Paid FROM pensioninfo
			JOIN loanapplication ON loanapplication.pensionID = pensioninfo.PensionID
			JOIN loanschedule ON loanschedule.loanID = loanapplication.loanID
			join loantypes on loantypes.loanTypeID = loanapplication.LoanType
			join product on product.productID = loantypes.productID
			WHERE pensioninfo.PensionID = '$pensionID' and productCode = 'PL'  and loanapplication.active='1'
			AND (loanschedule.DueDate > CURDATE() and loanschedule.DueDate NOT LIKE '".$d."%' )and loanapplication.status <> 'canceled' and loanapplication.status <> 'closed'
			group by YEAR(DueDate), MONTH(DueDate)";
		//echo $sql;
		$pl = $this->db->query($sql);
		return $pl;
	}
	
	function getPDI($due, $monthly){
		$datetime1 = date_create($due);
		$datetime2 = date_create(date("Y-m-d", strtotime($this->auth->localdate())));
		$interval = date_diff($datetime1, $datetime2);
		//$m = $interval->format('%m');
		$d = $interval->format('%d');
		$days = $interval->format('%R%a');
		$m = round($days/12);
		if($days >5){
			$pdi1 = $monthly * $m * .02;
			
			$pdi2 = $monthly * .02 *12/365 * $d;
			$pdi = $pdi1+$pdi2;
			//echo $pdi;
		}else $pdi = 0;
		return $pdi;
	}
	
	
	function balance($loanID){
		
		//check balance from loanapplication table
		$sql = "Select loanbalance from loanapplication where loanID='".$loanID."'";
		$q = $this->db->query($sql)->row();
		
		if($q->loanbalance != "" or $q->loanbalance != null OR $q->loanbalance == 0){
			$balance = $q->loanbalance;
		}else{
			$balance = false;
		}
	
		return $balance;
		
	}
	
	//created March 30 2017
	
	function due($loanID){
		$sql = 'select loanscheduleID, loanapplication.loanID, DueDate, AmountDue, loanapplication.PN, loantype, DATEDIFF(NOW(),DueDate) as aging from loanschedule
			JOIN loanapplication ON loanapplication.loanID = loanschedule.loanID 
			where loanschedule.loanID = "'.$loanID.'" AND 
					DueDate < "'.$this->auth->localdate().'" 
					AND Paid <> AmountDue ';
		//echo $sql;
		$dues = $this->db->query($sql);
		
		return $dues;
		
		exit();
		$balance = $this->balance($loanID);
		$data = array();
		if($dues){
			if($dues->num_rows()){
				$data = array();
				$total = 0;
				$sched = array();
				$pdidue = 0;
				
				foreach ($dues->result() as $due){
					$pdi = round($this->getPDI($due->DueDate,$due->AmountDue, $balance),2);
					$pdidue += $pdi;
					
					/*$data[$due->loanscheduleID]['pdi'] = $pdi;
					$data[$due->loanscheduleID]['due'] = $due->AmountDue;
					$data[$due->loanscheduleID]['aging'] = $due->aging;*/
					$total += $due->AmountDue;			
					$PN = $due->PN;
				}
				$data['PN'] = $PN;
				$data['pdi'] = $pdidue;
				$data['total'] = $total;
			}else{
				
				
			}
			
			
			
			return $data;
		}
	}
	
	function updateLoan($loanID, $pid, $amount,  $term, $exterm, $remarks=null, $method, $computation){
		
		$fees = $this->Loansmodel->loanfees($pid,$amount, $term, $exterm);
		
		$net = $amount;
		$principal = $amount;
	
		foreach($fees['fees'] as $fee){
			
				if($fee['comp'] != 'fixed'){
					$data ="loanfees.feeID = '".$fee['feeID']."',".
									" loanfees.value = '".$fee['feevalue']."',".
									" loanfees.dateModified = '".$this->auth->localtime()."',".
									" loanfees.modifiedBy ='".$this->auth->user_id()."'";	
									
					$where = " productfees.fee_account_id = '".$fee['fee_account_id']."' and ".
									"loanfees.loanID ='".$loanID."'";
				
					$table = 'loanfees';
					
					$this->updateFees( $data, $where);
					
				}else{
					$data ="loanfees.feeID = '".$fee['feeID']."',".									
									" loanfees.dateModified = '" .$this->auth->localtime()."',".
									" loanfees.modifiedBy ='".$this->auth->user_id()."'";	
									
					$where = " productfees.fee_account_id = '".$fee['fee_account_id']."' and ".
									"loanfees.loanID ='".$loanID."'";
				
					$table = 'loanfees';
					
					$this->updateFees( $data, $where);
					
				}
				
		}		 
		
		$f =  $this->Loansmodel->getLoanFees($loanID);
		
			
		if($f->num_rows() > 0){
			foreach($f->result() as $fee) {
				$fe=  floatval(str_replace(",","",$fee->value));
				
				if($fee->upfront == 'deduct' or $fee->upfront != 'add'){
					$net -= $fe;						
				}else{
					$principal +=  $fe;					
				}
			}		
		}
		
		if($exterm != '')
			$schedTerm = $exterm;
		else
			$schedTerm = $term;
		
		if($method == 'M')
			$monthly = $principal/$schedTerm;
		else
			$monthly = $principal;
		
		$data = array( "AmountApplied"=>$amount,
								"LoanType"=>$pid,
								"principalAmount"=>$principal,
								"MonthlyInstallment"=>round($monthly,2),								
								"extension"=>$exterm,
								"interest"=>$fees['interest'],
								"remarks"=>$remarks,
								"computation"=>$computation,
								"netproceeds"=>$net,
								"Term"=>$term,
								"dateModified"=>$this->auth->localtime(),
								"ModifiedBy"=>$this->auth->user_id()
								);
		$where = array("loanid"=>$loanID);
		$this->Loansmodel->update_data("loanapplication", $where, $data);
		return true;		
	}
	
	// August 4, 2016
	function updateFees($data, $where){		
		if	($this->db->query('update loanfees join productfees on productfees.feeID = loanfees.feeID join fees on fees.id = productfees.fee_account_id SET '.$data." where ".$where))
		return true;
	}
	
	function getLoan($where, $select){
		//$this->db->select('loantypes.*, product.*,loanapplication.*, branches.branchname, branches.address as branchaddress, cities.name as city');
		$this->db->select($select);
		$this->db->join('clientinfo', 'clientinfo.clientID = loanapplication.clientID','left');	
		$this->db->join('loantypes', 'loantypes.loanTypeID = loanapplication.LoanType','left');	
		$this->db->join('product', 'product.productID = loantypes.productID');	
		$this->db->join('branches', 'branches.id = loanapplication.branchID','left');	
		$this->db->join('cities', 'cities.id = branches.city','left');	
		$this->db->where($where);
		$this->db->order_by("dateApplied");
		$this->db->from('loanapplication');
		return $loaninfo = $this->db->get();
	}
	
	function getDetails($where, $select){
		
		$sql = "select ".$select." from loanapplication 
			left join loantypes on loantypes.loanTypeID = loanapplication.LoanType 
			left join product on product.productID = loantypes.productID 		
			where ".$where;
		return $this->db->query($sql);
		
	}
	
	function getLoanbyPN($pn, $branchID){
		$sql = "select * from loanapplication where PN = '".$pn."' and branchID = '".$branchID."'";
		
		$res = $this->db->query($sql);
		//echo $this->db->last_query();
		if($res->num_rows() > 0){
			$r = $res->row();
			
			$loanID = $r->loanID;
			return $loanID;
		}else{
			
			return false;
		}
	}
	
	function updateGranted(){		
		$term = $_POST['term'];
		$approve = $_POST['approved'];
		$loanid = $_POST['loanid'];
		$method = $_POST['method'];
		$loancode=$_POST['loancode'];
		if($loancode == 'E'){
			$year = date("Y", strtotime($_POST['startpayment']."-1 month"));
			$m = date("m", strtotime($_POST['startpayment']."-1 month"));
			$d = date("d", strtotime($this->auth->localdate()));
			$date = $year."-".$m."-".$d;
			$startPay= date("Y-m-d",strtotime($date));
			$term = $_POST['exterm'];
		}else{
			$startPay = $this->auth->localdate();
			$term = $_POST['term'];
		}
		$matdate = $startPay."+".$term." month";
		$data = array("status"=>"granted",						
					"dateModified"=>$this->auth->localtime(),						
					"modifiedBy"=>$this->auth->user_id(),						
					"AmountDisbursed"=>$_POST['amount']);
							//"DateDisbursed"=>$this->auth->localtime(),
							//"DisbursedBy"=>$this->auth->user_id(),
							//"MaturityDate"=>date("Y-m-d", strtotime($matdate)),
							
		$id = array("loanID"=>$_POST['loanid']);
		$this->Loansmodel->update_data("loanapplication", $id, $data);						
		$this->loansetup->update_loanschedule($term, $approve, $startPay, $loanid, $method);
	}		
	function updateLoanwithPN(){
		$term = $_POST['term'];		
		$approve = $_POST['approved'];		
		$loanid = $_POST['loanid'];		
		$method = $_POST['method'];		
		$loancode=$_POST['loancode'];		
		if($loancode == 'E'){			
			$year = date("Y", strtotime($_POST['startpayment']."-1 month"));			
			$m = date("m", strtotime($_POST['startpayment']."-1 month"));			
			$d = date("d", strtotime($this->auth->localdate()));			
			$date = $year."-".$m."-".$d;			
			$startPay= date("Y-m-d",strtotime($date));			
			$term = $_POST['exterm'];		
		}else{			
			$startPay = $this->auth->localdate();			
			$term = $_POST['term'];		
		}		
		
		$matdate = $startPay."+".$term." month";		
		$data = array("PN"=>$_POST['bookpn'],						
				"DateDisbursed"=>$this->auth->localtime(),						
				"DisbursedBy"=>$this->auth->user_id(),						
				"MaturityDate"=>date("Y-m-d", strtotime($matdate))						
				);		
		
		$id = array("loanID"=>$_POST['loanid']);		
		$this->Loansmodel->update_data("loanapplication", $id, $data);								
		$this->loansetup->update_loanschedule($term, $approve, $startPay, $loanid, $method);	
	}
	
	function loancount($status){
		if($this->session->userdata("allbranch") == 1){
			$res = $this->db->select('sum(total) as total')
						->where("status",$status)
						->get('loancount')->row();
		}else{
			$res = $this->db->select('total as total')
						->where("status",$status)
						->where("branchID",$this->auth->branch_id())
						->get('loancount')->row();
		}
		
		return $res->total;		
		
	}
}
?>