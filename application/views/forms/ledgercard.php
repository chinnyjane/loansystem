<?php
	$loans = $this->Loansmodel->getLoanDetails($loanid);
	
	$loaninfo = $loans['loaninfo']->row();
	$client = $loans['clientinfo']->row();
	$sched = $loans['schedule'];
	$spouse = $loans['spouseinfo'];
	$comaker = $loans['comaker'];
	$collaterals = $loans['collaterals'];

	if($loaninfo->productCode == 'PL'){
		$col = $collaterals->row();
		
	}else{
	}
	switch($loaninfo->PaymentTerm){
		case 'M':
			$pterm = "Monthly";
		break;
		case 'L':
			$pterm = "Lumpsum";
		break;
	}
	echo '<p align="center">'.strtoupper($loaninfo->branchname).' BRANCH <br/>'.strtoupper($loaninfo->branchaddress.", ".$loaninfo->city).'</p>';
	echo '<h4 align="center">'.$formtitle.'</h4>';
	
	if(!empty($loans['comaker'])){				
		if($loans['comaker']->num_rows() > 0){
			foreach($comaker->result() as $com){
				$clientinfo = $this->Clientmgmt->getclientinfoByID($com->clientID)->row();
				$cm[] = $clientinfo->LastName.", ".$clientinfo->firstName;
				$cmadd[] = $clientinfo->address.", ".$clientinfo->barangay.", ".$clientinfo->cityname.", ".$clientinfo->provname;
			}
		}
	}
	
	if($loaninfo->DateDisbursed != NULL){
		$dateGranted =  date("d-M-y", strtotime($loaninfo->DateDisbursed));
		$dateMatured =  date("d-M-y", strtotime($loaninfo->MaturityDate));
	}else{
		$dateGranted = '-';
		$dateMatured = '-';
	}
 
?>
<div class="productCode"><h2><?php echo substr(trim($client->LastName), 0, 1)."<br/><br/><br/>".$loaninfo->productCode;?></h2></div>
<?php if($loaninfo->productCode == 'PL') { ?>
<table class="table  table-border  " >
  <tr>
    <td colspan="2"><p>Name</p></td>
    <td colspan="3"><?php echo $client->LastName.", ".$client->firstName;?></td>
    <td colspan="2">PN No</td>
    <td colspan="3"><?php echo $loaninfo->PN;?></td>
  </tr>
  <tr>
    <td colspan="2">Address</td>
    <td colspan="3"><?php echo $client->address.", ".$client->barangay.', '.$client->cityname.', '.$client->provname;?></td>
    <td colspan="2">Co-maker 1</td>
    <td colspan="3"><?php if(isset($cm)) echo $cm[0];?></td>
  </tr>
  <tr>
    <td colspan="2">Birthdate</td>
    <td ><?php echo date("M d, Y",strtotime($client->dateOfBirth));?></td>
    <td >Age</td>
    <td ><?php echo $this->loansetup->get_age($client->dateOfBirth);?></td>
    <td colspan="2">Address</td>
    <td colspan="3"><?php if(isset($cmadd)) echo $cmadd[0];?></td>
  </tr>
  <tr>
    <td colspan="2">Tel. No</td>
    <td colspan="3"><?php echo $client->contact;?></td>
    <td colspan="2">Co-maker 2</td>
    <td colspan="3"><?php if(isset($cm[1])) echo $cm[1];?></td>
  </tr>
  <tr>
    <td colspan="2">Monthly Pension</td>
    <td colspan="3"><?php echo ($col->monthlyPension ? number_format($col->monthlyPension,2) : '');?></td>
    <td colspan="2">Address</td>
    <td colspan="3"><?php if(isset($cmadd[1])) echo $cmadd[1];?></td>
  </tr>
  <tr>
    <td colspan="2">Date of Remittance</td>
    <td colspan="3">&nbsp;</td>
    <td colspan="2">Principal</td>
    <td colspan="3"><?php echo number_format($loaninfo->approvedAmount,2);?></td>
  </tr>
  <tr>
    <td colspan="2">Schedule of Withdrawal</td>
    <td colspan="3"><?php echo $this->numbers->ordinal($col->pensionDate);?></td>
    <td colspan="2">Term</td>
    <td colspan="3"><?php echo $loaninfo->Term;?> months - <?php echo $loaninfo->extension." ext. - ".$pterm;?></td>
  </tr>
  <tr>
    <td colspan="2">Bank/SA #</td>
    <td colspan="3"><?php echo $col->bankCode.": ".$col->Bankaccount;?></td>
    <td colspan="2">Monthly Installment</td>
    <td colspan="3"><?php echo number_format($loaninfo->MonthlyInstallment,2);?></td>
  </tr>
  <tr>
    <td colspan="2">Bank Branch</td>
    <td colspan="3"><?php echo $col->bankBranch;?></td>
    <td colspan="2">Date Released</td>
    <td colspan="3"><?php echo $dateGranted;?></td>
  </tr>
  <tr>
    <td colspan="2">SSS #</td>
    <td colspan="3"><?php echo $col->PensionNum;?></td>
    <td colspan="2">Maturity Date</td>
    <td colspan="3"><?php echo $dateMatured;?></td>
  </tr>
    <tr>
    <td colspan="2">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td colspan="2">Nature of Loan</td>
    <td colspan="3"><?php echo strtoupper($col->PensionType);?></td>
  </tr>
  <tr>
  <?php
	$count = 1; ?>
    <td width="6%">#</td>
    <td width="12%">Due Date</td>
    <td width="12%">Amount</td>
    <td width="11%">Date Paid</td>
    <td width="11%">OR No</td>
    <td width="9%">PAYMENT</td>
    <td width="9%">PDI</td>
    <td width="12%">Principal</td>
    <td width="11%">Balance</td>
    <td width="7%">Remarks</td>
  </tr>
   <?php 
	$count ++;	 ?>
	<tr>
    <td colspan="10">Loans Granted: <?php echo $dateGranted;?> &nbsp; &nbsp; &nbsp; PN No. <?php echo $loaninfo->PN;?> &nbsp; &nbsp; &nbsp; Php<?php echo number_format($loaninfo->approvedAmount);?></td>
  </tr>
	<?php
 
  if($sched->num_rows() > 0){
	  $principal = $loaninfo->principalAmount;
	  $totalpaid = 0;
	  $startbal = $principal;
	foreach($sched->result() as $sch){ 
		$count ++;	
		
		if(!isset($bal)) $bal = $sch->LoanBalance;
		
		if($bal <= 0)
			$bal = $principal;
		
		if(!isset($basebal))
		$basebal = $bal;
	
		if($basebal < $sch->AmountDue){
			$amount = $basebal;			
		}else{
			$amount = $sch->AmountDue;			
		}
				
		if($sch->Paid > 0)
			$basebal -= $amount;
					
		$balance = $basebal;
	?>
		<tr>
		<td ><?php echo $this->numbers->ordinal($sch->order);?></td>
		<td><?php echo $sch->DueDate;?></td>
		<td><?php echo number_format($amount,2);?></td>
		<td><?php echo $sch->DatePaid;?></td>
		<td></td>
		<td><?php echo ($sch->Paid ? number_format($sch->Paid,2) : '');?></td>
		<td></td>
		<td></td>
		<td><?php echo ($sch->Paid  ? number_format($balance,2) : '');?></td>
		<td></td>
	  </tr>
	<?php 
	
	}
  }
  
  while ($count < 29){ 
  $count ++;
  ?>
	  <tr>
		<td >&nbsp;</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	  </tr>
  <?php }
  ?>
</table>
<?php } else{ ?>
<table class="table  table-border  " >
  <tr>
    <td colspan="2"><p>Name</p></td>
    <td colspan="3"><?php echo $client->LastName.", ".$client->firstName;?></td>
    <td colspan="2">PN No</td>
    <td colspan="3"><?php echo $loaninfo->PN;?></td>
  </tr>
  <tr>
    <td colspan="2">Address</td>
    <td colspan="3"><?php echo $client->address.", ".$client->barangay.', '.$client->cityname.', '.$client->provname;?></td>
    <td colspan="2">Co-maker </td>
    <td colspan="3"><?php if(isset($cm)) echo $cm[0];?></td>
  </tr>
  <tr>
    <td colspan="2">Tel. No</td>
    <td colspan="3"><?php echo $client->contact;?></td>
    <td colspan="2">Address</td>
    <td colspan="3"><?php if(isset($cmadd)) echo $cmadd[0];?></td>
  </tr>
  <tr>
    <td colspan="2">Date Released</td>
    <td colspan="3"><?php echo $dateGranted;?></td>
    <td colspan="2">Term</td>
    <td colspan="3"><?php echo $loaninfo->Term;?> months</td>
  </tr>
  <tr>
    <td colspan="2">Maturity Date</td>
    <td colspan="3"><?php echo $dateMatured;?></td>
    <td colspan="2">Collateral</td>
    <td colspan="3"><?php ?></td>
  </tr>
  <tr>
    <td colspan="2">Mode of Payment</td>
    <td colspan="3"><?php echo $pterm;?></td>
    <td colspan="2">Amount of Loan</td>
    <td colspan="3"><?php echo number_format($loaninfo->approvedAmount,2);?></td>
  </tr>
  <?php
	$count = 1; ?>
	<tr>
    <td width="6%">#</td>
    <td width="12%">Due Date</td>
    <td width="12%">Amount</td>
    <td width="11%">Date Paid</td>
    <td width="11%">OR No</td> 7
    <td width="9%">PAYMENT</td>
    <td width="9%">PDI</td>
    <td width="12%">Principal</td>
    <td width="11%">Balance</td> 
    <td width="7%">Remarks</td>
  </tr>
  <?php 
	$count ++;	 ?>
	<tr>
    <td colspan="10">Loans Granted: <?php echo $dateGranted;?> &nbsp; &nbsp; &nbsp; PN No. <?php echo $loaninfo->PN;?> &nbsp; &nbsp; &nbsp; Php <?php echo number_format($loaninfo->approvedAmount);?></td>
  </tr>
	<?php
  if($sched->num_rows() > 0){
	  $principal = $loaninfo->principalAmount;
	  $totalpaid = 0;
	  $startbal = $principal;
	foreach($sched->result() as $sch){ 
		$count ++;			
		if(!isset($bal)) $bal = $sch->LoanBalance;
		
		if($bal <= 0)
			$bal = $principal;
		
		if(!isset($basebal))
		$basebal = $bal;
	
		if($basebal < $sch->AmountDue){
			$amount = $basebal;			
		}else{
			$amount = $sch->AmountDue;			
		}
		if($sch->Paid > 0)
			$basebal -= $amount;
					
		$balance = $basebal;
	?>
		<tr>
		<td ><?php echo $this->numbers->ordinal($sch->order);?></td>
		<td><?php echo $sch->DueDate;?></td>
		<td><?php echo number_format($amount,2);?></td>
		<td><?php echo $sch->DatePaid;?></td>
		<td></td>
		<td><?php echo ($sch->Paid ? number_format($sch->Paid,2) : '');?></td>
		<td></td>
		<td></td>
		<td><?php echo ($sch->Paid  ? number_format($balance,2) : '');?></td>
		<td></td>
	  </tr>
	<?php 
	
	}
  }
  
  while ($count < 29){ 
  $count ++;
  ?>
	  <tr>
		<td >&nbsp;</td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	  </tr>
  <?php }
  ?>
</table>
<?php } ?>