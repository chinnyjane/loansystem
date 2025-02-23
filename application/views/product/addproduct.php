<?php $posturl = "product/overview/addproduct";
		$modalid = "addproduct";
		$formtitle = "Create New Product";
		//echo $this->form->modalformopennorm($modalid, $posturl, $formtitle);
		?>

<div class="modal-content">
	<div class="modal-header">
		Create New Product
	</div>
		<div class="modal-body">
		<form action="product/overview/addproduct"  method="post" class="jquerypost">
		<?php if(isset($errors)) echo "<font color='red'>".$errors.'</font>';
		echo validation_errors("<font color='red'>", '</font>');?>
			 <div class="form-group row">
				<div class="col-md-4"><label>Product Code</label>
					<input id="pcode" name="pcode" type="hidden" placeholder="Product Code" class="form-control input-sm" value="<?php echo $productID;?>" >
					<input id="pname" name="pnme" type="text" placeholder="Product" class="form-control input-sm" value="<?php echo $proname;?>" readonly>
				</div>
				<div class="col-md-4">
					<label>Loan Status</label>
					<select name="psubcode"  class="form-control input-sm">
						<option value="N" >NEW</option>
						<option value="E" >EXTENSION</option>                    
						<option value="A" >ADDITIONAL</option>
						<option value="R" >RENEWAL</option>                    
					</select>
				</div>
				<div class="col-md-4"><label>Payment Method</label>
					<select name="paymentterm"  class="form-control input-sm">					
						<option value="M" >Monthly</option>
						<option value="L" >Lumpsum</option>
					</select>
				</div>
				</div>
				<div class="form-group row">
				<div class="col-md-4"><label>Computation</label>
					<select name="computation"  class="form-control input-sm">					
						<option value="principal" >Principal Amount</option>
						<option value="net" >Net Amount</option>					
					</select>
				</div>
				<div class="col-md-4"><label>Product SubCode</label>
					<input id="pname" name="pname" type="text" placeholder="Product Name" value="<?php set_value('pname', $this->input->post('pname'));?>" class="form-control input-sm" required>
				</div>
				<div class="col-md-4"><label>Product Description</label>
					<input id="pdesc" name="pdesc" placeholder="Product Description" class="form-control input-sm" value="<?php set_value('pdesc', $this->input->post('pdesc'));?>"  required>	
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-md-6">
					<label>Min. Loan Amount</label>
					<div class="input-group">
						<span class="input-group-addon">Php</span>
						<input type="text" class="input-sm form-control" name="minAmount" value="<?php set_value('minAmount', $this->input->post('minAmount'));?>" >
					</div>
				</div>
				<div class="col-md-6">
					<label>Max. Loan Amount</label>
					<div class="input-group">
							<span class="input-group-addon">Php</span>
						<input type="text" class="input-sm form-control"  name="maxAmount" value="<?php set_value('maxAmount', $this->input->post('maxAmount'));?>">
						</div>
				</div>
				</div>
				<div class="form-group row">
				<div class="col-md-6">
					<label>Min. Loan Term</label>
					<div class="input-group">						
						<input type="text" class="input-sm form-control"  name="minTerm" value="<?php set_value('minTerm', $this->input->post('minTerm'));?>"><span class="input-group-addon">months</span>
					</div>
				</div>
				<div class="col-md-6">
					<label>Max. Loan Term</label>
					<div class="input-group">						
						<input type="text" class="input-sm form-control"  name="maxTerm" value="<?php set_value('maxTerm', $this->input->post('maxTerm'));?>"><span class="input-group-addon">months</span>
						</div>
				</div>
			</div>
		
		</form>
	<div class="modal-footer">
	<?php 
		$footer = '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>';
		$footer .= '<input type="submit" class="btn btn-primary" name="addproduct" value="Submit Product">';
		echo $footer; ?>
	</div>
	</div>
	</div>
	