
  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <div id="scroll" class="content" >
	<h2 class="content-subhead"><a href="<?php echo base_url();?>loans/setup" class="link">Loan Setup</a> > Loan Products</h2>
	<br/>
	<a href="<?php echo base_url();?>loans/setup/products/create" class="pure-button button-secondary link">Create New Loan Product</a><br/><br/>
	<?php $products = $this->Loansmodel->get_products('all');
	if($products->num_rows() > 0)
	{
		$count = 1;
		//$er['postdetails'] = $products;
		//$this->load->view('template/postdetails',$er);	
	?>	
		<div class="scroll">	
			<form method="post" class="pure-form">
			<table class="pure-table">
				<thead>
					<tr>
					<td class="column-check"><input class="check-all" type="checkbox" /></td>
					<td>#</td>
					<td>Product Code</td>
					<td>Product Name</td>
					<td>Product Description</td>
					<td>Status</td>
					</tr>
				<thead>
				<tbody>
					<?php foreach ($products->result() as $pro) { ?>
					<tr>
						<td><input type="checkbox" name="checked[]"  class="case" value="<?php echo $pro->loanTypeID; ?>" /></td>
						<td><?php echo $count;?></td>
						<td><?php echo $pro->LoanCode;?></td>
						<td><a href="<?php echo base_url();?>loans/setup/products/details/<?php echo $pro->loanTypeID; ?>" class="link"><?php echo $pro->LoanName;?></a></td>
						<td><?php echo $pro->LoanDescription;?></td>
						<td><?php if($pro->active == 1) echo "active"; else echo "Inactive";?></td>
					</tr>
					<?php $count++;} ?>
				</tbody>
			</table>
			<div style="margin: 10px;"><input type="submit" class="pure-button button-success" name="submit" value="Activate">&nbsp;<input type="submit" class="pure-button button-error" name="submit" value="Deactivate"></div>
			</form>
		</div>
	<?php } else { echo "No Loan Products was added yet"; } ?>
</div>
</div>
 