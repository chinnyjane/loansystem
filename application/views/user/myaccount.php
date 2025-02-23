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
<div class="panel panel-primary"><div class="panel-heading">My Account</div>
<div class="panel-body">
	<div class="row form-group"> 
		<div class="col-md-4">
				<label>Username</label>
				<input type="text" class="form-control" name="username" value="<?php echo $username;?>" placeholder="Username" required>
		</div>
		</div>
		<div class="row form-group">
		<div class="col-md-4"><label for="firstname" >First Name</label> 
		<input id="firstname" name="firstname" type="text" value="<?php echo $firstname;?>" placeholder="First Name" class="form-control input-sm" required>	
		</div>
        <div class="col-md-4"><label for="lastname">Last Name</label> 				
            <input id="lastname" name="lastname" type="text" value="<?php echo $lastname; ?>" placeholder="Last Name" class="form-control input-sm" required>			
		</div>
		<div class="col-md-4">
		<label for="lastname" >Email</label> 		           
        <input id="email" name="email" type="text" value="<?php echo $email; ?>" placeholder="Email Address" class="form-control input-sm" required>
		</div>
	</div>
	<div class="row form-group">
	<div class="col-md-4"> <label>Contact Number</label>
	 <input class="form-control input-sm" id="contact" name="contact" type="text" value="<?php echo $contact; ?>" placeholder="Contact number" required>
	</div>
	<div class="col-md-4"> <label>Role/Group</label>
		<input  name="group" type="text" value="<?php echo $this->UserMgmt->get_role_byid($groupid);?>" class="form-control input-sm" readonly >
	</div>
	<div class="col-md-4"> <label>Branch</label>
		  <input  type="text" value="<?php echo $this->UserMgmt->get_branch_by_id($branch);?>"  class="form-control input-sm"  readonly>   
	</div>
	</div>
</div>
<div class="panel-footer"><input type="submit" name="Submit" value="Update Account" class="btn btn-primary btn-sm">
</div>
</div>
</form>