<?php
$user = $this->UserMgmt->get_user_byid($userid);
	if($user->num_rows() > 0){
		foreach ($user->result() as $u){
			$firstname = $u->firstname;
			$lastname = $u->lastname;
			$email = $u->email;
			$groupid= $u->group_id;
			$branch = $u->branch_id;
			$userid = $u->id;
			$contact = $u->contact;
		}
	}
	
	$right = $this->auth->perms("User.Users",$this->auth->user_id(),3);	
 ?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<div id="scroll" class="content" >
<h4><a href="<?php echo base_url();?>user">Users</a> > <?php echo $firstname;?> <?php echo $lastname; ?></h4>
    <?php echo validation_errors("<div style='color: red; margin:10px;'>","</div>");
	if(isset($poststatus)) echo "<div style='color: red; margin:10px;'>".$poststatus."</div>";
	?>
<ul class="nav nav-tabs">
  <li><a href="<?php echo base_url();?>user/user/edit/<?php echo $userid;?>">User Profile</a></li>
  <li  class="active"><a href="<?php echo base_url();?>user/user/rights/<?php echo $userid;?>">User Rights</a></li>
  <li><a href="<?php echo base_url();?>user/user/branch/<?php echo $userid;?>">Allowed Branch</a></li>
  </ul>
<div class="panel panel-danger"><div class="panel-heading">User Rights</div>
<?php 
$module = $this->UserMgmt->get_module();
if($module->num_rows() > 0){	 ?>
     <form method="post" >
    <table class="table table-hover ">
        <thead>
            <tr>               
                <th>Module Name</th>
                <th width="15%" style="text-align:center">Create</th>
                <th width="15%" style="text-align:center">View</th>
                <th width="15%" style="text-align:center">Manage</th>
                <th width="15%" style="text-align:center">Delete</th>
            </tr>
        </thead>
        <tbody>
	<?php foreach ($module->result() as $mod){ ?>
		<tr>      	
					<td><?php echo $mod->module_name; ?></td>
					<?php $right = $this->UserMgmt->get_rights($userid,$mod->id);
					 if($right->num_rows() > 0){ 
						foreach ($right->result() as $r){ 
						if($r->active == 1) $check="checked";
						else $check = '';
						?>
					 <td style="text-align:center"><input type="hidden" name="rights[<?php echo $mod->id;?>][<?php echo $r->module_right;?>]" value="0"/>
					<input type="checkbox" name="rights[<?php echo $mod->id;?>][<?php echo $r->module_right;?>]" value="1" <?php echo $check;?> /></td>
					<?php } 				
					}else{?>
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
	<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
    <div style="margin: 20px"><input type="submit" name="Submit" value="Update Rights" class="btn btn-danger" /></div>
    </form>
	
<?php 
}else{
	echo "<div>No Modules yet.</div>";
} ?>
</div>
</div>
</div>