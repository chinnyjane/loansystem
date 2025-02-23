 <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <div id="scroll" class="content" >
<h2 class="content-subhead"><a href="<?php echo base_url();?>loans/setup" class="link">Loan Setup</a> > Loan Fees</h2>
<br/>
<a href="<?php echo base_url();?>loans/setup/fees/create" class="pure-button button-secondary">Create New Fee</a><br/><br/>
<div class="scroll">
<?php $fees = $this->Loansmodel->get_loanfees();
	if($fees->num_rows() > 0){
	$count = 1;
	//$er['postdetails'] = $fees;
	//$this->load->view('template/postdetails',$er);?>
	<form method="post" action="">
	<table class="pure-table">
		<thead>
			<tr>
				<td class="column-check"><input class="check-all" type="checkbox" /></td>
				<td>Loan Product</td>
				<td>Loan Fee</td>
				<td>Computation Type</td>
				<td>Value</td>
			</tr>
		<thead>
		<tbody>
		<?php foreach($fees->result() as $f) { ?>
			<tr>
				<td><input type="checkbox" name="checked[]"  class="case" value="<?php echo $f->feeID; ?>" /></td>
				<td><?php echo $f->loancode; ?></td>
				<td><?php echo $f->feeName; ?></td>
				<td><?php echo $f->comptype; ?></td>
				<td><?php echo $f->value; ?></td>
			</tr><?php } ?>
		</tbody>
	</table>
	<div style="margin:10px"><input type="submit" name="submit" value="Delete Fee(s)" class="pure-button button-error"></div>
	</form>
<?php } ?>
</div>	
</div>
</div>
 