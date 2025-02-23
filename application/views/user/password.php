<?php
$user = $this->UserMgmt->get_user_byid($this->auth->user_id());
	if($user->num_rows() > 0){
		foreach ($user->result() as $u){
			$username = $u->username;
			$firstname = $u->firstname;
			$lastname = $u->lastname;
			$email = $u->email;
			$groupid= $u->group_id;
			$branch = $u->branch_id;
			$userid = $u->id;
			$contact = $u->contact;
		}
	}
echo validation_errors();
if(isset($status)) echo '<div class="alert alert-danger alert-dismissable">'.$status.'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
?>
<form class="pure-form pure-form-aligned" method="post" action="">
<div class="panel panel-danger"><div class="panel-heading"><b>Change Password</b></div>
<div class="panel-body">
	
		<div class="row form-group">
		<div class="col-md-4"><label for="firstname" >Current Password</label> 
		<input id="firstname" name="oldpassword" type="password" placeholder="Current Password" class="form-control" required>	
		</div>
        <div class="col-md-4"><label for="lastname">New Password</label> 				
            <input id="lastname" name="newpassword" type="password" placeholder="New Password" class="form-control" required>		
		</div>
		<div class="col-md-4">
		<label for="lastname" >Confirm New Password</label> 		           
        <input id="email" name="confirmpassword" type="password" placeholder="Confirm New Password" class="form-control" required>
		</div>
	</div>
	
</div>
<div class="panel-footer">
<input type="hidden" name="userid" value="<?php echo $this->auth->user_id();?>">
<input type="submit" name="Submit" value="Change Password" class="btn btn-danger">
</div>
</div>
</form>