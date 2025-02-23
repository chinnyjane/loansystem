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
  <li class="active"><a href="<?php echo base_url();?>user/user/edit/<?php echo $userid;?>">User Profile</a></li>
  <li><a href="<?php echo base_url();?>user/user/rights/<?php echo $userid;?>">User Rights</a></li>
  <li ><a href="<?php echo base_url();?>user/user/branch/<?php echo $userid;?>">Allowed Branch</a></li>
  </ul>
<form class="pure-form pure-form-aligned" method="post" action="">
	<div class="panel panel-success"><div class="panel-heading">User Profile</div>
	<div class="panel-body">
		<div class="row form-group"> 
		<div class="col-md-4"><label for="firstname" >First Name</label> 
		<?php if($right == true){?><input id="firstname" name="firstname" type="text" value="<?php echo $firstname;?>" placeholder="First Name" class="form-control input-sm" required><?php } else echo strtoupper($firstname);?>			
		</div>
        <div class="col-md-4"><label for="lastname">Last Name</label> 		
		<?php if($right == true){?>			
            <input id="lastname" name="lastname" type="text" value="<?php echo $lastname; ?>" placeholder="Last Name" class="form-control input-sm" required>
			<?php } else echo strtoupper($lastname);?>
		</div>
		<div class="col-md-4">
		<label for="lastname" >Email</label> 
		<?php if($right == true){?>	            
        <input id="email" name="email" type="text" value="<?php echo $email; ?>" placeholder="Email Address" class="form-control input-sm" required>
		<?php } else echo mailto($email, $email);?>
		</div>
		</div>
		<div class="row form-group">
		<div class="col-md-4"><label for="contact" >Contact Number</label> 
		<?php if($right == true){?><input id="contact" name="contact" type="text" value="<?php echo $contact; ?>" placeholder="Contact Number" class="form-control input-sm" required><?php } else echo strtoupper($contact);?>
		</div>
		<div class="col-md-4">
		<label for="group">Role</label> 
		<?php
			if($right == true){
				echo '<select name="group" class="form-control input-sm">';
				$role = $this->UserMgmt->get_roles();
					if($role->num_rows() >0){
						foreach ($role->result() as $r): ?>
						<option value="<?php echo $r->group_id;?>" <?php if($groupid == $r->group_id) echo "selected";?>><?php echo $r->name;?></option>
						<?php endforeach;
					}
					echo '</select>';
			} else echo $this->UserMgmt->get_role_byid($groupid);?>
		</div>
		<div class="col-md-4">
		<label for="branch">Branch</label> 
		<?php 
			if($right == true){	
				echo '<select name="branch" class="form-control input-sm">'
					.'<option disabled>Choose Branch</option>';
				$branch = $this->UserMgmt->get_branches();
					if($branch->num_rows() >0){
						foreach ($branch->result() as $br): ?>
						<option value="<?php echo $br->id;?>" <?php echo set_select("branch", $br->id);?>><?php echo $br->branchname;?></option>
						<?php endforeach;
					}
					
				echo '</select>';
			
			}else echo $this->UserMgmt->get_branch_by_id($branch);?>
		</div>
		</div>
		<div class="row form-group">
		<div class="col-md-4"><?php if($right == true){?><input type="hidden" name="userid" value="<?php echo $userid;?>"><input type="submit" class="btn btn-success form-control input-sm" name="Submit" value="Save User" ><?php } ?>
		</div>
		</div>
	</div>
	</div>
	
</form>
<?php 
//display this form if user has rights
if ($right == true) { ?>
<form class="pure-form pure-form-aligned" method="post" action="">
  <div class="panel panel-warning"><div class="panel-heading">Change Password</div>
	<div class="panel-body">
	<div class="row form-group">
		<div class="col-md-4">
		<label for="oldpassword">Old Password</label>
			<input id="oldpassword" name="oldpassword" type="password" placeholder="Old Password"  class="form-control input-sm" required>
		</div>
		<div class="col-md-4">
		<label for="newpassword">New Password</label>              
            <input id="newpassword" name="newpassword" type="password" placeholder="New Password"  class="form-control input-sm" required>
		</div>
		<div class="col-md-4">
		<label for="confirmpassword">Confirm Password</label>              
            <input id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm New Password"  class="form-control input-sm" required>
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-4">
			<input type="hidden" name="userid" value="<?php echo $userid;?>">      
		    <input type="submit" class="btn btn-warning" name="Submit" value="Update Password" >
		</div>
	</div>
</div>
</div>
  </form>
<?php } //end display user with rights?>

</div>
</div>

