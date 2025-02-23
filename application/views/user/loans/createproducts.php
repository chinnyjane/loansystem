
  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <div id="scroll" class="content" >
	<h2 class="content-subhead"><a href="<?php echo base_url();?>loans/setup">Loan Setup</a> > <a href="<?php echo base_url();?>loans/setup/products">Loan Products</a> > Create New Product</h2>
	<br/>
	<form class="pure-form" method="post">
	<?php if(isset($errors)) echo "<font color='red'>".$errors.'</font>';?>
		 <fieldset>
		<h2 class="htitle">Enter Product Details</h2>
		<fieldset class="pure-group">        	       
            <input id="pcode" name="pcode" type="text" placeholder="Product Code" class="pure-input-1-2"required>
			<input id="pname" name="pname" type="text" placeholder="Product Name" class="pure-input-1-2" required>
			<textarea id="pdesc" name="pdesc" placeholder="Product Description" class="pure-input-1-2" required></textarea>			
        </fieldset>
		<input type="submit" class="button-success pure-button" name="submit" value="Add Product" > &nbsp; <a href="<?php echo base_url();?>loans/setup/products">Cancel</a>
		</fieldset>
	</form>
	
</div>
</div>
 