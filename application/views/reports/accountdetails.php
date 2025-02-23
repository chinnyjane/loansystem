<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<p>Account Details</p>

<?php
if($coa_details->num_rows() >0){
	
	foreach($coa_details->result() as $coad): ?>
	<form action="<?php echo base_url();?>reports/accounts/updatedetails" class="jquerypost" method='post'>
		<div class="row form-group">
			<div class="col-md-6">
				<label>Choose Account Group</label>
						<select class="input-sm form-control" name="coa_category">
							<?php foreach($coagroup->result() as $coa_g): ?>
								<option value="<?php echo $coa_g->coa_id;?>" <?php if($coa_g->coa_id == $coad->coa_category) echo "selected";?>><?php echo $coa_g->coa_codeprefix.$coa_g->coa_code;?> - <?php echo $coa_g->coa_name;?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-6">
						<label>Account Category</label>
						<select class="input-sm form-control" name="coa_parent">
							<option value="">- No Parent Account - </option>
							<?php 
							if($account){
							foreach($account->result() as $coa): ?>
								<option value="<?php echo $coa->coa_id;?>" <?php if($coa->coa_id == $coad->coa_parent) echo "selected";?>><?php echo $coa->coa_codeprefix.$coa->coa_code;?> - <?php echo $coa->coa_name;?></option>
							<?php endforeach;  }?>
						</select>
					</div>
					
				</div>
				<div class="row form-group">
					<div class="col-md-6">
						<label>Account Code</label>
						<input type="text" class="input-sm form-control" name="coa_code" value="<?php echo $coad->coa_code;?>">
					</div>
					<div class="col-md-6">
						<label>Account Name</label>
						<input type="text" class="input-sm form-control" name="coa_name" value="<?php echo $coad->coa_name;?>">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-12">
						<label>Account Description</label>
						<textarea class="form-control" name="coa_desc"><?php echo $coad->coa_desc;?></textarea>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-3">
						<label>Normal Balance</label>
						<select class="form-control" name="norm_bal">
							<option value="">-NONE-</option>
							<option value="DR" <?php if($coad->normal_balance == "DR") echo "selected";?>>DR</option>
							<option value="CR" <?php if($coad->normal_balance == "CR") echo "selected";?>>CR</option>
						</select>
					</div>
					<div class="col-md-6">
						<label>With Subsidiary Ledger?</label><br/>
						<label>
							<input type="hidden" name="with_sub" id="with_sub" value=""> 
							<input type="radio" name="with_sub" id="with_sub" value="1" <?php if($coad->with_sub == "1") echo "checked";?>>
							Yes
						</label>&nbsp; &nbsp;
						<label>
							<input type="radio" name="with_sub" id="with_sub" value="0" <?php if($coad->with_sub == "0") echo "checked";?>>
							No
						</label>
					</div>
				</div>
				<div class="row form-group">
					<input type="hidden" name="coa_id" value="<?php echo $coad->coa_id;?>">
					<input type="hidden" name="code" value="<?php echo $coad->coa_code;?>">
					<div class="col-md-12 "><button class="btn btn-sm btn-primary ">Update Account Details</button></div>
				</div>
			</form>
	<?php endforeach;
}
?>