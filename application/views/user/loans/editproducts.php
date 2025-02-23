  <?php
$product = $this->Loansmodel->getproductsbyID($pid);
if($product->num_rows() > 0){
	foreach ($product->result() as $pro){
		$pcode = $pro->LoanCode;
		$pname = $pro->LoanName;
		$pdesc = $pro->LoanDescription;
	}
}
  ?>
  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <div id="scroll" class="content" >
	<h2 class="content-subhead"><a href="<?php echo base_url();?>loans/setup" class="link">Loan Setup</a> > <a href="<?php echo base_url();?>loans/setup/products" class="link">Loan Products</a> > Create New Product</h2>
	<br/>
	<form class="pure-form" method="post">
	<?php if(isset($errors)) echo "<font color='red'>".$errors.'</font>';
	echo validation_errors("<font color='red'>", '</font>');?>
		 <fieldset>
		<h2 class="htitle">Enter Product Details</h2>
		<fieldset class="pure-group">        	       
            <input id="pcode" name="pcode" type="text" placeholder="Product Code" value="<?php echo $pcode;?>" class="pure-input-1-3" required>
			<input id="pname" name="pname" type="text" placeholder="Product Name" value="<?php echo $pname;?>" class="pure-input-1-3" required>
			<textarea id="pdesc" name="pdesc" placeholder="Product Description" class="pure-input-1-3" required><?php echo $pdesc;?></textarea>			
        </fieldset>
		<input type="hidden" name="pid" value="<?php echo $pid;?>" />
		<input type="submit" class="button-success pure-button" name="submit" value="Update Product" > &nbsp; <a href="<?php echo base_url();?>loans/setup/products"  class="link">Cancel</a>
		</fieldset>
	</form>
	
</div>
</div>
 