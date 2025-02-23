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
    <div class="col-md-9">
    	
    </div>
</div>
<div class="panel-footer">		
    <input type="hidden" name="loanstatus" id="loanstatus" value="<?php echo $loanstatus;?>">            
    <input type="submit" class="btn btn-primary btn-lg btn-block"   name="submitloan" value="Save Loan" <?php echo $disabled;?>>
</div>
</form>