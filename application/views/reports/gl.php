<?php 
$br = $this->UserMgmt->get_branches();
?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addAccount" data-backdrop="static">Add Ledger</button>
<div class="row">
  <div class="col-lg-4">
  <label>Select Branch</label>    
      <select name="branch_id" id="branch" type="text" class="form-control">
		<option>ALL</option>		
		<?php
			foreach($br->result() as $b){
				if($branchid == $b->id) $select = 'selected';
				else $select='';
				echo "<option value='".$b->id."' ".$select.">".$b->branchname."</option>";				
			}
		?>		
	  </select>
     
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
<?php
$tmpl = array ('table_open'  => '<table class="table table-bordered table-condensed table-striped table-hover " id="glaccounts">' );
$this->table->set_template($tmpl);

$this->table->set_heading("Code", "Account Title","DR/CR", "Beginning Balance", "Ending Balance", "As of", "Action");

$glaccounts = $this->Accounting->branchGL($branchid);
if($glaccounts){
	$total = array();
	$bal['DR'] = 0;
	$bal['CR'] = 0;
	foreach($glaccounts->result() as $gl){
		$bal[$gl->normal_balance] +=  ($gl->op_balance);
		$this->table->add_row($gl->coa_codeprefix.$gl->coa_code." - ".$gl->coa_sub_code, $gl->coa_name,$gl->normal_balance, number_format($gl->op_balance,2),"","","<a href='".base_url()."reports/accounts/details/".$gl->coa_id."'>Update</a>");
	}
	
}
echo "<div class='table-responsive'>";
echo $this->table->generate();
echo "</div>";
echo number_format($bal['DR'],2)." = ".number_format($bal['CR'],2);
?>


<!-- Add Account Ledger -->
<div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
	<form action="<?php echo base_url();?>reports/accounts/addbranchGL" method="post" class="jquerypost">
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
						<select class="input-sm form-control" name="coa_code">
							<option value="">- No Parent Account - </option>
							<?php 
							if($account){
							foreach($account->result() as $coa): ?>
								<option value="<?php echo $coa->coa_id."-".$coa->coa_code;?>"> <?php echo $coa->coa_name;?> - <?php echo $coa->coa_codeprefix.$coa->coa_code;?></option>
							<?php endforeach;  }?>
						</select>
					</div>
					
				</div>
				<div class="row form-group">
					<div class="col-md-6">
						<label>Opening Balance</label>
						<input type="text" class="input-sm form-control" name="balance">
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
				<input type="hidden" name="branch_id" value="<?php echo $branchid;?>">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		</div>  
		</form>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#branch').on('change', function(){
			var branchid = $(this).val();
			window.location.assign('<?php echo base_url();?>reports/accounts/gl/'+branchid);
		});
		 $('#glaccounts').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
	});
</script>