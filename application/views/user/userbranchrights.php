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
  <li ><a href="<?php echo base_url();?>user/user/rights/<?php echo $userid;?>">User Rights</a></li>
  <li  class="active"><a href="<?php echo base_url();?>user/user/branch/<?php echo $userid;?>">Allowed Branch</a></li>
  </ul>
<?php if($_POST){
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
}?>
<form action="" method="post">
<?php $branch = $this->UserMgmt->get_branches();	
if($branch->num_rows() >0){ ?>
<div class="panel panel-warning"><div class="panel-heading">User Allowed Branch</div>
	<table class="table table-hover">
        <thead>
            <tr>
                <th>Branch Name</th>
                <th>Allow</th>               
            </tr>
        </thead>
        <tbody>
	<?php foreach ($branch->result() as $br): ?>
    	<tr>
        	<td><?php echo $br->branchname; ?></td>
			<td><input type="hidden" name="branch[<?php echo $br->id;?>]" value="0" ><input type="checkbox" name="branch[<?php echo $br->id;?>]" id="<?php echo $br->id;?>" class="checkperm" value="1" >
			</td>
		</tr>
    <?php endforeach;?>	
    </tbody>
    </table>
</div>
<div style="margin:20px;"><button type="submit" class="btn btn-warning">Update Branch Rights</button></div>
<?php } ?>
</form>
</div>
</div>
</div>