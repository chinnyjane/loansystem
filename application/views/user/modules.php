<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<div id="scroll" class="content" >
<h4 class="content-subhead"><a href="<?php echo base_url();?>user">Users</a> > Modules</h4>
<?php // if($this->auth->perms("User.Modules",$this->auth->user_id(),1) == TRUE){ ?>

<?php //echo validation_errors();
if(isset($status)) echo $status;
?><form class="form-horizontal" method="post" action="" name="addmodule">
<div class="panel panel-success"><div class="panel-heading">Add Module</div> 
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
      <button type="submit" class="btn btn-success" >Add Module</button>
</div>
</div>
        
		
    </fieldset>
   
</fieldset>
</form>
<?php  //} ?>


<?php $module = $this->UserMgmt->get_module();
if($module->num_rows() > 0){ 
$count = 1;
?><h2 class="sub-header">Modules</h2>
          <div class="table-responsive">
            
<form method="post">
<div class="panel panel-info"><div class="panel-heading">Add Module</div> 
    <table class="table table-hover table-bordered">
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
	<?php foreach ($module->result() as $mod): ?>
		<tr>
        	<td width="50px"><input type="checkbox" name="module[]"  class="case" value="<?php echo $mod->id; ?>" /></td>
					<td><?php echo $count; ?></td>
					<td><a href=""><?php echo $mod->module_name; ?></a></td>
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
	<?php //if($this->auth->perms("User.Modules",$this->auth->user_id(),4) == TRUE){ ?><div style="margin: 10px;">
	<input type="submit" class="btn btn-success btn-sm" name="submit" value="Activate"> &nbsp;<input type="submit" class="btn btn-danger btn-sm" name="submit" value="Deactivate"></div><?php //} ?>
	</form>
<?php }else{
	echo "<div>No Modules yet.</div>";
}

?>    
        
</div>
</div>