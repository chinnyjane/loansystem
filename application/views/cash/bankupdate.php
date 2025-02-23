<?php
if($branch->num_rows() >0){
	foreach($branch->result() as $br){
		$branchname = $br->branchname;
	}
	
	$bankdetails = $this->Cashmodel->getbankofbranch($bankAccount);
	if($bankdetails->num_rows() <= 0)
	{
		echo "Bank Account is not existing.";
	}else{
		$brbank = $bankdetails->row();
		echo validation_errors();
		if(isset($success)) echo '<div class="alert alert-success">'.$success.'</div>';
		$data = array("branchBankID"=>$brbank->branchBankID);
		$trans = $this->Loansmodel->get_data_from("bankstransactions", $data);
		if($trans->num_rows() > 0)
		$read =  "readonly";
		else $read = '';
?>

<form action="" method="post">
<div class="panel panel-info">
	<div class="panel-heading">
	Update Bank Account
	</div>
	<div class="panel-body">
		<div class="row form-group">
		<div class="col-md-4"><label>Branch</label>
			<input type="text" class="form-control input-sm" value="<?php echo $branchname;?>" readonly>
		</div>
		<div class="col-md-4"><label>Choose Bank</label>
		<?php if($bankslist->num_rows() > 0) { ?>
			<select name="bankID" class="form-control input-sm" required <?php echo $read;?>>
				<?php foreach ($bankslist->result() as $b){
					if( $brbank->bankCode == $b->bankCode) echo '<option value="'.$b->bankID.'">'.$b->bankCode.'</option>';				
				}?>
			</select>
			<?php } else { ?><a href="<?php base_url();?>cash/banks" class="btn btn-sm btn-default">Add Bank</a> <?php } ?>
		</div>
		<div class="col-md-4">
			<label>Branch Code</label>
			<input type="text" class="form-control input-sm" name="branchcode" placeholder="(optional)"  value="<?php echo $brbank->branchCode;?>" >
		</div>
		</div>
		<div class="row form-group">
		
		<div class="col-md-4">
		<label>Bank Branch</label>
				<input type="text" class="form-control input-sm" name="bankBranch" placeholder="Bank Branch" value="<?php echo $brbank->bankBranch;?>" required>		
		</div>
		<div class="col-md-4">
			<label>Bank Account</label>
			<input type="text" class="form-control input-sm" name="bankAccount" placeholder="Bank Account"  value="<?php echo $brbank->bankAccount;?>" required>
		</div>
		<div class="col-md-4">
			<label>Bank Address</label>
			<input type="text" class="form-control input-sm" name="bankAddress" placeholder="Bank Address"  value="<?php echo $brbank->bankAddress;?>" required>
		</div>
		
		</div>
		<div class="row form-group">
		<div class="col-md-6">
				<label>Beginning Balance</label>
				<input type="text" class="form-control input-sm" name="BeginningBal" placeholder="00.00"  value="<?php echo $brbank->BeginBalance;?>" required <?php echo $read;?>>
				</div>	
		<div class="col-md-6">
				<label>Beginning Date</label>
				<input type="text" class="form-control input-sm" name="BeginningDate" id="BeginDate" placeholder="yyyy-mm-dd"  value="<?php echo $brbank->BeginDate;?>" required <?php echo $read;?>>
				<?php if($read !=  "readonly") { ?>
				<script>
				  $(function() {
					$( "#BeginDate" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script>
			  <?php } ?>
				</div>
				</div>
			<input type="hidden" name="branchid" value="<?php echo $branchid;?>">
			<input type="hidden" name="branchBankID" value="<?php echo $bankAccount;?>">
       <input type="submit" class="btn btn-sm btn-primary " name="submit" value="Update Bank">  &nbsp;<a class="btn btn-warning btn-sm" href="<?php echo base_url();?>cash/branches/details/<?php echo $branchid;?>">Back to List of Banks</a>
	</div>
</div>   
</div>    
<?php }
} ?>		
      
     