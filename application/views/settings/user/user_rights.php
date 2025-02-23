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
	
	$right = $this->auth->perms("Profile.Rights",$this->auth->user_id(),3);	
 echo validation_errors("<div style='color: red; margin:10px;'>","</div>");
	if(isset($poststatus)) echo "<div style='color: red; margin:10px;'>".$poststatus."</div>";
	?>
<ul class="nav nav-pills">
	<?php $this->settings->subsubmenu("Profile",$userid,$subact);?>
  </ul>
    <?php echo validation_errors("<div style='color: red; margin:10px;'>","</div>");
	if(isset($poststatus)) echo "<div style='color: red; margin:10px;'>".$poststatus."</div>";
	?>


<?php 
$config['base_url'] = base_url()."profile/rights/".$userid;				
$config['per_page'] = 8;
$config['total_rows'] = $this->UserMgmt->get_total_records('modules', '1');
$config['uri_segment'] = 4;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
$module = $this->UserMgmt->get_records_from('modules' ,$config['per_page'], $segment, 1);
//$module = $this->UserMgmt->get_module();
if($module->num_rows() > 0){
$count = $segment +1; ?>
     <form method="post" >
	 <div class="panel panel-danger"><div class="panel-heading"><b><?php echo $firstname." ".$lastname;?></b> - Rights</div>
    <table class="table table-hover ">
        <thead>
            <tr>  
				<th>#</th>
                <th>Module Name</th>
                <th width="15%" style="text-align:center">Create</th>
                <th width="15%" style="text-align:center">View</th>
                <th width="15%" style="text-align:center">Manage</th>
                <th width="15%" style="text-align:center">Delete</th>
            </tr>
        </thead>
        <tbody>
	<?php foreach ($module->result() as $mod){ ?>
		<tr> <td><?php echo $count;?></td><td><?php echo $mod->module_name; ?></td>
		<?php $right = $this->UserMgmt->get_rights($userid,$mod->id);
					 if($right->num_rows() > 0){ 
						foreach ($right->result() as $r){ 
						if($r->active == 1) $check="checked";
						else $check = '';
						?><td style="text-align:center"><input type="hidden" name="rights[<?php echo $mod->id;?>][<?php echo $r->module_right;?>]" value="0"/>
					<input type="checkbox" name="rights[<?php echo $mod->id;?>][<?php echo $r->module_right;?>]" value="1" <?php echo $check;?> /></td>
					<?php } 				
					}else{					
					?>
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
	<?php
	$count++;
	} ?>
    </tbody>
	</table>
	<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
	
    
	
<?php 
}else{
	echo "<div>No Modules yet.</div>";
} ?>
</div>
<div style="margin: 20px"><input type="submit" name="Submit" value="Update Rights" class="btn btn-danger" /><div style="float: right; "><?php echo $this->pagination->create_links(); ?></div></div>
    </form>

