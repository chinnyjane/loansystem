<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<div id="scroll" class="content" >
<h4><a href="<?php echo base_url();?>user">Users</a> > Create New User</h4>
<p>&nbsp;</p>
    <form class="form-horizontal" method="post" action="">
    <?php if (isset($poststatus)) echo $poststatus;?>
	<div class="panel panel-info"><div class="panel-heading">User Profile</div>
	<div class="panel-body">
		<div class="row form-group">
			<div class="col-md-4"><label>First Name</label>
			<input id="firstname" name="firstname" type="text" placeholder="First Name" class="form-control input-sm" required>
			</div>
			<div class="col-md-4"><label>Last Name</label>
			<input id="lastname" name="lastname" type="text" placeholder="Last Name" class="form-control input-sm" required>
			</div>
			<div class="col-md-4"><label>Email</label>
			<input id="email" name="email" type="email" placeholder="Email"  class="form-control input-sm" required>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4"><label>Contact Number</label>
			<input id="contact" name="contact" type="text" placeholder="Contact Number" class="form-control input-sm" required>
			</div>
			<div class="col-md-4"><label>Role/Group</label>
			<select name="group" class="form-control input-sm">
            <option disabled selected>Choose Role/Group</option>
           <?php $role = $this->UserMgmt->get_roles();
				if($role->num_rows() >0){
					foreach ($role->result() as $r): ?>
                    <option value="<?php echo $r->group_id;?>"><?php echo $r->name;?></option>
                    <?php endforeach;
				} ?>
            </select>
			</div>
			<div class="col-md-4"><label>Branch</label>
			<select name="branch" class="form-control input-sm">
			<option disabled selected>Choose Branch</option>
           <?php $branch = $this->UserMgmt->get_branches();
				if($branch->num_rows() >0){
					foreach ($branch->result() as $br): ?>
                    <option value="<?php echo $br->id;?>"><?php echo $br->branchname;?></option>
                    <?php endforeach;
				}
				?>
            </select>
			</div>			
		</div>
		<div class="row form-group">
			<div class="col-md-4"><label>Password</label>
			<input id="password" name="password" type="password" placeholder="Password" class="form-control input-sm" required>
			</div>
			<div class="col-md-4"><label>Confirm Password</label>
			<input id="confirmpassword" name="confirmpassword" type="password" placeholder="Confirm Password" class="form-control input-sm" required>
			</div>
		</div>
		<div class="row form-group">
			
		</div>
	</div>
	</div>
   <div class="col-md-4"> <input type="submit" class="btn btn-success" name="submit" value="Add User"> &nbsp;<a href="<?php echo base_url();?>user" class="btn btn-warning">Cancel</a></div>
	</form> 
</div>
</div>