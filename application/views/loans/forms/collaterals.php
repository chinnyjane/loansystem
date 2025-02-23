<?php 
if(isset($loaninfo)){
	$loaninfo = $loaninfo->row();
	$status = strtolower($loaninfo->status);
	if($status =='processing' or $status =='ci' or $status == 'for approval'){
		$readonly=false;
		$disabled =false;
	}else{
		$readonly=true;
		$disabled = true;	
	}
	$loanstatus = $loaninfo->LoanSubCode;
	$loan = $loaninfo;
	$loantype = $loan->loanTypeID;
	$amountfield = array("name"=>'loanapplied',
					"id"=>'loanapplied',
					"value"=>$loan->AmountApplied,
					"class"=>'input-sm form-control');
	$loanid = array('loanid'=>$loan->loanID,
					'clientid'=>$clientid,
					'loantype'=>$loantype);
	//echo $loan->loanID;
	
	//echo $loantype;
}else{
	$readonly='';
	$disabled = '';
	$loanstatus = 'N';
	
	$amountfield = array("name"=>'loanapplied',
					"id"=>'loanapplied',
					"value"=>'',
					"class"=>'input-sm form-control');
	$loanid = array('loanid'=>'',
					'clientid'=>'');			
}
$products = $this->Loansmodel->get_productcodes();
$PL =  'style="display:none"';
$REM =  'style="display:none"';
$CM =   'style="display:none"';

if(isset($loans)){
	$loans = $loans->row();
	
	switch($loantype){
		case "PL":
			//$pension = $this->Loansmodel->get_pensioninfo($loans->pensionID);
			$PL =  '';
			$REM =  'style="display:none"';
			$CM =  'style="display:none"';
		break;
		case "REM":
			//$col = $this->Loansmodel->getCollateralByID($loans->pensionID);
			$PL =  'style="display:none"';
			$REM =  '';
			$CM =  'style="display:none"';
		break;
		case "CM":
			//$col = $this->Loansmodel->getCollateralByID($loans->pensionID);
			$PL =  'style="display:none"';
			$REM =  'style="display:none"';
			$CM =  '';
		break;
	}
}

if(isset($col)){
	echo "<pre>";	
	print_r($col->result());
	echo "</pre>";	
}else{	

?>
<form action="<?php echo base_url();?>loans/application/collaterals" method="post" name="collaterals" class="formpost">
<div id="COLForm">
	<?php 
	echo $pid;
	if(isset($loaninfo)){
		echo  $this->Loansmodel->getcollaterals($loantype,$loantype,$colID);
		//echo $colID;
	}?>
</div>
<div class="panel-body">
		
	<div id="PLForm" <?php echo $PL;?> >
	<h4>Pension Information</h4>
		<div class="row form-group">
				<div class="col-md-4">
                
					<label>Pension from</label>
					<select name="PL[pensiontype]" class="input-sm form-control">
						<option value="sss">SSS</option>
						<option value="gsis">GSIS</option>
						<option value="afp">AFP</option>
					</select>
				</div>
				<div class="col-md-4">
					<label>Type of Pension</label>
					<select name="PL[pensionstatus]" class="input-sm form-control">
						<option value="survivorship">Survivorship</option>
						<option value="retirement">Retirement</option>
						<option value="disability">Disability</option>
					</select>
				</div>
				<div class="col-md-4">					
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-4">
					<label>SSS/GSIS/AFP Account:</label>
					<input type="text" class="input-sm form-control" name="PL[sss]" placeholder="ex.123456789">
				</div>
				<div class="col-md-4">
					<label>Monthly Pension</label>
					<input type="text" class="input-sm form-control" name="PL[pension]" placeholder="00.00">
				</div>
				<div class="col-md-4">
					<label>Pension Receive Day</label>
					<input type="text" class="input-sm form-control" name="PL[date]" placeholder="ex. 10">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-4">
					<label>Bank:</label>
					<?php echo $this->form->bank("PL[bank]", ''); ?>
				</div>
				<div class="col-md-4">
					<label>Branch</label>
					<input type="text" class="input-sm form-control" name="PL[branch]" placeholder="bank branch">
				</div>
				<div class="col-md-4">
					<label>Account #</label>
					<input type="text" class="input-sm form-control" name="PL[accountnum]" placeholder="ex. 0123456789">
				</div>
		</div>
	</div>	
</div>

<div class="panel-footer">	
	<?php echo form_hidden($loanid); ?>
  	<input type="submit" class="btn btn-primary btn-block "   name="submitloan" value="Save Loan Collateral">    
 </div>
</form>
<?php } ?>