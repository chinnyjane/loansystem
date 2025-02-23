<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<?php $clientdata = $this->Loansmodel->get_clientbyid($client);
if($clientdata->num_rows() > 0){
	foreach($clientdata->result() as $c){
		$name = $c->LastName.", ".$c->firstName;
	}
}else $name = "wala";
if($_POST){
	if(isset($errors)){
	echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$errors."</div>"; 
	$disable="disabled";
	}else{
	$disable="";
	}
}
 ?>
<form class="form-horizontal" method="post" action="" >
<div class="panel panel-success"><div class="panel-heading">Loan Information</div>
	<div class="panel-body">
	<div class="row form-group"><div class="col-md-4"><label><?php echo $name;?></label> </div></div>			
	<div class="row form-group">
		<div class="col-md-4"><label>Amount Applied *</label><div class="input-group">
		<span class="input-group-addon">Php</span>
		<input type="text" name="loanapplied" value="<?php echo set_value("loanapplied");?>" class="form-control input-sm" required>		  
		</div></div>
		<div class="col-md-3"><label>Terms *</label><select name="terms" class="form-control input-sm"><?php $count=1; while ($count <= 24) {?> <option value='<?php echo $count;?>' <?php echo set_select('terms', $count ); ?>><?php echo $count;?></option><?php $count++;}?></select></div>
		<div class="col-md-4"><label>Monthly Pension</label><div class="input-group">
		<span class="input-group-addon">Php</span>
		<input type="text" name="pension" value="<?php echo number_format($this->loansetup->monthlypension(),2);?>" class="form-control input-sm" readonly>	<input type="hidden" name="submit" value="compute">	  
		</div>
		</div>			
		</div>
	</div>	
</div>
<div class="row form-group"><div class="col-md-4"><input type="submit" name="submit" value="Compute Net Proceeds" class="btn btn-success"></div></div>
<div class="col-md-6">
<div class="panel panel-success"><div class="panel-heading">Loan Fees</div>
<div class="panel-body">	
	<div class="row form-group">
		<div class="col-md-5"><label for="interest">Interest</label></div>
		<div class="col-md-7"><input type="text" name="interest" placeholder="0.00" value="<?php if(isset($int)) echo number_format($int, 2); ?>"  class="form-control input-sm" readonly></div>
	</div>
	<div class="row form-group">
		<div class="col-md-5"><label for="servicefee">Service Fee</label></div>
		<div class="col-md-7"><input type="text" name="servicefee" placeholder="0.00" value="<?php if(isset($servicefee)) echo $servicefee;?>"  class="form-control input-sm" readonly></div>
	</div>
	<div class="row form-group">
		<div class="col-md-5"><label for="rfpl">RFPL Net</label></div>
		<div class="col-md-7"><input type="text" name="rfpl" placeholder="0.00" value="<?php if(isset($rfpl)) echo $rfpl;?>" class="form-control input-sm" readonly></div>
	</div>
	<div class="row form-group">
		<div class="col-md-5"><label for="atm">ATM Charges</label></div>
		<div class="col-md-7"><input type="text" name="atm" placeholder="0.00"  value="<?php if(isset($atm)) echo $atm;?>" class="form-control input-sm" readonly></div>
	</div>
	<div class="row form-group">
		<div class="col-md-5"><label for="atm">Notarial Fee</label></div>
		<div class="col-md-7"><input type="text" name="notarial" placeholder="0.00" value="<?php if(isset($notarial)) echo $notarial;?>" class="form-control input-sm" readonly></div>
	</div>
	<div class="row form-group">
		<div class="col-md-5"><label for="atm" class="htitle">TOTAL FEES</label></div>
		<div class="col-md-7"><input type="text" name="totalcharges" placeholder="0.00" value="<?php if(isset($totalcharges)) echo $totalcharges;?>"  class="form-control input-sm" readonly></div>
	</div></div>
</div>
</div>
<div class="col-md-6">
<div class="panel panel-info"><div class="panel-heading">Loan Summary</div>
<div class="panel-body">
	<div class="row form-group">
		<div class="col-md-5"><label for="interest">Monthly Installment</label></div>
		<div class="col-md-7"><input type="text" name="monthly" placeholder="0.00" value="<?php if(isset($monthly)) echo $monthly; ?>"  class="form-control input-sm" readonly></div>
	</div>
	<div class="row form-group">
		<div class="col-md-5"><label for="interest">Excess</label></div>
		<div class="col-md-7"><input type="text" name="excess" placeholder="0.00" value="<?php if(isset($excess)) echo $excess; ?>"  class="form-control input-sm" readonly></div>
	</div>
	<div class="row form-group">
		<div class="col-md-5"><label class="htitle" style="color: red" for="net"> Net Proceeds </label></div>
		<div class="col-md-7"><input type="text" name="net" placeholder="00.00"  style="color: red;" value="<?php if(isset($net)) echo $net;?>" class="form-control input-sm" readonly></div>
		</div>
	</div>
</div>
</div>
<div style="clear:both"></div>
		<div style="margin: 20px; " >
		<input type="submit" class="btn btn-primary" name="submit" value="Submit Loan Info" <?php echo $disable;?>>		
		</div>
</form>


 