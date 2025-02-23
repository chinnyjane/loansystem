
<?php //echo validation_errors();
if($modules->num_rows() > 0){
	foreach($modules->result() as $mod){
		$modulename = $mod->module_name;
		$modulelink = $mod->module_link;
		$desc = $mod->description;
		if($mod->active == 1)
		$status = "active";
		else $status = "inactive";
	}
}
?>
<a href="<?php echo base_url();?>settings/user/modules" class="btn btn-primary btn-sm">Back to Modules</a><br/><br/>
<?php 
if($_POST){
	if(isset($error)){
	echo '<div class="alert alert-danger">'.$error;
	if(validation_errors())
	echo "<br/>".validation_errors();
	echo "</div>";
	}
	if(isset($success)) echo '<div class="alert alert-success">'.$success."</div>";
}	
?>

<form class="form-horizontal" method="post" action="" name="updatemodule">
<div class="panel panel-green"><div class="panel-heading">Module Details</div> 
<div class="panel-body"> 
<div class="row form-group">
	<div class="col-md-4"><label>Module Name</label>
	<input id="module" name="module" type="text" class="form-control input-sm" placeholder="Module Name" value="<?php echo $modulename;?>" required>
	</div>
	<div class="col-md-4"><label>Module Description</label>
	<input id="description" name="description" type="text" class="form-control input-sm" placeholder="Description" value="<?php echo $desc;?>" required>
	</div>
	<div class="col-md-4"><label>Module Link</label>
	<input id="link" name="link" type="text" class="form-control input-sm" placeholder="Module Link" value="<?php echo $modulelink;?>" required>
	</div>
</div> 
	<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>">
	<input type="hidden" name="submit" value="Update Module">      
	<button type="submit" class="btn btn-success" >Update Module</button>
</div>
</div>
</form>
<?php  //} ?>


