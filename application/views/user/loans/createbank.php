
  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <div id="scroll" class="content" >
	<h2 class="content-subhead"><a href="<?php echo base_url();?>loans/setup">Loan Setup</a> > <a href="<?php echo base_url();?>loans/setup/banks">Banks</a> > Add New Bank</h2>
	<br/>
	<?php echo validation_errors('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>',"</div>"); 
	if(isset($success)) echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$success.'</div>';
	?>
	<form class="form-horizontal" method="post" action="">
		<div class="panel panel-success"><div class="panel-heading">Add New Bank</div>
		<div class="panel-body">		
		<div class="row form-group">
		<div class="col-md-4"><label>Bank Name</label><input id="bank" name="bank" type="text" placeholder="Bank Name" class="form-control input-sm" required></div>			
		<div class="col-md-4"><label>Bank Code</label><input id="bankcode" name="bankcode" type="text" placeholder="Bank Code ex. BDO, BPI, RCBC" class="form-control input-sm" required></div>
		</div>
		<input type="submit" class="btn btn-success" name="submit" value="Add Bank" > &nbsp; <a href="<?php echo base_url();?>loans/setup/banks">Cancel</a>
		</div>
		</div>
	</form>
	
</div>
</div>
 