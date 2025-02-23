
  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <div id="scroll" class="content" >
	<h2 class="content-subhead"><a href="<?php echo base_url();?>loans/setup">Loan Setup</a> > <a href="<?php echo base_url();?>loans/setup/fees">Loan Fees</a> > Create New Loan Fee</h2>
	<br/>
	<form class="pure-form" method="post" action="">
		 <fieldset>
		<h2 class="htitle">Enter Loan Fees</h2>
		<?php echo validation_errors();
		if(isset($errors)) echo "<font color='red'>".$errors."</font>"; 
		?>
		<?php $products = $this->Loansmodel->get_products('1');
			if($products->num_rows() > 0){	?><label>Loan Type</label>
			<div class="pure-group">
			<select name="loantype" class="pure-input-1-3"><?php foreach ($products->result() as $pro) { ?><option value="<?php echo $pro->loanTypeID;?>"><?php echo $pro->LoanCode;?> - <?php echo $pro->LoanName;?> </option>
			<?php } ?></select></div>
			<?php } ?>
		<fieldset class="pure-group">        	       
            <input id="firstname" name="feename" type="text" placeholder="Fee Name" class="pure-input-1-3"required>			
			<select name="computation" class="pure-input-1-3">
				<option value="fixed">Fixed</option>
				<option value="%">%</option>
			</select>
			<input id="value" name="value" type="text" placeholder="00.00" class="pure-input-1-3" required>			
        </fieldset>
		<input type="submit" class="button-success pure-button" name="submit" value="Add Loan Fee" > &nbsp; <a href="<?php echo base_url();?>loans/setup/products">Cancel</a>
		</fieldset>
	</form>
	
</div>
</div>
 <script>
 $("#value").keydown(function(e){
    var key = e.which;

    // backspace, tab, left arrow, up arrow, right arrow, down arrow, delete, numpad decimal pt, period, enter
    if (key != 8 && key != 9 && key != 37 && key != 38 && key != 39 && key != 40 && key != 46 && key != 110 && key != 190 && key != 13){
        if (key < 48){
            e.preventDefault();
        }
        else if (key > 57 && key < 96){
            e.preventDefault();
        }
        else if (key > 105) {
            e.preventDefault();
        }
    }
});
</script>