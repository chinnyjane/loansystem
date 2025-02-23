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
	
	$right = $this->auth->perms("Profile.Branch",$this->auth->user_id(),3);	
 echo validation_errors("<div style='color: red; margin:10px;'>","</div>");
	if(isset($poststatus)) echo "<div style='color: red; margin:10px;'>".$poststatus."</div>";
	?>
<ul class="nav nav-pills">
	<?php $this->settings->subsubmenu("Profile",$userid,$subact);?>
  </ul>
  
  <?php echo validation_errors("<div style='color: red; margin:10px;'>","</div>");
	if(isset($error)) echo $error;
	?>
<form action="" method="post">
<?php 
$config['base_url'] = base_url()."profile/branch/".$userid;				
$config['per_page'] = 15;
$config['total_rows'] = $this->UserMgmt->get_total_records('branches', '1');
$config['uri_segment'] = 4;
$this->pagination->initialize($config);
$segment = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
$branch = $this->UserMgmt->get_records_from('branches' ,$config['per_page'], $segment, '1');
//$branch = $this->UserMgmt->get_branches();	
if($branch->num_rows() >0){ 
$count = $segment +1;
?>
<div class="panel panel-warning"><div class="panel-heading"><b><?php echo $firstname." ".$lastname;?></b> - Allowed Branch</div>
<?php
$tmpl = array ('table_open' => '<table class="table table-hover">' );
$this->table->set_template($tmpl);
$this->table->set_heading("#", "Branch Name", "Allow", "Approved");
foreach ($branch->result() as $br){
	$data = array("branch_id"=> $br->id,
				"user_id" => $userid,
				"active" => 1,
				'approved' => 1);
	if($this->UserMgmt->check_branchrights($data) == true){
	$check = "checked";
	$app = 'yes';
	}else{ $check='';
	$app = 'no';}
	$this->table->add_row($count, $br->branchname,'<input type="hidden" name="branch['.$br->id.']" value="0" ><input type="checkbox" name="branch['.$br->id.']" id="'.$br->id.'" class="checkperm" value="1" '.$check.'>',$app );
	$count++;
}
echo $this->table->generate();
 ?>
<input type="hidden" name="userid" value="<?php echo $userid;?>">
<div class="panel-footer" >
	<button type="submit" class="btn btn-warning">Update Branch Rights</button>
	<div style="float: right; "><?php echo $this->pagination->create_links(); ?>
	</div>
</div>
<?php } ?>

</div>
</form>
