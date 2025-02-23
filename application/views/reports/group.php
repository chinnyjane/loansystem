<p><button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addAccount" data-backdrop="static">Add Account Group</button>
</p>
<div class="form-group">
<?php
$tmpl = array ('table_open'          => '<table class="table table-bordered table-condensed table-hover " id="tableuser">' );
$this->table->set_template($tmpl);
if($coagroup->num_rows() > 0){ ?>
	<div class="table-responsive">
		<?php 
		$count =1;
		foreach($coagroup->result() as $coa):
			$this->table->add_row($count, $coa->coa_codeprefix.$coa->coa_code, $coa->coa_name, $coa->coa_desc, "<a href='".base_url()."reports/accounts/cat_details/".$coa->coa_id."'>Update</a> &nbsp; <a href=''>Delete</a>");
			$count++;
		endforeach;
		$this->table->set_heading("#","Account Code", "Account Title", "Account Description", "Actions");
		echo $this->table->generate();
		
		?>
		
	</div>
<?php }else{
	echo "No Entries yet.";
}
?>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<script>
    $(document).ready(function() {
        $('#tableuser').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
    });
   </script>
<!-- Add Account Group -->
<div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
					<div class="col-md-12">
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