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
	$amountfield = array("name"=>'loanapplied',
					"id"=>'loanapplied',
					"value"=>$loan->AmountApplied,
					"class"=>'input-sm form-control');
	$loanid = array('loanid'=>$loan->loanID,
					'clientid'=>$clientid,
					'loantype'=>$loan->loanTypeID);
	
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

?>
<form action="<?php echo base_url();?>loans/application/loandetails" method="post" class="formpost" id="loanamount">
<div class="panel-body">
	<div class="col-md-4">
    	<label>Select Loan : </label> &nbsp; &nbsp;
        <select name="loancode" class="form-control input-sm" id="loancode" <?php echo $disabled;?>>
         <?php 
         $count =1;
            foreach ($products->result() as $pro) {            
                
                if($pro->LoanCode == $loan->LoanCode)
                $select = 'selected';
                else
                $select = '';
                echo '<option value="'.$pro->LoanCode.'" '.$select.'>'.$pro->LoanName.'</option>';
            }
         ?>	
         </select>	
         <br/>
         <label>Payment Method</label>
        <select name="method" id="method" class="form-control input-sm" <?php echo $disabled;?>>                	
            <option value="M" >Monthly</option>
            <option value="L" >Lumpsum</option>
        </select>
        <br/>
        <label>Amount Applied * <code>[ $loan ]</code> : </label> 
        <div class="input-group">
            <span class="input-group-addon">Php</span>
            <?php echo form_input($amountfield);?>
            <!--<input type="text" name="loanapplied" id="loanapplied" value="<?php echo set_value("loanapplied", $loan->AmountApplied);?>" class="form-control input-sm" <?php echo $readonly;?> required>	            -->
        </div>
        <br/>
        <label>Terms * <code> [ $term ] </code>: </label>
        <select name="terms" id="terms" class="form-control input-sm"  <?php echo $disabled;?> required>
        <?php $count=1; 
          $maxterm = 24;
               
        while ($count <= $maxterm) {					
            
            ?> 
            <option value='<?php echo $count;?>' <?php echo set_select('terms', $count ); ?>><?php echo $count;?> &nbsp; month(s)</option>
        <?php $count++;}?>
        </select>
        <br/>
        <div id="pensioninput">
        <label>Monthly Pension</label>
         <div class="input-group">
         <span class="input-group-addon">Php</span>
        <input type="text" id="pensionamount" name="pensionamount" class="input-sm form-control">
        </div>
        </div>
    </div>
    <div class="col-md-8" id="feedetails">
    
    	
    </div>
</div>
<div class="panel-footer">		
    <input type="hidden" name="loanstatus" id="loanstatus" value="<?php echo $loanstatus;?>"> 
    <?php echo form_hidden($loanid); ?>
    <input type="submit" class="btn btn-primary btn-lg btn-block"   name="submitloan" value="Save Loan" <?php echo $disabled;?>>
</div>
</form>