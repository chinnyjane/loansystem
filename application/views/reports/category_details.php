<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<p>Account Details</p>
<?php
if($coa_details->num_rows() >0){
	
	foreach($coa_details->result() as $coad): ?>
	<form action="<?php echo base_url();?>reports/accounts/updategroup" method="post" class="jquerypost">	
		<div class="modal-body">
				<div class="row form-group">
					<div class="col-md-3">
						<label>ACode Prefix</label>
						<input type="text" class="input-sm form-control" name="coa_codeprefix" placeholder="A" value="<?php echo $coad->coa_codeprefix;?>">
					</div>
					<div class="col-md-3">
						<label>Account Code</label>
						<input type="text" class="input-sm form-control" name="coa_code" value="<?php echo $coad->coa_code;?>">
					</div>
					<div class="col-md-6">
						<label>Account Title</label>
						<input type="text" class="input-sm form-control" name="coa_name" value="<?php echo $coad->coa_name;?>">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-9">
						<label>Choose Account Group</label>
						<select class="input-sm form-control" name="coa_parent">
							<?php foreach($coagroup->result() as $coa_g): ?>
								<option value="<?php echo $coa_g->coa_id;?>" <?php if($coa_g->coa_id == $coad->coa_parent) echo 'selected';?>><?php echo $coa_g->coa_codeprefix.$coa_g->coa_code;?> - <?php echo $coa_g->coa_name;?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label>Account Order</label>
						<input type="number" name="coa_order" class="form-control" value="<?php echo $coad->coa_order;?>">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-12">
						<label>Account Description</label>
						<textarea class="form-control" name="coa_desc"><?php echo $coad->coa_desc;?></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="coa_id" value="<?php echo $coad->coa_id;?>">
				<input type="hidden" name="code" value="<?php echo $coad->coa_code;?>">
				<input type="hidden" name="code_prefix" value="<?php echo $coad->coa_codeprefix;?>">
				<a href="<?php echo base_url();?>reports/accounts" type="button" class="btn btn-default" data-dismiss="modal">Close</a>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		</form>
	<?php endforeach;
}
?>