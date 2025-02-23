<script type="text/javascript" src="<?php echo base_url();?>assets/js/modaljs.js"></script>
<?php
$account  = $this->Accounting->ChartOfAccounts();
$tmpl = array ('table_open'  => '<table class="table table-condensed table-hover" id="charge" >');
	$this->table->set_template($tmpl);
?>
<form method="post"  >
	<div class="panel panel-default">
		<div class="panel-heading"><a href="#"  data-toggle="modal" data-target="#addcharges" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New Charges</a></div>
		<div class="panel-body">
		
			<?php
			$this->table->set_heading("#", "Charge Type", "Charge Name", "Action");
			$charges = $this->Products->getCharges();
			if($charges->num_rows() > 0){
				$count = 1;
				foreach($charges->result() as $ch){
					$this->table->add_row($count,$ch->charge_type,$ch->charge_name,'<a href="'.base_url().'settings/charges/update/'.$ch->id.'" title="Update" data-target="#"  data-toggle="modal">Update</a>');
					$count++;
				}
			}else{
				$this->table->add_row("","","","");
			}
			
			echo $this->table->generate();
			?>
	
		</div>
		<div class="panel-footer">			
			<input type="submit" name="submit" value="Activate" class="btn btn-sm btn-success"> 
			<input type="submit" name="submit" value="Deactivate" class="btn btn-sm btn-danger">						
		</div>
	</div>
</form>			
	
	<script>
    $(document).ready(function() {
        $('#charge').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
    });
   </script>

   <div class="modal fade" id="addcharges" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
    <?php echo form_open_multipart(base_url().'settings/charges/action/add',' method="post" id="fileupload" class="jquerypost"');?>
    <div class="modal-content">
        	<div class="modal-header">
            	Add Charges
            </div>
            <div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<label>Charge Type</label>
						<select class="input-sm form-control" name="charge_type">
							<option value="F">Finance</option>
							<option value="NF">Non-Finance</option>							
						</select>
					</div>
					<div class="col-md-6">
						<label>Charge Name</label>
						<input type="text" class="input-sm form-control" name="charge_name">
					</div>
				</div>
            	<div class="row">
					<div class="col-md-12">
						<select class="input-sm form-control" name="coa_parent[]">
						<?php 
						if($account){
							foreach($account->result() as $coa): ?>
								<option value="<?php echo $coa->coa_id;?>"><?php echo $coa->coa_codeprefix.$coa->coa_code;?> - <?php echo $coa->coa_name;?></option>
						<?php endforeach;  }?>
						</select>
					</div>
				</div>
            </div>
            <div class="modal-footer">
            	
            	<input type="submit" name="submit" value="Add Charges" class="btn btn-primary"></div>
            </div>
      </form>
        </div>
	</div>