<?php 
	$loaninfo= $loaninfo->row();
	$client = $clientinfo->row();
	
	if (isset($collaterals) and $collaterals !=''){
		if($collaterals->num_rows() > 0){
		$col = $collaterals->row();
		if($loaninfo->productCode == 'PL')
		$pension = $col->monthlyPension;
		}else $pension = 0;
	}else{
		$pension = 0;
	}
	$pl = $this->Loans->CountTerms($loaninfo->pensionID);
	$usedTerm =  $pl->num_rows();
	
	if($this->auth->perms('debug',$this->auth->user_id(),3) == true) { 
		//echo "<pre>";
		//print_r($pl->result());
		//echo "</pre>";
		//echo $usedTerm;
	}
	
	
	if($loaninfo->MaturityDate >  $this->auth->localdate() && $usedTerm < 30 && $loaninfo->productCode == "PL" && ($loaninfo->status == 'granted' or strtoupper($loaninfo->status) == 'CURRENT')){
		$term = 36 - $usedTerm;
		$monthly = ($loaninfo->MonthlyInstallment ? $loaninfo->MonthlyInstallment : ($loaninfo->extension ? ($loaninfo->approvedAmount/$loaninfo->extension) : ($loaninfo->approvedAmount/$loaninfo->Term)));
?>

<div class="panel panel-danger">
	<div class="panel-heading">
		<h4>Apply for Loan Extension</h4>
	</div>
	<div class="panel-body">
		<form action="<?php echo base_url();?>loans/overview/extendloan" method="post">			
			<input type="hidden" name="term" value="<?php echo $usedTerm;?>">
			<input type="hidden" name="lastDate" value="<?php echo $LastDate;?>">
			<input type="hidden" name="loanid" value="<?php echo $loanid;?>">
			<input type="hidden" name="method" value="<?php echo $loaninfo->PaymentTerm;?>">
			<input type="hidden" name="loantype" value="<?php echo $loaninfo->LoanType;?>">
			<input type="hidden" name="clientid" value="<?php echo $clientid;?>">
			<input type="hidden" name="monthly" value="<?php echo $monthly;?>">
			<input type="hidden" name="maxTermAv" value="<?php echo $term;?>">
			<input type="hidden" name="monthlyPension" value="<?php echo $pension;?>">
			<input type="hidden" name="branchID" value="<?php echo $loaninfo->branchID;?>">
			<input type="hidden" name="product" value="<?php echo $loaninfo->productID.".".$loaninfo->LoanCode;?>">
			<input type="hidden" name="productname" value="<?php echo $loaninfo->productCode."-".$loaninfo->LoanCode;?>">
			<input type="hidden" name="clientname" value="<?php echo strtoupper($client->LastName.", ".$client->firstName." ".$client->MiddleName); ?>">
			
			&nbsp; &nbsp; <input type="hidden" name="pensionID" value="<?php echo $loaninfo->pensionID;?>" class="input input-sm ">
			&nbsp; &nbsp; <input type="submit" name="submit" value="Extend Loan" class="btn btn-sm btn-danger ">
		</form>
	</div>
</div>
	<?php }else if( $loaninfo->status == 'granted' or strtoupper($loaninfo->status) == 'CURRENT'){
	//echo "<code>This Loan cannot be extended. If Loan Info is not correct, please contact Administrator.</code>";
	}	?>