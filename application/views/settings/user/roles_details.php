
<?php $roles = $this->UserMgmt->role_byid($groupid);
//echo $groupid;
if($roles->num_rows() > 0) {
	foreach ($roles->result() as $r){
		$rolename = $r->name;
		$roledesc = $r->description;
	}
 } ?>
<?php echo validation_errors();?>

<form class="pure-form pure-form-aligned" method="post" action="">
<div class="panel panel-green"><div class="panel-heading">Modify Role</div>
	<div class="panel-body">
		<div class="row form-group">                   
        <div class="col-md-4"><input id="role" name="role" type="text" value="<?php echo $rolename;?>" class="form-control input-sm" required></div>
		<div class="col-md-4"><input id="description" name="description" type="text" value="<?php echo $roledesc; ?>" class="form-control input-sm" required></div>
		<div class="col-md-4"><input type="submit" class="btn btn-success" name="Submit" value="Edit Role" /></div>
		</div>
	</div>
</div>
</form>



<div class="panel panel-success"><div class="panel-heading">Role/Group Rights</div>
<?php 
$config['base_url'] = base_url()."settings/user/roles/edit/".$groupid;				
$config['per_page'] = 5;
$config['total_rows'] = $this->UserMgmt->get_total_records('modules', '1');
$config['uri_segment'] = 6;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
$module = $this->UserMgmt->get_records_from('modules' ,$config['per_page'], $segment, 1);
//$module = $this->UserMgmt->get_module();
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
<?php echo $this->pagination->create_links(); ?>
<div ><input type="submit" class="btn btn-warning btn-sm" name="Submit" value="Update Rights"/> </div>
</form>
<?php }else{
	echo "<div>No Modules yet.</div>";
}?> 
