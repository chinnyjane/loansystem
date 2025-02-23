<p><button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addGroup" data-backdrop="static">Add Account Group</button> &nbsp;&nbsp;&nbsp;&nbsp; <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addAccount" data-backdrop="static">Add Ledger</button>
</p>

<div class="form-group">
<?php

$tmpl = array ('table_open'  => '<table class="table table-bordered table-condensed table-striped table-hover " id="tableuser">' );
$this->table->set_template($tmpl);
if($coagroup->num_rows() > 0){ ?>
	<div class="table-responsive">
		<?php 
		$count =1;
		
		
		foreach($coagroup->result() as $coa):
			$tab= "";
			$c =1;
			while ($c <= $coa->coa_order){
				$tab .= "&nbsp;&nbsp;&nbsp;";
				$c++;
			}
			$this->table->add_row($count, $tab.$coa->coa_codeprefix.$coa->coa_code, $tab.$coa->coa_name, "","","<a href='".base_url()."reports/accounts/cat_details/".$coa->coa_id."'>Update</a> &nbsp; <a href=''>Delete</a>");
			$accounting = $this->Accounting->getAccountByCategory($coa->coa_id);
			$count++;
			if($accounting){
			if($accounting->num_rows() >0){
				$parent = '';
				
				$this_parent = "";
				foreach($accounting->result() as $acct):
					
					if($acct->with_sub == 1) $sub = "<i class='fa fa-check'></i>"; else $sub="";
					$this->table->add_row($count, $tab."&nbsp;&nbsp;&nbsp;"."<i class='fa fa-arrow-right'></i> &nbsp;".$coa->coa_codeprefix.$acct->coa_code, $tab."&nbsp;&nbsp;&nbsp;".$acct->coa_name, $acct->normal_balance, $sub, "<a href='".base_url()."reports/accounts/details/".$acct->coa_id."'>Update</a> &nbsp; <a href=''>Delete</a>");
					$parent = $acct->coa_id;
					$this_parent = $acct->coa_parent;
					$count++;
				endforeach;
			}
			}
		endforeach;
		$this->table->set_heading("#","Account Code", "Account Title", "Normal Balance","With Subsidiary Ledger", "Actions");
		echo $this->table->generate();
		
		?>
		
	</div>
<?php }else{
	echo "No Entries yet.";
}
?>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>

<!-- Add Account Group -->
<div class="modal fade" id="addGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
	<form action="<?php echo base_url();?>reports/accounts/addgroup" method="post" class="jquerypost">
		<div class="modal-content">
			<div class="modal-header">
				<b>Add Account Group</b>
			</div>
			<div class="modal-body">
				<div class="row form-group">
					<div class="col-md-3">
						<label>ACode Prefix</label>
						<input type="text" class="input-sm form-control" name="coa_codeprefix" placeholder="A">
					</div>
					<div class="col-md-3">
						<label>Account Code</label>
						<input type="text" class="input-sm form-control" name="coa_code">
					</div>
					<div class="col-md-6">
						<label>Account Title</label>
						<input type="text" class="input-sm form-control" name="coa_name">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-9">
						<label>Account Group</label>
						<select class="form-control" name="coa_parent">
							<option value=""> - NONE - </option>
							<?php 
							if($coagroup->num_rows() > 0){
								foreach($coagroup->result() as $coa) :?>
							<option value="<?php echo $coa->coa_id;?>"><?php echo $coa->coa_codeprefix.$coa->coa_code." - ".$coa->coa_name;?></option>	
							<?php 
								endforeach;
							} ?>	
						</select>
					</div>
					<div class="col-md-3">
						<label>Account Group</label>
						<input type="number" name="coa_order" class="form-control">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-12">
						<label>Account Description</label>
						<textarea class="form-control" name="coa_desc"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		</div>  
		</form>
	</div>
</div>
<!-- END -->

<!-- Add Account Ledger -->
<div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
	<form action="<?php echo base_url();?>reports/accounts/add_account" method="post" class="jquerypost">
		<div class="modal-content">
			<div class="modal-header">
				<b>Add Account </b>
			</div>
			<div class="modal-body">
				<div class="row form-group">
					<div class="col-md-6">
						<label>Choose Account Group</label>
						<select class="input-sm form-control" name="coa_category">
							<?php foreach($coagroup->result() as $coa_g): ?>
								<option value="<?php echo $coa_g->coa_id;?>"><?php echo $coa_g->coa_codeprefix.$coa_g->coa_code;?> - <?php echo $coa_g->coa_name;?></option>
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
								<option value="<?php echo $coa->coa_id;?>"><?php echo $coa->coa_codeprefix.$coa->coa_code;?> - <?php echo $coa->coa_name;?></option>
							<?php endforeach;  }?>
						</select>
					</div>
					
				</div>
				<div class="row form-group">
					<div class="col-md-6">
						<label>Account Code</label>
						<input type="text" class="input-sm form-control" name="coa_code">
					</div>
					<div class="col-md-6">
						<label>Account Name</label>
						<input type="text" class="input-sm form-control" name="coa_name">
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-12">
						<label>Account Description</label>
						<textarea class="form-control" name="coa_desc"></textarea>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-6">
						<label>Normal Balance</label>
						<select class="form-control" name="norm_bal">
							<option value="">-None-</option>
							<option value="DR">DR</option>
							<option value="CR">CR</option>
						</select>
					</div>
					<div class="col-md-6">
						<label>With Subsidiary Ledger?</label><br/>
						<label>
							<input type="radio" name="with_sub" id="with_sub" value="1" checked>
							Yes
						</label>&nbsp; &nbsp;
						<label>
							<input type="radio" name="with_sub" id="with_sub" value="0" >
							No
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		</div>  
		</form>
	</div>
</div>
<!-- END -->

<script>
	$(document).ready(function(){
		
		 $('#tableuser').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
	});
</script>