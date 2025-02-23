<form  method="post" id="calculator" action="">
<div class="panel panel-primary">
	<div class="panel-heading">
    Loan Calculator
    </div>
    <div class="panel-body">    
	<?php $products = $this->Loansmodel->get_productcodes();?>
    <div class="col-md-4">
    <label>Select Product</label>
        <select name="loancode" class="form-control input-sm" id="loancode" <?php echo $disabled;?>>
			<?php 
            $count =1;
            foreach ($products->result() as $pro) {
                                        
                if($pro->LoanCode == $loan->LoanCode)
                $select = 'selected';
                else
                $select = '';
                echo '<option value="'.$pro->LoanCode.'" '.$select.'>'.$pro->LoanName.'</option>';
            } ?>	
        </select>	
	
	<label> Monthly Pension </label>
		<div class="input-group">
		  <span class="input-group-addon">Php</span>
		  <input type="text" name="pension" value="<?php echo set_value("pension");?>" class="form-control" required>		  
			</div>
	<label> Amount Applied </label>
	<div class="input-group">
		  <span class="input-group-addon">Php</span>
			<input type="text" name="loanapplied"  value="<?php echo set_value("loanapplied");?>" class="form-control" required>			
		</div>
		<label> Terms of Payment </label>
		<div class="input-group">			
			<input type="text" name="terms" placeholder="Terms upto 24 months"  value="<?php echo set_value("terms");?>" class="form-control">	
            <span class="input-group-addon">months</span>
			 		
		</div>		
        <br/>
		<div class="input-group">
        <button type="submit" class="btn btn-warning" id="compute">Calculate</button>
        </div>
    </div>
    <div class="col-md-8">
    <?php if(isset($errors)) echo "<font color=red>".$errors."</font>";
		echo validation_errors("<font color=red>","</font>"); ?>
        <div class="form-group row">
        	<div class="col-md-6">
             <label for="interest">Monthly Installment</label>
              <div class="input-group">
              <span class="input-group-addon">Php</span>
              <input type="text" name="monthly" placeholder="0.00" value="<?php if(isset($monthly)) echo $monthly; ?>"  class="form-control input" readonly>
              </div>
            </div>
            <div class="col-md-6">
            	 <label for="interest">Excess</label>
                 <div class="input-group">
                    <span class="input-group-addon">Php</span>
                    <input type="text" name="excess" placeholder="0.00" value="<?php if(isset($excess)) echo $excess; ?>"  class="form-control input" readonly>
                </div>
            </div>
        </div>
      <p>
    
         
		<h2 class="htitle"> Loan Fees </h2>
		<div  class="pure-control-group">
			<label for="interest">Interest</label>
			<input type="text" name="interest" placeholder="0.00" value="<?php if(isset($int)) echo number_format($int, 2); ?>"  class="pure-input-1-4" readonly>
		</div>
		<div  class="pure-control-group">
			<label for="servicefee">Service Fee</label>
			<input type="text" name="servicefee" placeholder="0.00" value="<?php if(isset($servicefee)) echo $servicefee;?>"  class="pure-input-1-4" readonly>
		</div>
		<div  class="pure-control-group">
			<label for="rfpl">RFPL Net</label>
			<input type="text" name="rfpl" placeholder="0.00" value="<?php if(isset($rfpl)) echo $rfpl;?>" class="pure-input-1-4" readonly>
		</div>
		<div  class="pure-control-group">
			<label for="atm">ATM Charges</label>
			<input type="text" name="atm" placeholder="0.00"  value="<?php if(isset($atm)) echo $atm;?>" class="pure-input-1-4" readonly>
		</div>
		<div  class="pure-control-group">
			<label for="atm">Notarial Fee</label>
			<input type="text" name="notarial" placeholder="0.00" value="<?php if(isset($notarial)) echo $notarial;?>" class="pure-input-1-4" readonly>
		</div>
		
		<div  class="pure-control-group">
			<label for="atm" class="htitle">TOTAL FEES</label>
			<input type="text" name="totalcharges" placeholder="0.00" value="<?php if(isset($totalcharges)) echo $totalcharges;?>"  class="pure-input-1-4" readonly>
		</div>
		
		<br/>
		<div  class="pure-control-group">
		<label class="htitle" style="color: red" for="net"> Net Proceeds </label>
		<input type="text" name="net" placeholder="00.00"  style="color: red;" value="<?php if(isset($net)) echo $net;?>" class="pure-input-1-4" readonly>
		</div>			
        </p>	
    </div>
	</div>
</div>
</form>