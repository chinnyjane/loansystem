<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<div id="scroll" class="content" >
	<h2 class="content-subhead"><a href="<?php echo base_url();?>loans/setup">Loan Setup</a> > <a href="<?php echo base_url();?>loans/setup/computation">Computation Methods</a> > Create New Method</h2>
	<br/>
<?php if(isset($errors)) echo "<font color='red'>".$errors."</font>";
	echo validation_errors("<font color='red'>","</font>"); ?>
	<form class="pure-form" method="post" action="">
		 <fieldset>		
		<fieldset class="pure-group">        	       
            <input id="compname" name="Computation" type="text" placeholder="Computation Name" class="pure-input-1-3"required>			
			<input id="method" name="value" type="text" placeholder="example: A =X + Y * z" class="pure-input-1-3" required>			
        </fieldset>
		<input type="submit" class="button-success pure-button" name="submit" value="Add Method" > &nbsp; <a href="<?php echo base_url();?>loans/setup/computation">Cancel</a>
		</fieldset>
	</form>	
</div>
</div>
 