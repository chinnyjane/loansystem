<?php //echo validation_errors();
if(isset($status)) echo $status;
?>
<div class="panel panel-default"><div class="panel-heading">Modules &nbsp; <button  class="btn btn-success btn-sm" data-toggle="modal" data-target="#addmodule"> <i class="fa fa-plus"></i> Add Module</button></div> 
<?php
if($modules->num_rows() > 0){ 
$count = 1;
?>
<div class="panel-body">
	
</div>
<form method="post">
<div class="table-responsive">
<table class="table table-hover table-bordered" id="tablemodule">
        <thead>
            <tr>
                <th class="column-check"><input class="check-all" type="checkbox" /></th>
                <th>#</th>
                <th>Module Name</th>
                <th>Module Link</th>
                <th>Module Description</th>	
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
	<?php foreach ($modules->result() as $mod): ?>
		<tr>
        	<td width="50px"><input type="checkbox" name="module[]"  class="case" value="<?php echo $mod->id; ?>" /></td>
					<td><?php echo $count; ?></td>
					<td><a href="<?php echo base_url();?>settings/user/modules/manage/<?php echo $mod->id; ?>"><?php echo $mod->module_name; ?></a></td>
					<td><a href="<?php echo base_url().$mod->module_link; ?>"><?php echo $mod->module_link; ?></a></td>
					<td><?php echo $mod->description; ?></td>
					<td><?php if($mod->active == 1) echo "active"; else echo "Inactive"; ?></td>
				</tr>
	<?php 
	$count++;
	endforeach; ?>	
    </tbody>
	</table>
	</div>	
	
<?php }else{
	echo "<div>No Modules yet.</div>";
}
?>           
<div class="panel-footer">
<input type="submit" class="btn btn-success btn-sm" name="submit" value="Activate"> &nbsp;<input type="submit" class="btn btn-danger btn-sm" name="submit" value="Deactivate">
</div>
	</form>
</div>
<?php echo $this->pagination->create_links(); ?>


<!-- ADD MODULE -->
<div class="modal fade" id="addmodule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
  <form class="form-horizontal" method="post" action="" name="addmodule">
	 <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New Module</h4>
      </div>
      <div class="modal-body">
	
	<div class="panel-body"> 
	<div class="row form-group">
		<div class="col-md-4"><label>Module Name</label>
		<input id="module" name="module" type="text" class="form-control input-sm" placeholder="Module Name" required>
		</div>
		<div class="col-md-4"><label>Module Description</label>
		<input id="description" name="description" type="text" class="form-control input-sm" placeholder="Description" required>
		</div>
		<div class="col-md-4"><label>Module Link</label>
		<input id="link" name="link" type="text" class="form-control input-sm" placeholder="Module Link" required>
		</div>
	</div> 
		<input type="hidden" name="submit" value="Add Module">
		  
	</div>
	
	</div>
	<div class="modal-footer">
		 <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-success btn-sm" >Add Module</button>
	</div>
</div>
</form>
</div>

<script>
    $(document).ready(function() {
        $('#tablemodule').dataTable({
			"dom": 'T<"clear">lfrtip',
			"tableTools": {
				"sSwfPath": "/swf/copy_csv_xls_pdf.swf"
			}
		});
    });
   </script>