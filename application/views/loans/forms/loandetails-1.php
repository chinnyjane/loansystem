<?php 
echo $this->session->userdata('applicant_id'); 
$products = $this->Loansmodel->get_productcodes();
$loanstatus = 'N';

if($loanid != ''){
	$loans = $this->Loansmodel->getLoanbyID($loanid);			
	$loan = $loans->row();
	$readonly='readonly';
	$disabled = 'disabled';
}else{
	$readonly='';
	$disabled = '';
}

?>
<form action="<?php echo base_url();?>loans/application/loandetails" method="post" class="formpost" id="loanamount">
<div class="panel-body">
	<div class="row form-group">
		<div class="col-md-3">
				<label>Select Loan : </label> &nbsp; &nbsp;
                <select name="loancode" class="form-control input-sm" id="loancode" <?php echo $disabled;?>>
				 <?php 
				 $count =1;
					foreach ($products->result() as $pro) {
						//echo '<label class="radio-inline">';
						//echo '<input type="radio" name="loantype" id="loantype'.$count.'" value="'.$pro->loanTypeID.'">'.$pro->LoanName;
						//echo '</label>';
						
						if($pro->LoanCode == $loan->LoanCode)
						$select = 'selected';
						else
						$select = '';
						echo '<option value="'.$pro->LoanCode.'" '.$select.'>'.$pro->LoanName.'</option>';
					}
				 ?>	
                 </select>		
			</div>
            <div class="col-md-3">
				<label>Payment Method</label>
				<select name="method" id="method" class="form-control input-sm" <?php echo $disabled;?>>                	
					<option value="M" >Monthly</option>
					<option value="L" >Lumpsum</option>
				</select>
			</div>		
			<div class="col-md-3">
				<label>Amount Applied * <code>[ $loan ]</code> : </label> 
				<div class="input-group">
					<span class="input-group-addon">Php</span>
					<input type="text" name="loanapplied" id="loanapplied" value="<?php echo set_value("loanapplied");?>" class="form-control input-sm" <?php echo $readonly;?> required>	  
					
				</div>
			</div>	

			<div class="col-md-3">
				<label>Terms * <code> [ $term ] </code>: </label>
					<select name="terms" id="terms" class="form-control input-sm"  <?php echo $disabled;?> required>
					<?php $count=1; 
					//if ( $age < 70)
					$maxterm = 24;
					//else
					//$maxterm = 18;				
					
					while ($count <= $maxterm) {					
						
						?> 
						<option value='<?php echo $count;?>' <?php echo set_select('terms', $count ); ?>><?php echo $count;?> &nbsp; month(s)</option>
					<?php $count++;}?>
					</select>
			</div>
				
			
		</div>
	
<div id="feedetails" class="panel panel-body col-md-6 col-md-offset-3">

</div>			
	
</div>
<?php // $this->load->view('loans/forms/collaterals');?>
<div class="panel-footer">		
    <input type="hidden" name="loanstatus" id="loanstatus" value="<?php echo $loanstatus;?>">            
    <input type="submit" class="btn btn-primary btn-lg btn-block"   name="submitloan" value="Save Loan" <?php echo $disabled;?>>
</div>
</form>