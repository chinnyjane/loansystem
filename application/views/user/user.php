<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<div id="scroll" class="content" >
	<h3>Users</h3>
	<a href="<?php echo base_url();?>user/create" class="btn btn-xs btn-info">Create New User</a>
	<a href="<?php echo base_url();?>user/roles" class="btn btn-xs btn-info">Roles</a>
	<a href="<?php echo base_url();?>user/branch" class="btn btn-xs btn-info">Branches</a>
	<a href="<?php echo base_url();?>user/modules" class="btn btn-xs btn-info">Modules</a>
	<br/><br/>
	<?php 
	//$users = $this->UserMgmt->get_users();
	if($users->num_rows() > 0)
	{	
	?>
	<form method="post"  class="pure-form">
	 <div class="table-responsive">
       <table class="table table-hover table-bordered">	
		<thead>
		<tr>
		<th class="column-check"><input class="check-all" type="checkbox" /></th>
		<th>ID</th><th>Name</th><th>Email</th><th>Branch</th><th>Role</th><th>Last Login</th><th>Status</th>
		</tr>
		</thead>
		<tbody>
			<?php foreach($users->result() as $u): ?>
		<tr>
		<td class="column-check"><input type="checkbox"  class="case" name="user[]" value="<?php echo $u->id; ?>" /></td>
		<td><?php echo $u->id; ?></td>
		<td><a href="<?php echo base_url();?>user/user/edit/<?php echo $u->id;?>"><?php echo $u->lastname.", ".$u->firstname; ?></a></td>
		<td><?php echo $u->email; ?></td>
		<td><?php  $br = $this->UserMgmt->get_branch_by_id($u->branch_id); 
		echo $br;
		?></td>
		<td><?php $gr = $this->UserMgmt->get_role_byid($u->group_id); 
		echo $gr;
		
		?></td>
		<td><?php echo $u->last_login;?></td>
		<td><?php
			if($u->deleted == 1) echo "Deleted";
			elseif($u->active == 1) echo "Active";
			elseif($u->active == 0 ) echo "Deactivated";
		?></td>
		</tr>
			<?php endforeach;?>
					
			</tbody>
			</table>
			</div>
			<?php echo $this->pagination->create_links(); ?>
			<br/>
		<?php if($this->auth->perms("User.Users",$this->auth->user_id(),3) == TRUE){ ?>
			<input type="submit" name="submit" value="Activate" class="btn btn-xs btn-success"> 
			<input type="submit" name="submit" value="Deactivate" class="btn btn-xs btn-danger">
			<?php } ?>
			<?php if($this->auth->perms("User.Users",$this->auth->user_id(),4) == TRUE){ ?>
			<input type="submit" name="submit" value="Delete" class="btn btn-xs btn-default">
			<input type="submit" name="submit" value="Permanent Delete" class="btn btn-xs btn-default">
			<?php } ?>
			</form>			
	<?php 
	
	}else echo "No Users yet.";
	?>
	
	</div>
</div>

<!-- Loan Calculator -->
<div class="modal fade" id="loancalculator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Loan Calculator</h4>
      </div>
      <div class="modal-body">
		<?php $this->load->view('user/loans/loancalculator');?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>        
      </div>
    </div>
  </div>
</div>
<!-- END of Loan Calculator-->