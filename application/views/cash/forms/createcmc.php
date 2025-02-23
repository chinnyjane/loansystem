<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<div class="panel panel-primary"><div class="panel-heading"><b>CREATE NEW CMC TRANSACTION</b></div>
<?php echo validation_errors();?>
<form action="" method="post">
<div class="panel-body">
	<div class="row form-group">
		<div class="col-md-1"></div>
		<div class="col-md-3"><label>Choose Date of Transaction:</label><input type="date" id="date" name="date" placeholder="yyyy-mm-dd" class="form-control input">
		</div>
		<script>
				  $(function() {
					$( "#date" ).datepicker({format: 'yyyy-mm-dd',
					changeMonth: true,
					changeYear: true
					});
				  });
			  </script>
		<div class="col-md-2"><label>&nbsp;</label>
			<input type="submit" name="submit" value="Create Transaction" class="btn btn-primary ">
		</div>
	</div>
</div>
</form>
</div>