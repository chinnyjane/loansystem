<?php $roles = $this->UserMgmt->role_byid($groupid);
if($roles->num_rows() > 0) {
	foreach ($roles->result() as $r){
		$rolename = $r->name;
		$roledesc = $r->description;
	}
 } ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
 <div id="scroll" class="content" >
<h4><a href="<?php echo base_url();?>user">Users</a> > <a href="<?php echo base_url();?>user/roles">Roles</a> > <?php echo $rolename;?></h4>
<?php echo validation_errors();?>
<div class="col-md-4">
<form class="pure-form pure-form-aligned" method="post" action="">
<div class="panel panel-success"><div class="panel-heading">Modify Role</div>
	<div class="panel-body">
		<div class="row form-group">                   
        <div class="col-md-12"><input id="role" name="role" type="text" value="<?php echo $rolename;?>" class="form-control" required></div>
		</div>
		<div class="row form-group">
		<div class="col-md-12"><input id="description" name="description" type="text" value="<?php echo $roledesc; ?>" class="form-control" required></div>
		</div>
		<div class="row form-group">
        <div class="col-md-9"><input type="submit" class="btn btn-warning" name="Submit" value="Edit Role" /></div>
		</div>
	</div>
</div>
</div>
</form>
</div>

<div class="col-md-7">
<div class="panel panel-info"><div class="panel-heading">Role/Group Rights</div>
<?php 
$module = $this->UserMgmt->get_module();
if($module->num_rows() > 0){
	 ?>
     <form method="post" >
     <table class="table table-hover">
        <thead>
            <tr>               
                <th>Module Name</th>
                <th width="20%" style="text-align:center">Create</th>
                <th width="20%" style="text-align:center">View</th>
                <th width="20%" style="text-align:center">Manage</th>
                <th width="20%" style="text-align:center">Delete</th>
            </tr>
        </thead>
        <tbody>
	<?php foreach ($module->result() as $mod){ ?>
		<tr>      	
					<td><?php echo $mod->module_name; ?></td>
					<?php $right = $this->UserMgmt->get_group_rights($groupid,$mod->id);
					 if($right->num_rows() > 0){ 
						foreach ($right->result() as $r){ 
						if($r->active == 1) $check="checked";
						else $check = '';
						?>
					 <td style="text-align:center"><input type="hidden" name="rights[<?php echo $mod->id;?>][<?php echo $r->module_right;?>]" value="0"/>
					<input type="checkbox" name="rights[<?php echo $mod->id;?>][<?php echo $r->module_right;?>]" value="1" <?php echo $check;?> /></td>
					<?php } ?>					 
					<?php }else{?>
					<td style="text-align:center"><input type="hidden" name="rights[<?php echo $mod->id;?>][1]" value="0"/>
					<input type="checkbox" name="rights[<?php echo $mod->id;?>][1]" value="1"/></td>
					<td style="text-align:center"><input type="hidden" name="rights[<?php echo $mod->id;?>][2]" value="0"/>
					<input type="checkbox" name="rights[<?php echo $mod->id;?>][2]" value="1"/></td>
					<td style="text-align:center"><input type="hidden" name="rights[<?php echo $mod->id;?>][3]" value="0"/>
					<input type="checkbox" name="rights[<?php echo $mod->id;?>][3]"  value="1"/></td>
                    <td style="text-align:center"><input type="hidden" name="rights[<?php echo $mod->id;?>][4]" value="0"/>
					<input type="checkbox" name="rights[<?php echo $mod->id;?>][4]" value="1"/></td>
					<?php } ?>
				</tr>
	<?php } ?>
    </tbody>
	</table>
	<input type="hidden" name="groupid" value="<?php echo $groupid;?>"/>
</div>
<div style="margin: 10px;"><input type="submit" class="btn btn-warning" name="Submit" value="Update Rights"/></div>
</form>
<?php }else{
	echo "<div>No Modules yet.</div>";
}?> 
</div>
</div>
</div>