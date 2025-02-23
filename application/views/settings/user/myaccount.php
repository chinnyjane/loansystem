<?php
$user = $this->UserMgmt->get_user_byid($this->auth->user_id());
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
	
 ?>
<form class="pure-form pure-form-aligned" method="post" action="">
<div class="panel panel-success"><div class="panel-heading">My Account</div>
<div class="panel-body">
	<div class="row form-group"> 
		<div class="col-md-3">
				<label>Username</label>
				<input type="text" class="form-control input-sm" name="username" value="<?php echo $username;?>" placeholder="Username" required>
			</div>
		<div class="col-md-3"><label for="firstname" >First Name</label> 
		<?php if($right == true){?><input id="firstname" name="firstname" type="text" value="<?php echo $firstname;?>" placeholder="First Name" class="form-control input-sm" required><?php } else echo strtoupper($firstname);?>			
		</div>
        <div class="col-md-3"><label for="lastname">Last Name</label> 		
		<?php if($right == true){?>			
            <input id="lastname" name="lastname" type="text" value="<?php echo $lastname; ?>" placeholder="Last Name" class="form-control input-sm" required>
			<?php } else echo strtoupper($lastname);?>
		</div>
		<div class="col-md-3">
		<label for="lastname" >Email</label> 
		<?php if($right == true){?>	            
        <input id="email" name="email" type="text" value="<?php echo $email; ?>" placeholder="Email Address" class="form-control input-sm" required>
		<?php } else echo mailto($email, $email);?>
		</div>
		</div>
</div>
</div>
</form>
	<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
	
    <?php echo validation_errors('<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a>',"</div>");
	if(isset($poststatus)) echo '<div class="alert alert-success">'
        .'<a href="#" class="close" data-dismiss="alert">&times;</a>'.$poststatus."</div>";
	?>
    <div class="row">
   <div style="width:50%; float:left">
   <div class="admin-box">
	<h3>Account</h3>
    <form class="form-horizontal" method="post" action="">
     <fieldset>
		<div class="form-group">
        <label class="control-label col-xs-3" for="firstname">First Name</label>
        <div class="col-xs-8">
            <input class="form-control" id="firstname" name="firstname" type="text" value="<?php echo $firstname;?>" placeholder="First Name" required>
        </div>
    </div>
	<div class="form-group">
        <label class="control-label col-xs-3" for="lastname">Last Name</label>
        <div class="col-xs-8">
            <input class="form-control" id="lastname" name="lastname" type="text" value="<?php echo $lastname; ?>" placeholder="Last Name" required>
        </div>
    </div>
      <div class="form-group">
        <label class="control-label col-xs-3" for="email">Email</label>
        <div class="col-xs-8">
            <input class="form-control" id="email" name="email" type="text" value="<?php echo $email; ?>" placeholder="Email address" required>
        </div>
    </div>
	<div class="form-group">
        <label class="control-label col-xs-3" for="contact">Contact Number</label>
        <div class="col-xs-8">
            <input class="form-control" id="contact" name="contact" type="text" value="<?php echo $contact; ?>" placeholder="Contact number" required>
        </div>
    </div>
	<div class="form-group">
        <label class="control-label col-xs-3" for="group">Role</label>
        <div class="col-xs-8">
            <input  name="group" type="text" value="<?php echo $this->UserMgmt->get_role_byid($groupid);?>" class="form-control" readonly >            
			<?php //echo $this->UserMgmt->get_role_byid($groupid);?>
        </div>
    </div>
     <div class="form-group">
        <label class="control-label col-xs-3" for="branch">Branch</label>
        <div class="col-xs-8">
            <input  type="text" value="<?php echo $this->UserMgmt->get_branch_by_id($branch);?>"  class="form-control"  readonly>            
            <?php //echo $this->UserMgmt->get_branch_by_id($branch);?>
        </div>
    </div>
      
        <div class="form-group">   
		 <label class="control-label col-xs-3" ></label>
        	<input type="hidden" name="userid" value="<?php echo $userid;?>">       
		    <input type="submit" class="btn btn-warning" name="Submit" value="Update Account" > 
        </div>
	
        
   
    </fieldset>
	</form> 
    </div>
    </div>

    <div style="width:50%; float:left">
  <div class="admin-box">
  <h3>Change Password</h3>
 
  <form class="pure-form pure-form-aligned" method="post" action="">
     <fieldset>
     	<div class="pure-control-group">
        	<label for="oldpassword">Old Password</label>              
            <input id="oldpassword" name="oldpassword" type="password" placeholder="Password" required>
        </div>
   		<div class="pure-control-group">
        	<label for="newpassword">New Password</label>              
            <input id="newpassword" name="newpassword" type="password" placeholder="Password" required>
        </div>
          <div class="pure-control-group">
          	<label for="confirmpassword">Confirm Password</label>              
            <input id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm Password" required>
        </div>
        <div class="pure-controls"> 
        	<input type="hidden" name="userid" value="<?php echo $userid;?>">      
		    <input type="submit" class="pure-button pure-button-primary" name="Submit" value="Update Password" >
        </div>
        </fieldset>
  </form>
  </div> 
</div>
</div>
<div style="clear:both"></div>

</div>

</div>
<div style="clear:both"></div>

