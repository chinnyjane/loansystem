<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<?php $clientdata = $this->Loansmodel->get_clientbyid($client);
if($clientdata->num_rows() > 0){
	foreach($clientdata->result() as $c){
		$name = $c->LastName.", ".$c->firstName;
	}
}
if($_POST){
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
}
echo validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>',"</div>"); 
 ?>
 <form method="post" action=""> 
<div class="panel panel-info"><div class="panel-heading">Pension Information</div>
	<div class="panel-body">
		<div class="row form-group"><div class="col-md-4"><label><?php echo $name;?></label></div></div>
		<div class="row form-group">
			<div class="col-md-4"><label>Pension by: </label><label class="checkbox-inline">
			  <input type="radio" id="pensiontype" name="pensiontype" value="sss"> SSS
			</label>
			<label class="checkbox-inline">
			  <input type="radio" id="pensiontype" name="pensiontype" value="gsis"> GSIS
			</label>
			</div>
			<div class="col-md-6"><label>Pension Type</label> <label class="checkbox-inline">
			  <input type="radio" id="pensionstatus" name="pensionstatus" value="retirement"> Retirement
			</label>
			<label class="checkbox-inline">
			  <input type="radio" id="pensionstatus" name="pensionstatus" value="survivorship"> Survivorship
			</label>
			<label class="checkbox-inline">
			  <input type="radio" id="pensionstatus" name="pensionstatus" value="disability"> Disability
			</label></div> </div>
		<div class="row form-group">
			<div class="col-md-4"><label>SSS/GSIS number</label><input type="text" class="form-control input-sm" value="<?php echo set_value("sss");?>" placeholder="SSS/GSIS number" name="sss" required> </div>
			<div class="col-md-4"><label>Monthly Pension</label>
			<div class="input-group">
			<span class="input-group-addon">Php</span>
			<input type="text" name="pension" value="<?php echo set_value("pension");?>" placeholder="00.00" class="form-control input-sm" required>		  
			</div></div>
			<div class="col-md-4"><label>Date of Pension</label><input type="text" id="sssdate"  class="form-control input-sm" value="<?php echo set_value("sssdate");?>"  placeholder="mm-dd-yyyy" name="sssdate" required>
            <script>
				  $(function() {
					$( "#sssdate" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>	
			<div class="row form-group">	
			<div class="col-md-4"><label>Depository Bank</label>
			<?php $banks = $this->Loansmodel->get_data_from('banks', '');
				if($banks->num_rows() > 0){ ?>
				<select name="bank" class="form-control input-sm"><?php foreach ($banks->result() as $b){?>
				<option value="<?php echo $b->bankID;?>"><?php echo $b->bankCode;?></option>
				<?php } ?></select>
				<?php }?></div>
			<div class="col-md-4"><label>Branch</label><input type="text" class="form-control input-sm" placeholder="Branch" value="<?php echo set_value("branch");?>" name="branch" required></div>
			<div class="col-md-4"><label>Bank Account Number</label><input type="text" class="form-control input-sm" placeholder="Bank Account Number" value="<?php echo set_value("accountnum");?>" name="accountnum" required></div>
			</div>			
		</div>
		</div>
		<!--
		<div class="panel panel-info"><div class="panel-heading">Dependents</div>
		<div class="panel-body">
		<div class="row form-group">	
			<div class="col-md-4"><label>Name of Dependents</label></div>
			<div class="col-md-4"><label>Date of Birth</label></div>
			
			</div>
		<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday1"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday1" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
				<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday2"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday2" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
			<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday3"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday3" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
			<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday4"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday4" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
			<div class="row form-group">	
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="Name" name="dpname[]" > </div>
			<div class="col-md-4"><input type="text" class="form-control input-sm" placeholder="mm-dd-yyyy" id="dpbday5"  name="dpbday[]" > <script>
				  $(function() {
					$( "#dpbday5" ).datepicker({format: 'mm-dd-yyyy',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script></div>
			</div>
			</div>
		</div> -->
	<div style="clear:both"></div>
		<div style="margin: 20px; " >
		<input type="hidden" name="submit" value="pensioninfo">
		<a class="btn btn-warning" href="<?php echo base_url();?>loans/application/newloan">Back</a> &nbsp;<button type="submit" class="btn btn-primary">Submit Pension Info</button>
		</div>		
	</form>	